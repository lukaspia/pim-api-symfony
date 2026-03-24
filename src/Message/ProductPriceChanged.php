<?php

declare(strict_types=1);


namespace App\Message;


readonly class ProductPriceChanged
{
    public function __construct(
        public int $productId,
        public float $oldPrice,
        public float $newPrice
    ) {
    }
}
