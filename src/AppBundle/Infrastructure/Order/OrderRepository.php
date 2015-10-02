<?php

namespace AppBundle\Infrastructure\Order;

use Doctrine\ORM\Tools\Pagination\Paginator;
use AppBundle\Infrastructure\Core\EntityRepository;
use AppBundle\Infrastructure\Core;

class OrderRepository extends EntityRepository
{
    private $entityPath = 'AppBundle\Order\Order';

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
    public function add(Order $order)
    {
        $this->getEntityManager()->persist($order);
        $this->getEntityManager()->flush();

        $orderId = $order->getId();
        $order->generateOrderNumber($orderId);

        $this->getEntityManager()->persist($order);
        $this->getEntityManager()->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getByMarketOrderNumber($marketOrderNumber, Core\Market $market)
    {
        $order = $this->getRepository()
            ->findOneBy(['marketOrderNumber' => $marketOrderNumber, 'market' => $market]);

        return $order;
    }

    /**
     * {@inheritdoc}
     */
    public function getBySellerAndMarketWithMarketOrderNumber(Core\Seller $seller, Core\Market $market, $marketOrderNumber)
    {
        $order = $this->getRepository()
            ->findOneBy([
                'seller' => $seller,
                'market' => $market,
                'marketOrderNumber' => $marketOrderNumber,
            ]);

        return $order;
    }

    /**
     * {@inheritdoc}
     */
    public function getBySellerAndMarketWithSellerOrderNumber(Core\Seller $seller, Core\Market $market, $sellerOrderNumber)
    {
        $order = $this->getRepository()
            ->findOneBy([
                'seller' => $seller,
                'market' => $market,
                'sellerOrderNumber' => $sellerOrderNumber,
            ]);

        return $order;
    }

    /**
     * {@inheritdoc}
     */
    public function getBySellerAndMarketWithExternalShopOrderNumber(Core\Seller $seller, Core\Market $market, $externalShopOrderNumber)
    {
        $order = $this->getRepository()
            ->findOneBy([
                'seller' => $seller,
                'market' => $market,
                'orderNumber' => $externalShopOrderNumber,
            ]);

        return $order;
    }

    /**
     * {@inheritdoc}
     */
    public function getByOrderNumber($OrderNumber)
    {
        $repository = $this->getRepository();
        return $repository->findOneByOrderNumber($OrderNumber);
    }

    /**
     * {@inheritdoc}
     */
    public function getByOrderItemId($orderItemId, Core\Seller $seller, Core\Market $market)
    {
        $dql = <<<DQL
        SELECT so FROM AppBundle\Order\Order so
        JOIN so.items soi
        WHERE soi.id = :id
        AND so.seller = :seller
        AND so.market = :market
DQL;
        $query = $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter('seller', $seller)
            ->setParameter('market', $market)
            ->setParameter('id', $orderItemId);

        return $query->getOneOrNullResult();
    }

    /**
     * {@inheritdoc}
     */
    public function getByMarketOrderItemId($marketOrderItemId, Core\Seller $seller, Core\Market $market)
    {
        $dql = <<<DQL
        SELECT so FROM AppBundle\Order\Order so
        JOIN so.items soi
        WHERE soi.marketId = :id
        AND so.seller = :seller
        AND so.market = :market
DQL;
        $query = $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter('seller', $seller)
            ->setParameter('market', $market)
            ->setParameter('id', $marketOrderItemId);

        return $query->getOneOrNullResult();
    }

    /**
     * {@inheritdoc}
     */
    public function getBySellerOrderNumber($sellerOrderNumber, Core\Seller $seller)
    {
        $order = $this->getRepository()
            ->findOneBy([
                'seller' => $seller,
                'sellerOrderNumber' => $sellerOrderNumber,
            ]);

        return $order;
    }

    /**
     * {@inheritdoc}
     */
    public function paginateBySeller(Core\Seller $seller, $firstResult = 0, $maxResult = 20, $filter = [])
    {
        $search  = ($filter['search'])
                 ? "AND (o.orderNumber = :search OR o.marketOrderNumber = :search OR o.sellerOrderNumber = :search OR o.id = :search)"
                 : '';
        $date    = ($filter['dateStart'] && $filter['dateEnd']) ? 'AND (o.createdAt BETWEEN :dateStart AND :dateEnd)' : '';

        $dql = <<<DQL
                SELECT o
                FROM AppBundle\Order\Order o
                WHERE o.seller = :seller {$search} {$date}
                ORDER BY o.createdAt DESC
DQL;

        $query = $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter('seller', $seller)
            ->setFirstResult($firstResult)
            ->setMaxResults($maxResult);

        if ($filter['search']) {
            $query->setParameter('search', $filter['search']);
        }

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
    public function getBy(array $where)
    {
        $orders = $this->getRepository()->findBy($where);
        return $orders;
    }

    public function getByDate(Core\Seller $seller, array $where)
    {

        $date = ($where['dateStart'] && $where['dateEnd']) ? 'AND (o.createdAt BETWEEN :dateStart AND :dateEnd)' : '';

        $dql = <<<DQL
                SELECT o
                FROM AppBundle\Order\Order o
                WHERE o.seller = :seller {$date}
                ORDER BY o.createdAt DESC
DQL;

        $query = $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter('seller', $seller);

        if ($where['dateStart'] && $where['dateEnd']) {
            $query->setParameter('dateStart', "{$where['dateStart']} 00:00:00");
            $query->setParameter('dateEnd', "{$where['dateEnd']} 23:59:59");
        }

        $orders = $query->getResult();
        return $orders;
    }

    public function getAllBySellerWithFilter(Core\Seller $seller, $filter = [])
    {

        $search  = ($filter['search'])
            ? "AND (o.orderNumber = :search OR o.marketOrderNumber = :search OR o.sellerOrderNumber = :search OR o.id = :search)"
            : '';
        $date    = ($filter['dateStart'] && $filter['dateEnd']) ? 'AND (o.createdAt BETWEEN :dateStart AND :dateEnd)' : '';

        $dql = <<<DQL
            SELECT o
            FROM AppBundle\Order\Order o
            WHERE o.seller = :seller {$search} {$date}
            ORDER BY o.createdAt DESC
DQL;

        $query = $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter('seller', $seller);

        if ($filter['search']) {
            $query->setParameter('search', $filter['search']);
        }

        if ($filter['dateStart'] && $filter['dateEnd']) {
            $query->setParameter('dateStart', "{$filter['dateStart']} 00:00:00");
            $query->setParameter('dateEnd', "{$filter['dateEnd']} 23:59:59");
        }

        return $query->getResult();
    }
}
