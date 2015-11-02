<?php

namespace Application\Product;

class Service
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function create($productRequestData)
    {
        $market = $this->container->getMarket();
        $importer = $market->getImporter();
        if (!$importer instanceof \Domain\Product\Importer) {
            throw new \DomainException('Invalid importer');
        }
        $productFactory = $this->container->getFactory('product');
        $productEntity = $productFactory->createStructure($productRequestData);
        $importer->import($productEntity);
    }
}