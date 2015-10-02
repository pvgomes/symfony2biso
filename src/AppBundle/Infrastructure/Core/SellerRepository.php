<?php

namespace AppBundle\Infrastructure\Core;

use AppBundle\Infrastructure;

class SellerRepository extends EntityRepository
{
    private $entityPath = 'AppBundle\Infrastructure\Core\Seller';

    /**
     * {@inheritdoc}
     */
    public function getByKeyName($keyName)
    {
        $repository = $this->getRepository();
        $seller = $repository->findOneByKeyName($keyName);

        return $seller;
    }

    public function add(Infrastructure\Core\Seller $seller)
    {
        $this->getEntityManager()->persist($seller);
        $this->getEntityManager()->flush($seller);
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityPath()
    {
        return $this->entityPath;
    }
}
