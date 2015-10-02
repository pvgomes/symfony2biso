<?php

namespace AppBundle\Infrastructure\Core;

use AppBundle\Infrastructure;

class UserRoleRepository extends EntityRepository
{
    private $entityPath = 'AppBundle\Infrastructure\Core\UserRole';

    public function get($id)
    {
        $repository = $this->getRepository();
        return $repository->find($id);
    }


    public function getAll()
    {
        $repository = $this->getRepository()
            ->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery();

        return $repository->getResult();
    }

    public function getByUserName($userName)
    {
        $repository = $this->getRepository();
        $arrayUsers = $repository->findByUsername($userName);

        return current($arrayUsers);
    }

    public function add(Infrastructure\Core\UserRole $userRole){
        $this->getEntityManager()->persist($userRole);
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
