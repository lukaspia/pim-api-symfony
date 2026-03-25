<?php

declare(strict_types=1);


namespace App\Validator;

use App\Repository\ProductRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueActiveSkuValidator extends ConstraintValidator
{
    public function __construct(
        private readonly ProductRepository $productRepository
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueActiveSku) {
            throw new UnexpectedTypeException($constraint, UniqueActiveSku::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        $product = $this->context->getObject();

        $existingProduct = $this->productRepository->findActiveBySku($value, $product->getId());

        if ($existingProduct) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
