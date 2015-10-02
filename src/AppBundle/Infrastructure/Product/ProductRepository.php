<?php

namespace AppBundle\Infrastructure\Product;

use Doctrine\ORM\Tools\Pagination\Paginator;
use AppBundle\Infrastructure\Core\EntityRepository;
use AppBundle\Infrastructure\Core;

class ProductRepository extends EntityRepository
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
    public function add(Product $product)
    {
        $this->getEntityManager()->persist($product);
        $this->getEntityManager()->flush($product);
    }

    public function paginateBySeller(Core\Seller $seller, $firstResult = 0, $maxResult = 20, $filter = null)
    {
        $searchParam = (isset($filter['search'])) ? "AND (p.sku LIKE :search OR p.name LIKE :search) " : '';
        $date = ($filter['dateStart'] && $filter['dateEnd']) ? "AND p.createdAt BETWEEN :dateStart AND :dateEnd" : '';
        $category = ($filter['category']) ? "AND c.nameKey = :category" : '';

        $dql = "SELECT p
                FROM AppBundle\Infrastructure\Product\Product p
                INNER JOIN AppBundle\Infrastructure\Product\Category c WITH p.category = c.id
                WHERE p.seller = :seller
                {$searchParam} {$date} {$category}
                ORDER BY p.createdAt DESC";

        $query = $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter('seller', $seller)
            ->setFirstResult($firstResult)
            ->setMaxResults($maxResult);

        if ($filter['search']) $query->setParameter("search", "%{$filter['search']}%");
        if ($filter['category']) $query->setParameter('category', $filter['category']);
        if ($filter['dateStart'] && $filter['dateEnd']) {
            $query->setParameter('dateStart', "{$filter['dateStart']} 00:00:00");
            $query->setParameter('dateEnd', "{$filter['dateEnd']} 23:59:59");
        }

        $paginator = new Paginator($query, false);

        return $paginator;
    }

    /**
     * {@inheritdoc}
     */
    public function getBySkuAndSeller($sku, Core\Seller $seller)
    {
        $product = $this->getRepository()
            ->findOneBy(['sku' => $sku, 'seller' => $seller]);

        return $product;
    }

    /**
     * {@inheritdoc}
     */
    public function getBySkuAndSellerId($sku, $sellerId)
    {
        $query = $this->getEntityManager()->createQuery('
           SELECT p
           FROM AppBundle\Infrastructure\Product\Product p
           WHERE p.sku = :sku
           AND IDENTITY(p.seller) = :sellerId
       ')->setParameters([
            'sku' => $sku,
            'sellerId' => $sellerId,
        ]);

        $product = $query->getOneOrNullResult();

        return $product;
    }

    public function getBySkuCollectionAndSeller($skuCollection, $seller)
    {
        $products = $this->getRepository()
            ->findBy(['sku' => $skuCollection, 'seller' => $seller]);

        return $products;
    }

    /**
     * {@inheritdoc}
     */
    public function getActiveExternalProducts(array $skus, Core\Seller $seller, Core\Market $market)
    {
        $result = [];
        $products = $this->getBySkuCollectionAndSeller($skus, $seller);

        foreach ($products as $product) {
            $externalProduct = $product->getExternalProductFromMarket($market);
            if (ExternalProduct::STATUS_ACTIVE == $externalProduct->getStatus()) {
                $result[] = $externalProduct;
            }
        }

        return $result;
    }


    public function getAllBySellerWithFilter(Core\Seller $seller, $filter = [])
    {
        $searchParam = (isset($filter['search'])) ? "AND (p.sku LIKE :search OR p.name LIKE :search) " : '';
        $date = ($filter['dateStart'] && $filter['dateEnd']) ? "AND p.createdAt BETWEEN :dateStart AND :dateEnd" : '';
        $category = ($filter['category']) ? "AND c.nameKey = :category" : '';

        $dql = "SELECT p
                FROM AppBundle\Infrastructure\Product\Product p
                INNER JOIN AppBundle\Infrastructure\Product\Category c WITH p.category = c.id
                WHERE p.seller = :seller
                {$searchParam} {$date} {$category}
                ORDER BY p.createdAt DESC";

        $query = $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter('seller', $seller);

        if ($filter['search']) $query->setParameter("search", "%{$filter['search']}%");
        if ($filter['category']) $query->setParameter('category', $filter['category']);
        if ($filter['dateStart'] && $filter['dateEnd']) {
            $query->setParameter('dateStart', "{$filter['dateStart']} 00:00:00");
            $query->setParameter('dateEnd', "{$filter['dateEnd']} 23:59:59");
        }

        return $query->getResult();
    }


    public function getProductByExternalProductStatus(Core\Seller $seller, $status = ExternalProduct::STATUS_ACTIVE)
    {
        $dql = <<<DQL
        SELECT p FROM AppBundle\Infrastructure\Product\Product p
        JOIN p.externalProducts ep
        WHERE ep.status = :status
        AND p.seller = :seller
DQL;
        $query = $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter('seller', $seller)
            ->setParameter('status', $status);

        return $query->getResult();
    }


}
