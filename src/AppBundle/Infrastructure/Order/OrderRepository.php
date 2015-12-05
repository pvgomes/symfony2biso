<?php

namespace AppBundle\Infrastructure\Order;

use Doctrine\ORM\Tools\Pagination\Paginator;
use AppBundle\Infrastructure\Core\EntityRepository;
use \Domain;

class OrderRepository extends EntityRepository implements Domain\Order\OrderRepository
{
    private $entityPath = 'AppBundle\Infrastructure\Order\Order';

    /**
     * {@inheritdoc}
     */
    public function getEntityPath()
    {
        return $this->entityPath;
    }

    public function add(Domain\Order\Order $order)
    {
        $this->getEntityManager()->persist($order);
        $this->getEntityManager()->flush();

        $orderId = $order->getId();
        $order->generateOrderNumber($orderId);

        $this->getEntityManager()->persist($order);
        $this->getEntityManager()->flush();
    }

    public function getByMarketOrderNumber($marketOrderNumber, Domain\Core\Market $market)
    {
        $order = $this->getRepository()
            ->findOneBy(['marketOrderNumber' => $marketOrderNumber, 'market' => $market]);

        return $order;
    }

    public function getBySellerOrderNumber($sellerOrderNumber, Domain\Core\Seller $seller)
    {
        $order = $this->getRepository()
            ->findOneBy(['sellerOrderNumber' => $sellerOrderNumber, 'seller' => $seller]);

        return $order;
    }

    public function listByMarket(Domain\Core\Market $market, $firstResult = 0, $maxResult = 20, $filter = [])
    {
        $search  = ($filter['search'])
            ? "AND (o.orderNumber = :search OR o.sellerOrderNumber = :search OR o.marketOrderNumber = :search OR o.id = :search)"
            : '';

        $dql = <<<DQL
                SELECT o
                FROM AppBundle\Infrastructure\Order\Order o
                WHERE o.market = :market {$search}
DQL;

        $query = $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter('market', $market)
            ->setFirstResult($firstResult)
            ->setMaxResults($maxResult);

        if ($filter['search']) {
            $query->setParameter('search', $filter['search']);
        }

        return new Paginator($query, false);
    }


}
