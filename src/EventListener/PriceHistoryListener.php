<?php

namespace App\EventListener;

use App\Entity\PriceHistory;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Events;

/**
 *
 */
#[AsDoctrineListener(event: Events::postUpdate, connection: 'default')]
class PriceHistoryListener
{
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

        $em = $args->getObjectManager();
        $changeSet = $em->getUnitOfWork()->getEntityChangeSet($entity);

        if (!isset($changeSet['price'])) {
            return;
        }

        $history = new PriceHistory();
        $history->setProduct($entity);
        $history->setOldPrice((string)$changeSet['price'][0]);
        $history->setNewPrice((string)$changeSet['price'][1]);

        $em->persist($history);
        $em->flush();
    }
}
