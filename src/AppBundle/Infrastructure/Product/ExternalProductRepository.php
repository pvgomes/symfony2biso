<?php

namespace AppBundle\Infrastructure\Product;

use AppBundle\Infrastructure\Core\EntityRepository;
use AppBundle\Infrastructure\Core\Market;


class ExternalProductRepository extends EntityRepository
{
    private $entityPath = 'AppBundle\Infrastructure\Product\ExternalProduct';

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
    public function add(ExternalProduct $externalProduct)
    {
        $this->getEntityManager()->persist($externalProduct);
        $this->getEntityManager()->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        $repository = $this->getRepository();
        return $repository->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getAll()
    {
        $repository = $this->getRepository()
            ->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery();

        return $repository->getResult();
    }

    public function getByProductIdAndMarketId($productId, $marketId)
    {
        $query = $this->getEntityManager()->createQuery('
           SELECT e
           FROM AppBundle\Infrastructure\Product\ExternalProduct e
           WHERE IDENTITY(e.product) = :productId
           AND IDENTITY(e.market) = :marketId
       ')->setParameters([
            'productId' => $productId,
            'marketId' => $marketId,
        ]);

        $externalProduct = $query->getOneOrNullResult();

        return $externalProduct;
    }

    /**
     * Fetchs a ExternalProduct related to only one Product and Market.
     *
     * @param Product $product
     * @param Market $market
     * @return ExternalProduct
     */
    public function getByProductAndMarket(Product $product, Market $market)
    {
        $externalProduct = $this->getRepository()
            ->findOneBy(['product' => $product, 'market' => $market]);

        return $externalProduct;
    }


    public function getByProductId($productId)
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
