<?php

declare(strict_types=1);


namespace App\MessageHandler;

use App\Message\ProductPriceChanged;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/**
 *
 */
#[AsMessageHandler]
class ProductPriceChangedHandler
{
    /**
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(private LoggerInterface $logger)
    {
    }

    /**
     * @param \App\Message\ProductPriceChanged $event
     * @return void
     */
    public function __invoke(ProductPriceChanged $event): void
    {
        $this->logger->info(
            sprintf(
                'Price changed for Product #%d: %.2f -> %.2f',
                $event->productId,
                $event->oldPrice,
                $event->newPrice
            )
        );
    }
}
