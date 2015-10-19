<?php

namespace AppBundle\Infrastructure\Core;

use AppBundle\Infrastructure;


class MarketRepository extends EntityRepository implements \Domain\Core\MarketRepository
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
    public function getByKeyName($keyName)
    {
        $repository = $this->getRepository();
        $market = $repository->findOneByKeyName($keyName);

        return $market;
    }

    public function add(\Domain\Core\Market $market)
    {
        $market =
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
