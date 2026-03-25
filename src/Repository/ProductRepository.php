<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    /**
     * @param \Doctrine\Persistence\ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param string $sku
     * @param int|null $excludeId
     * @return \App\Entity\Product|null
     */
    public function findActiveBySku(string $sku, ?int $excludeId = null): ?Product
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.sku = :sku')
            ->andWhere('p.status != :status')
            ->setParameter('sku', $sku)
            ->setParameter('status', 'deleted');

        if ($excludeId) {
            $qb->andWhere('p.id != :id')
                ->setParameter('id', $excludeId);
        }

        return $qb->getQuery()->getOneOrNullResult();
    }
}
