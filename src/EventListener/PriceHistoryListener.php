<?php

namespace App\EventListener;

use App\Entity\PriceHistory;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Events;
use App\Message\ProductPriceChanged;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 *
 */
#[AsDoctrineListener(event: Events::postUpdate, connection: 'default')]
class PriceHistoryListener
{
    public function __construct(private MessageBusInterface $bus)
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

        $this->bus->dispatch(
            new ProductPriceChanged(
                $history->getProduct()->getId(),
                (float)$history->getOldPrice(),
                (float)$history->getNewPrice()
            )
        );
    }
}
