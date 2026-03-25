<?php

declare(strict_types=1);


namespace App\Service;

use App\Entity\PriceHistory;
use App\Entity\Product;
use App\Message\ProductPriceChanged;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 *
 */
readonly class ProductPriceManager implements ProductPriceManagerInterface
{
    /**
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \Symfony\Component\Messenger\MessageBusInterface $bus
     */
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MessageBusInterface $bus
    ) {
    }

    /**
     * @param \App\Entity\Product $product
     * @param string $oldPrice
     * @param string $newPrice
     * @return void
     * @throws \Symfony\Component\Messenger\Exception\ExceptionInterface
     */
    public function recordPriceChange(Product $product, string $oldPrice, string $newPrice): void
    {
        $history = new PriceHistory();
        $history->setProduct($product);
        $history->setOldPrice($oldPrice);
        $history->setNewPrice($newPrice);

        $this->entityManager->persist($history);
        $this->entityManager->flush();

        $this->bus->dispatch(
            new ProductPriceChanged(
                $product->getId(),
                (float)$oldPrice,
                (float)$newPrice
            )
        );
    }
}
