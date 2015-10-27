<?php

namespace AppBundle\Infrastructure\Core;

use AppBundle\Infrastructure;
use \Domain as Domain;

class UserRepository extends EntityRepository implements Domain\Core\UserRepository
{
    private $entityPath = 'AppBundle\Infrastructure\Core\User';

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

    public function add(Domain\Core\User $user){
        $this->getEntityManager()->persist($user);
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
