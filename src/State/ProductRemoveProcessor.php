<?php

declare(strict_types=1);


namespace App\State;

use App\Entity\Product;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Enum\ProductStatus;
use Doctrine\ORM\EntityManagerInterface;

readonly class ProductRemoveProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * @param Product $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        $data->setStatus(ProductStatus::DELETED);

        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }
}
