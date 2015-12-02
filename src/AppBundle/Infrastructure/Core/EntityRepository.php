<?php

namespace AppBundle\Infrastructure\Core;

use Doctrine\ORM\EntityRepository as DoctrineEntityRepository;
use AppBundle\Infrastructure;

abstract class EntityRepository extends DoctrineEntityRepository
{
    const HOT_CONNECTION_MODE = 'hot_connection_mode';
    const COLD_CONNECTION_MODE = 'cold_connection_mode';

    /**
     * @var DoctrineEntityRepository
     */
    private $repository;
    /**
     * @var string
     */
    private $connectionMode = self::COLD_CONNECTION_MODE;

    /**
     * @return DoctrineEntityRepository
     */
    public function getRepository()
    {
        if (self::HOT_CONNECTION_MODE === $this->connectionMode) {
            $this->getEntityManager()->clear();
        }

        if (is_null($this->repository)) {
            $this->repository = $this->getEntityManager()->getRepository($this->getEntityPath());
        }

        return $this->repository;
    }

    /**
     * Defines how to fetch data. From cache (default mode = COLD_CONNECTION_MODE)
     * or from database (HOT_CONNECTION_MODE).
     *
     * @param string $mode
     */
    public function setConnectionMode($mode)
    {
        $modes = [
            self::HOT_CONNECTION_MODE,
            self::COLD_CONNECTION_MODE,
        ];

        if (in_array($mode, $modes)) {
            $this->connectionMode = $mode;
        }
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

    /**
     * {@inheritdoc}
     */
    public function getAllByMarket(Infrastructure\Core\Market $market)
    {
        $entities = $this->getRepository()
            ->findByMarket($market);

        return $entities;
    }

    /**
     * Return path to entity,example: AppBundle\Infrastructure\Core\Seller
     * @return string
     */
    public abstract function getEntityPath();
}
