<?php

namespace AppBundle\Infrastructure\Core;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

use \Domain as Domain;

class SellerDataMapper implements Domain\Core\DataMapper
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

    public function assign($sellerInfrastructure)
    {
        if (!$sellerInfrastructure instanceof Seller) {
            throw new \DomainException("Invalid Market");
        }
        $sellerDomain = new Domain\Core\Seller();
        $sellerDomain->setName($sellerInfrastructure->getName());


        return $sellerDomain;
    }

    public function assignFull($sellerInfrastructure)
    {
        return $this->assign($sellerInfrastructure);
    }

    public function map($sellerDomain)
    {
        if (!$sellerDomain instanceof Domain\Core\Seller) {
            throw new \DomainException("Invalid Seller");
        }
        $sellerInfrastructure = new Seller();
        $sellerInfrastructure->setId($sellerDomain->getId());
        $sellerInfrastructure->setName($sellerDomain->getName());
        $sellerInfrastructure->setKeyName($sellerDomain->getKeyName());

        return $sellerInfrastructure;
    }

}