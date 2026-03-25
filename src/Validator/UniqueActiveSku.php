<?php

declare(strict_types=1);


namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class UniqueActiveSku extends Constraint
{
    public string $message = 'To SKU jest już używane przez inny aktywny produkt.';

    public function getTargets(): string|array
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
