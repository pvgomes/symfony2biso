<?php

namespace AppBundle\Infrastructure\Product;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

use AppBundle\Infrastructure\Core\EntityRepository;
use AppBundle\Infrastructure\Core;

class LoadProductRepository extends EntityRepository
{
    private $entityPath = 'AppBundle\LoadProduct';

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
    public function add(LoadProduct $loadProduct)
    {
        $this->getEntityManager()->persist($loadProduct);
        $this->getEntityManager()->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function addReport(LoadProduct $loadProduct, $sku, $message, Core\Market $market = null, $type)
    {
        $loadProductReport = new LoadProductReport();

        $loadProductReport->setLoadProduct($loadProduct);
        $loadProductReport->setSku($sku);
        $loadProductReport->setMessage($message);
        if ($market) {
            $loadProductReport->setMarket($market);
        }
        $loadProductReport->setType($type);

        $this->getEntityManager()->persist($loadProductReport);
        $this->getEntityManager()->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function addSuccess(LoadProduct $loadProduct, $sku, $message, Core\Market $market = null)
    {
        $type = LoadProductReport::SUCCESS;
        $this->addReport($loadProduct, $sku, $message, $market, $type);
    }

    /**
     * {@inheritdoc}
     */
    public function addErrors(LoadProduct $loadProduct, $sku, $message, Core\Market $market = null)
    {
        $type = LoadProductReport::ERROR;
        $this->addReport($loadProduct, $sku, $message, $market, $type);
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
    public function getByUser(UserInterface $user)
    {
        $loads = $this->getRepository()
            ->findByUser($user, ['createdAt' => 'DESC']);

        return $loads;
    }

    /**
     * {@inheritdoc}
     */
    public function getLoadReportCount($loadId)
    {
        $result = [
            'errors' => 0,
            'success' => 0,
            'valid' => false,
        ];

        try {
            $error = LoadProductReport::ERROR;
            $success = LoadProductReport::SUCCESS;

            $dql = "SELECT count(r.id)
                FROM AppBundle\LoadProductReport r
                WHERE IDENTITY(r.loadProduct) = :loadId
                AND r.type = :type
            ";

            $queryError = $this->getEntityManager()
                ->createQuery($dql)
                ->setParameters([
                    'loadId' => $loadId,
                    'type' => $error,
                ]);

            $querySuccess = $this->getEntityManager()
                ->createQuery($dql)
                ->setParameters([
                    'loadId' => $loadId,
                    'type' => $success,
                ]);


            $result['errors'] = $queryError->getSingleScalarResult();
            $result['success'] = $querySuccess->getSingleScalarResult();
            $result['valid'] = true;

            return $result;

        } catch (\Exception $exception) {
            return $result;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function paginateByUser(UserInterface $user, $firstResult = 0, $maxResult = 10)
    {
        $dql = "SELECT l
                FROM AppBundle\Infrastructure\Product\LoadProduct l
                WHERE l.user = :user
                ORDER BY l.createdAt DESC";

        $query = $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter('user', $user)
            ->setFirstResult($firstResult)
            ->setMaxResults($maxResult);

        $paginator = new Paginator($query, false);

        return $paginator;
    }
}
