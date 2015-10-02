<?php

namespace AppBundle\Infrastructure\Order;

use AppBundle\Infrastructure;
use AppBundle\Infrastructure\Core\EntityRepository;

class ItemStatusRepository extends EntityRepository
{

    private $entityPath = 'AppBundle\Infrastructure\Order\ItemStatus';

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
    public function changeStateMarket(Infrastructure\Order\Item $item)
    {
        $status = $this->getStatus($item->getStateMarket());
        $item->setStatusMarket($status);

        $this->getEntityManager()->persist($item);
        $this->getEntityManager()->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function changeStateSeller(Infrastructure\Order\Item $item)
    {
        $status = $this->getStatus($item->getStateSeller());
        $item->setStatusSeller($status);

        $this->getEntityManager()->persist($item);
        $this->getEntityManager()->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function setStatusHistory(Infrastructure\Order\Item $item, $eventName)
    {
        $statusHistory = new Infrastructure\Order\ItemStatusHistory();
        $statusHistory->setItem($item);
        $statusHistory->setStatusSeller($item->getStatusSeller());
        $statusHistory->setStatusMarket($item->getStatusMarket());
        $statusHistory->setEventName($eventName);

        $this->getEntityManager()->persist($statusHistory);
        $this->getEntityManager()->flush();
    }

    /**
     * @param $statusName
     * @return \AppBundle\Infrastructure\Order\ItemStatus
     */
    private function getStatus($statusName)
    {
        $repository = $this->getRepository();
        $status = $repository->findOneByKeyName($statusName);

        return $status;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusMarketCreateOrder()
    {
        return $this->getStatus(Infrastructure\Order\ItemStatus::STATUS_PARTNER_CREATE_ORDER);
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusSellerCreateWaiting()
    {
        return $this->getStatus(Infrastructure\Order\ItemStatus::STATUS_VENTURE_CREATE_WAITING);
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusSellerCreateOrder()
    {
        return $this->getStatus(Infrastructure\Order\ItemStatus::STATUS_VENTURE_CREATE_ORDER);
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusMarketConfirmOrder()
    {
        return $this->getStatus(Infrastructure\Order\ItemStatus::STATUS_PARTNER_CONFIRM_ORDER);
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusSellerConfirmOrder()
    {
        return $this->getStatus(Infrastructure\Order\ItemStatus::STATUS_VENTURE_CONFIRM_ORDER);
    }
}
