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

    /**
     * @var array
     */
    public $data;

    private $eventName;

    /**
     * @param $marketKey
     * @param $key
     * @param $value
     */
    public function __construct($marketKey, $key, $value)
    {
        $this->data['marketKey'] = $marketKey;
        $this->data['key'] = $key;
        $this->data['value'] = $value;
        $this->eventName = Domain\Core\Events::MARKET_CREATE_CONFIGURATION;
    }

    public function __get($property)
    {
        $value = null;
        if( isset($this->data[$property]) )
        {
            $value = $this->data[$property];
        }

        return $value;
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