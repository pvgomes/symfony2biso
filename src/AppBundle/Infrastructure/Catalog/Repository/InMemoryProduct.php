<?php

namespace AppBundle\Infrastructure\Catalog\Repository;

use AppBundle\Infrastructure;
use \Domain;

class InMemoryProduct
{

    private $products;

    public function __construct()
    {
        $this->loadMemoryProducts();
    }

    public function getById($id)
    {
        $domainProduct = null;
        if (array_key_exists($id, $this->products)) {
            $product = $this->products[$id];
            $domainProduct = Domain\Factory\Product::build($product->toArray());
        }

        return $domainProduct;
    }

    protected function loadMemoryProducts()
    {
        $product = new Infrastructure\Catalog\Entity\Product();
        $product->setId(1);
        $product->setQuantity(5);
        $product->setName('PLAYSTATION 4');
        $product->setDescription('500GB SONY');
        $product->setCurrency('BRL');
        $product->setOriginalPrice('2400,00');
        $product->setSpecialPrice('2100,00');
        $product->setEan(4245245);
        $product->setSize('26');
        $product->setWidth('31');
        $product->setWeight('4');
        $this->products[$product->getId()] = $product;

        $product->setId(2);
        $product->setQuantity(5);
        $product->setName('XBOX ONE');
        $product->setDescription('500GB MICROSOFT');
        $product->setCurrency('BRL');
        $product->setOriginalPrice('2000,00');
        $product->setSpecialPrice('1850,00');
        $product->setEan(424528542);
        $product->setSize('26');
        $product->setWidth('31');
        $product->setWeight('5');
        $this->products[$product->getId()] = $product;
    }


} 