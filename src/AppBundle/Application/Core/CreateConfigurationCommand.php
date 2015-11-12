<?php


namespace AppBundle\Application\Core;

use AppBundle\Infrastructure\Core\Configuration;
use AppBundle\Infrastructure\Core\ConfigurationDataMapper;
use AppBundle\Infrastructure\Core\Market;
use Domain;

class CreateConfigurationCommand implements Domain\Command
{

    /**
     * @var \AppBundle\Infrastructure\Core\Configuration
     */
    private $configuration;

    /**
     * @param Market $market
     * @param $key
     * @param $value
     */
    public function __construct(Market $market, $key, $value)
    {
        $this->configuration = new Configuration();
        $this->configuration->setKey($key);
        $this->configuration->setValue($value);
        $this->configuration->setMarket($market);
    }

    public function getDomainEntity()
    {
        return ConfigurationDataMapper::getInstance()->assignFull($this->configuration);
    }

}