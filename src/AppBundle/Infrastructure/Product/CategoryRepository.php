<?php

namespace AppBundle\Infrastructure\Product;

use Doctrine\ORM\Tools\Pagination\Paginator;
use AppBundle\Infrastructure\Core\EntityRepository;
use AppBundle\Infrastructure;
use \Domain;

class CategoryRepository extends EntityRepository implements Domain\Product\CategoryRepository
{
    private $entityPath = 'Domain\Order\Category';

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
    public function add(Domain\Product\Category $category)
    {
        $this->getEntityManager()->persist($category);
        $this->getEntityManager()->flush();
        return $category;
    }


    /**
     * {@inheritdoc}
     */
    public function listByMarket(Domain\Core\Market $market, $firstResult = 0, $maxResult = 20, $filter = [])
    {
        $dql = <<<DQL
                SELECT c
                FROM AppBundle\Infrastructure\Product\Category c
                WHERE c.market = :market
DQL;

        $query = $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter('market', $market)
            ->setFirstResult($firstResult)
            ->setMaxResults($maxResult);

        return new Paginator($query, false);
    }
}
