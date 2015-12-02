<?php

namespace AppBundle\Application\Core;

use AppBundle\Infrastructure\Core\Market;
use AppBundle\Infrastructure\Core\Configuration;

use Domain;

class CreateConfigurationCommand implements Domain\Command
{

    /**
     * @var \Domain\Core\Configuration
     */
    private $configuration;

    private $eventName;

    /**
     * @param Market $market
     * @param $key
     * @param $value
     */
    public function __construct(Market $market, $key, $value)
    {
        $this->eventName = Domain\Core\Events::MARKET_CREATE_CONFIGURATION;
        $this->configuration = new Configuration();
        $this->configuration->setKey($key);
        $this->configuration->setValue($value);
        $this->configuration->setMarket($market);
    }

    public function repositories()
    {
        return ['configuration'];
    }

    public function eventName()
    {
        return $this->eventName;
    }

    public function eventNameError()
    {
        return $this->eventName . ".error";
    }

    public function configurationEntity()
    {
        return new Configuration();
    }
}