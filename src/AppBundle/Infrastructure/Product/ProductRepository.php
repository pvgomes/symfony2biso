<?php

namespace AppBundle\Infrastructure\Product;

use Doctrine\ORM\Tools\Pagination\Paginator;
use AppBundle\Infrastructure\Core\EntityRepository;
use AppBundle\Infrastructure\Core;
use \Domain;

class ProductRepository extends EntityRepository implements Domain\Product\ProductRepository
{

    private $entityPath = 'AppBundle\Infrastructure\Product\Product';

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
    public function add(Domain\Product\Product $product)
    {
        $this->getEntityManager()->persist($product);
        $this->getEntityManager()->flush($product);
    }

    public function listByMarket(Domain\Core\Market $market, $firstResult = 0, $maxResult = 20, $filter = [])
    {
        $searchParam = (isset($filter['search'])) ? "AND (p.sku LIKE :search OR p.name LIKE :search) " : '';
        $category = ($filter['category']) ? "AND c.nameKey = :category" : '';

        $dql = <<<DQL
                SELECT p
                FROM AppBundle\Infrastructure\Product\Product p
                INNER JOIN AppBundle\Infrastructure\Product\Category c WITH p.category = c.id
                WHERE p.market = :market
                {$searchParam} {$category}
DQL;


        $query = $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter('market', $market)
            ->setFirstResult($firstResult)
            ->setMaxResults($maxResult);

        if ($filter['search']) $query->setParameter("search", "%{$filter['search']}%");
        if ($filter['category']) $query->setParameter('category', $filter['category']);

        return new Paginator($query, false);
    }

    public function bySkuAndMarket($sku, Domain\Core\Market $market)
    {
        return $this->getRepository()
            ->findOneBy(['sku' => $sku, 'market' => $market]);
    }

    public function activeExternalProducts(array $skus, Domain\Core\Market $market, Domain\Core\Seller $seller)
    {
        // TODO: Implement activeExternalProducts() method.
    }

    public function bySkuAndMarketId($sku, $marketId)
    {
        // TODO: Implement bySkuAndMarketId() method.
    }


}
