<?php


namespace AppBundle\Application\Core;

use AppBundle\Infrastructure\Core\Market;
use AppBundle\Infrastructure\Core\MarketDataMapper;
use Domain;

class CreateConfigurationCommand implements Domain\Command
{

    /**
     * @var \Domain\Core\Configuration
     */
    private $configuration;

    /**
     * @param Market $market
     * @param $key
     * @param $value
     */
    public function __construct(Market $market, $key, $value)
    {
        $this->configuration = new Domain\Core\Configuration();
        $this->configuration->setKey($key);
        $this->configuration->setValue($value);
        $this->configuration->setMarket(MarketDataMapper::getInstance()->assign($market));
    }

    public function domainEntity()
    {
        return $this->configuration;
    }

    public function resourceName()
    {
        return 'configuration';
    }

}