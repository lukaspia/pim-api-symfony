<?php

namespace App\Service;

use App\Entity\Product;

interface ProductPriceManagerInterface
{
    public function recordPriceChange(Product $product, string $oldPrice, string $newPrice): void;
}
