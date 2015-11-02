<?php

namespace Application\Product;

class QueueImporter implements Domain\Importer
{
    private $queueAdapter;

    public function __construct(QueueAdapter $queueAdapter)
    {
        $this->queueAdapter = $queueAdapter;
    }

    public function import(\Domain\Product\Product $product)
    {
        $exchange = $this->queueAdapter->getExchange('product.import');
        $message = $this->queueAdapter->getMessage($product->toArray());
        $exchange->publish($message);
    }
}