<?php

namespace AppBundle\Infrastructure\Order;

use AppBundle\Infrastructure\Core\EntityRepository;

class ItemRepository extends EntityRepository
{
    private $entityPath = 'AppBundle\Infrastructure\Order\Item';

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
    public function add(Item $item)
    {
        $this->getEntityManager()->persist($item);
        $this->getEntityManager()->flush();
    }


}
