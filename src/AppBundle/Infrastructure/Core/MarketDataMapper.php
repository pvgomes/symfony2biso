<?php

namespace AppBundle\Infrastructure\Core;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

class MarketDataMapper implements \Domain\Core\DataMapper
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

    }

    public function assignFull($marketInfrastructure)
    {
        return $this->assign($marketInfrastructure);
    }

    public function map($marketDomain)
    {
        return $marketDomain;
    }

}