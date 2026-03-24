<?php

declare(strict_types=1);


namespace App\Service;

use App\Entity\PriceHistory;
use App\Entity\Product;
use App\Message\ProductPriceChanged;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class ProductPriceManager implements ProductPriceManagerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MessageBusInterface $bus
    ) {
    }

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
