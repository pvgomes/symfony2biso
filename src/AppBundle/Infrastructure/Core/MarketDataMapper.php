<?php

namespace AppBundle\Infrastructure\Core;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

use \Domain as Domain;

class MarketDataMapper implements Domain\Core\DataMapper
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

    public function assign($marketInfrastructure)
    {
        if (!$marketInfrastructure instanceof Market) {
            throw new \DomainException("Invalid Market");
        }
        $marketDomain = new Domain\Core\Market();
        $marketDomain->setId($marketInfrastructure->getId());
        $marketDomain->setName($marketInfrastructure->getName());
        $marketDomain->setKeyName($marketInfrastructure->getKeyName());

        return $marketDomain;
    }

    public function assignFull($marketInfrastructure)
    {
        return $this->assign($marketInfrastructure);
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