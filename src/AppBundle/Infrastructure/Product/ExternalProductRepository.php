<?php

namespace AppBundle\Infrastructure\Product;

use AppBundle\Infrastructure\Core\EntityRepository;
use AppBundle\Infrastructure\Core\Market;
use \Domain;


class ExternalProductRepository extends EntityRepository implements Domain\Product\ExternalProductRepository
{
    private $entityPath = 'Domain\Product\ExternalProduct';

    /**
     * {@inheritdoc}
     */
    public function getEntityPath()
    {
        return $this->entityPath;
    }

    /**
     * {@inheritdoc}
     */
    public function add(Domain\Product\ExternalProduct $externalProduct)
    {
        $this->getEntityManager()->persist($externalProduct);
        $this->getEntityManager()->flush();
    }


    /**
     * {@inheritdoc}
     */
    public function byProductAndMarket(Domain\Product\Product $product, Domain\Core\Market $market)
    {
        $externalProduct = $this->getRepository()
            ->findOneBy(['product' => $product, 'market' => $market]);

        return $externalProduct;
    }


    /**
     * {@inheritdoc}
     */
    public function byProductId($productId)
    {
        $query = $this
            ->getEntityManager()
            ->createQuery('
               SELECT e
               FROM AppBundle\Infrastructure\Product\ExternalProduct e
               WHERE IDENTITY(e.product) = :productId')
            ->setParameter('productId', $productId);

        $externalProduct = $query->getResult();

        return $externalProduct;
    }
}
