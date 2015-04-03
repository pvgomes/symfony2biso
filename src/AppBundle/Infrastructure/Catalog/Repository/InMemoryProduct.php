<?php

namespace AppBundle\Infrastructure\Catalog\Repository;

use AppBundle\Infrastructure;
use Domain\Repository\Product as ProductRepository;

class InMemoryProduct implements ProductRepository{

    private $products;

    public function __construct()
    {
        $this->products[1] = new Infrastructure\Catalog\Entity\Product(1, "Barbie Dican", "MA048AP14WXLTRI-296312", "ABCDE-2014");
        $this->products[2] = new Infrastructure\Catalog\Entity\Product(2, "He-man Dican", "MA048AP14WXLTRI-296312", "ABCDE-2015");
    }

    public function getById($id)
    {
        //$this->products[$sku]
    }

} 