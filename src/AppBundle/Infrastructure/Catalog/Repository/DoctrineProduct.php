<?php

namespace AppBundle\Infrastructure\Catalog\Repository;

use Doctrine\ORM\EntityManager;
use \Domain;

class DoctrineProduct implements Domain\Repository\Product
{

    private $productClass = 'AppBundle\Infrastructure\Catalog\Entity\Product';

    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Get Product By Id
     * @param $id
     * @return \Domain\Catalog\Entity\Product | null
     */
    public function getById($id)
    {
        $domainProduct = null;
        $productDoctrine = $this->em->getRepository($this->productClass)->find($id);
        if ($productDoctrine instanceof \AppBundle\Infrastructure\Catalog\Entity\Product) {
            $domainProduct = Domain\Factory\Product::build($productDoctrine->toArray());
        }
        return $domainProduct;
    }

} 