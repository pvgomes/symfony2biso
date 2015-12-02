<?php

namespace AppBundle\Infrastructure\Core;

use AppBundle\Infrastructure;
use \Domain as Domain;

class MarketRepository extends EntityRepository implements Domain\Core\MarketRepository
{
    private $entityPath = 'AppBundle\Infrastructure\Core\Market';

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

    /**
     * {@inheritdoc}
     */
    public function byKeyName($keyName)
    {
        $repository = $this->getRepository();
        $market = $repository->findOneByKeyName($keyName);

        return $market;
    }

    /**
     * {@inheritdoc}
     */
    public function byKeyNameAndToken($keyName, $token)
    {
        return $this->getRepository()->findOneBy(['keyName' => $keyName, 'accessToken' => $token]);
    }

    /**
     * {@inheritdoc}
     */
    public function add(Domain\Core\Market $market)
    {
        $this->getEntityManager()->persist($market);
        $this->getEntityManager()->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityPath()
    {
        return $this->entityPath;
    }
}
