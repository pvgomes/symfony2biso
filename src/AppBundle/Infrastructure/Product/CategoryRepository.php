<?php

namespace AppBundle\Infrastructure\Product;

use Doctrine\ORM\Tools\Pagination\Paginator;
use AppBundle\Infrastructure\Core\EntityRepository;
use AppBundle\Infrastructure\Product\Category;
use AppBundle\Infrastructure;

class CategoryRepository extends EntityRepository
{
    private $entityPath = 'AppBundle\Infrastructure\Product\Category';

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
    public function getAllBySeller(Infrastructure\Core\Seller $seller)
    {
        $categories = $this->getRepository()
            ->findBySeller($seller, ['name' => 'ASC']);

        return $categories;
    }

    /**
     * {@inheritdoc}
     */
    public function add(Infrastructure\Product\Category $category)
    {
        $this->getEntityManager()->persist($category);
        $this->getEntityManager()->flush();
        return $category;
    }

    /**
     * {@inheritdoc}
     */
    public function getByCategorySellerIdAndSeller($categorySellerId, Infrastructure\Core\Seller $seller)
    {
        $category = $this->getRepository()
            ->findOneBy([
                'categorySellerId' => $categorySellerId,
                'seller' => $seller
            ]);

        return $category;
    }

    /**
     * {@inheritdoc}
     */
    public function getByNameAndSeller($name, Infrastructure\Core\Seller $seller)
    {
        $category = $this->getRepository()
            ->findOneBy(['name' => $name, 'seller' => $seller]);

        return $category;
    }

    /**
     * {@inheritdoc}
     */
    public function getByKeyNameAndSeller($nameKey, Infrastructure\Core\Seller $seller)
    {
        $category = $this->getRepository()
            ->findOneBy(['nameKey' => $nameKey, 'seller' => $seller]);

        return $category;
    }

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

    public function getOrCreate(Category $category)
    {
        $categoryResult = null;

        if ($category->getId()) {
            $categoryResult = $this->get($category->getId());
        } elseif ($category->getNameKey()) {
            $categoryResult = $this
                ->getByKeyNameAndSeller($category->getNameKey(), $category->getSeller());
        } elseif ($category->getName()) {
            $categoryResult = $this
                ->getByNameAndSeller($category->getName(), $category->getSeller());
        } else {
            throw new \InvalidArgumentException("We cant create category");
        }

        if (!$categoryResult && $category->isValid()) {
            $category = $this->add($category);
        } else {
            $category = $categoryResult;
        }

        return $category;
    }


    /**
     * {@inheritdoc}
     */
    public function paginateBySeller(Infrastructure\Core\Seller $seller, $firstResult = 0, $maxResult = 20)
    {
        $dql = "SELECT c
                FROM AppBundle\Infrastructure\Product\Category c
                WHERE c.seller = :seller
                ORDER BY c.createdAt DESC";

        $query = $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter('seller', $seller)
            ->setFirstResult($firstResult)
            ->setMaxResults($maxResult);

        $paginator = new Paginator($query, false);

        return $paginator;
    }
}
