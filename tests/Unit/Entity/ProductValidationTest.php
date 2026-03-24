<?php

declare(strict_types=1);


namespace App\Tests\Unit\Entity;

use App\Entity\Product;
use App\Enum\Currency;
use PHPUnit\Framework\TestCase;

class ProductValidationTest extends TestCase
{
    public function testSupportedCurrencies(): void
    {
        $product = new Product();

        $cases = [
            Currency::PLN,
            Currency::EUR,
            Currency::USD,
        ];

        foreach ($cases as $currency) {
            $product->setCurrency($currency);
            $this->assertSame($currency, $product->getCurrency());
        }
    }

    public function testPriceMustBePositive(): void
    {
        $product = new Product();

        $product->setPrice('100.00');
        $this->assertGreaterThan(0, (float)$product->getPrice());

        $product->setPrice('0.00');
        $this->assertEquals(0, (float)$product->getPrice());

        $product->setPrice('-10.50');
        $this->assertLessThan(0, (float)$product->getPrice());
    }
}
