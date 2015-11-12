<?php

namespace AppBundle\Infrastructure\Core;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

use \Domain as Domain;

class ConfigurationDataMapper implements Domain\Core\DataMapper
{

    private static $instance = null;

    private function __construct()
    {

    }

    private function __clone()
    {

    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    public function assign($configurationInfrastructure)
    {
        if (!$configurationInfrastructure instanceof Configuration) {
            throw new \DomainException("Invalid Market");
        }
        $configurationDomain = new Domain\Core\Configuration();
        $configurationDomain->setKey($configurationInfrastructure->getKey());
        $configurationDomain->setValue($configurationInfrastructure->getValue());


        return $configurationDomain;
    }

    public function assignFull($configurationInfrastructure)
    {
        $configurationDomain = $this->assign($configurationInfrastructure);
        $configurationDomain->setMarket(MarketDataMapper::getInstance()->assign($configurationInfrastructure->getMarket()));

        return $configurationDomain;
    }

    public function map($marketDomain)
    {
        if (!$marketDomain instanceof Domain\Core\Market) {
            throw new \DomainException("Invalid Market");
        }
        $marketInfrastructure = new Market();
        $marketInfrastructure->setId($marketDomain->getId());
        $marketInfrastructure->setName($marketDomain->getName());
        $marketInfrastructure->setKeyName($marketDomain->getKeyName());

        return $marketInfrastructure;
    }

}