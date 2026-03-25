<?php

namespace App\Tests\Functional;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Entity\Product;
use App\Entity\PriceHistory;
use Doctrine\ORM\EntityManagerInterface;

class ProductTest extends ApiTestCase
{
    protected static ?bool $alwaysBootKernel = true;

    private Client $client;
    private EntityManagerInterface $em;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->em = static::getContainer()->get('doctrine')->getManager();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->em->createQuery('DELETE FROM ' . PriceHistory::class)->execute();
        $this->em->createQuery('DELETE FROM ' . Product::class)->execute();
        $this->em->close();
        unset($this->em);
    }

    public function testUniqueActiveSkuValidation(): void
    {
        $options = [
            'headers' => [
                'Content-Type' => 'application/ld+json',
                'Accept' => 'application/ld+json',
            ],
            'json' => [
                'name' => 'Laptop Pro',
                'sku' => 'LAP-123',
                'price' => '5000.00',
                'currency' => 'PLN',
                'status' => 'active'
            ]
        ];

        $this->client->request('POST', '/api/products', $options);
        self::assertResponseStatusCodeSame(201);

        $this->client->request('POST', '/api/products', $options);
        self::assertResponseStatusCodeSame(422);
    }

    public function testPriceHistoryLoggingOnPatch(): void
    {
        $response = $this->client->request('POST', '/api/products', [
            'headers' => [
                'Content-Type' => 'application/ld+json',
                'Accept' => 'application/ld+json',
            ],
            'json' => [
                'name' => 'Smartphone X',
                'sku' => 'SM-X',
                'price' => '1000.00',
                'currency' => 'EUR',
                'status' => 'active'
            ]
        ]);

        $data = $response->toArray();
        $iri = $data['@id'];
        $productId = $data['id'];

        $this->client->request('PATCH', $iri, [
            'json' => ['price' => '1250.00'],
            'headers' => [
                'Content-Type' => 'application/merge-patch+json',
                'Accept' => 'application/ld+json',
            ]
        ]);
        self::assertResponseStatusCodeSame(200);

        $this->em->clear();
        $product = $this->em->getRepository(Product::class)->find($productId);

        self::assertCount(1, $product->getPriceHistories());

        self::assertEquals('1000.00', $product->getPriceHistories()[0]->getOldPrice());
        self::assertEquals('1250.00', $product->getPriceHistories()[0]->getNewPrice());
    }
}
