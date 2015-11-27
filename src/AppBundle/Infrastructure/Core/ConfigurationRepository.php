<?php

namespace AppBundle\Infrastructure\Core;

use Domain;

/**
 * Class ConfigurationRepository
 */
class ConfigurationRepository extends EntityRepository implements Domain\Core\ConfigurationRepository
{

    private $entityPath = 'AppBundle\Infrastructure\Core\Configuration';

    /**
     * {@inheritdoc}
     */
    public function getByKey($key)
    {
        $repository = $this->getRepository();
        return  $repository->findOneBy(['status' => Domain\Core\Configuration::STATUS_ACTIVE, 'key' => $key]);
    }

    public function add(Domain\Core\Configuration $configuration)
    {
        $existentConfiguration = $this->getByKey($configuration->getKey());
        if ($existentConfiguration instanceof Domain\Core\Configuration) {
            $this->inactive($existentConfiguration);
        }

        $this->getEntityManager()->persist($configuration);
        $this->getEntityManager()->flush($configuration);
    }

    public function inactive(Domain\Core\Configuration $configuration)
    {
        $configuration->setStatus(Domain\Core\Configuration::STATUS_INACTIVE);
        $this->getEntityManager()->persist($configuration);
        $this->getEntityManager()->flush($configuration);
    }


    public function getByMarket(Domain\Core\Market $venture)
    {
        $repository = $this->getRepository();
        return  $repository->findBy(['status' => Domain\Core\Configuration::STATUS_ACTIVE, 'market' => $venture], ['id' => 'desc']);
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityPath()
    {
        return $this->entityPath;
    }

}
