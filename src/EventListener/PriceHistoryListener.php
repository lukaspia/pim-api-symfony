<?php

namespace App\EventListener;

use App\Entity\Product;
use App\Service\ProductPriceManagerInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Events;

/**
 *
 */
#[AsDoctrineListener(event: Events::postUpdate, connection: 'default')]
readonly class PriceHistoryListener
{
    public function __construct(private ProductPriceManagerInterface $priceManager)
    {
    }

    /**
     * @param \Doctrine\ORM\Event\PostUpdateEventArgs $args
     * @return void
     */
    public function postUpdate(PostUpdateEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof Product) {
            return;
        }

        $changeSet = $args->getObjectManager()
            ->getUnitOfWork()
            ->getEntityChangeSet($entity);

        if (isset($changeSet['price'])) {
            $this->priceManager->recordPriceChange(
                $entity,
                (string)$changeSet['price'][0],
                (string)$changeSet['price'][1]
            );
        }
    }
}
