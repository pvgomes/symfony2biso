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
        $productFactory = $this->container->getFactory('product');
        $productEntity = $productFactory->createStructure($productRequestData);
        $dataBase = $this->container->getFactory('mysql');
        $dataBase->insert($productEntity->toArray());
    }
}