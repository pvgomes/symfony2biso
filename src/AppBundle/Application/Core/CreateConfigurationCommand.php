<?php

namespace AppBundle\Application\Core;

use AppBundle\Infrastructure\Core\Configuration;

use Domain;

class CreateConfigurationCommand implements Domain\Command
{

    /**
     * @var \AppBundle\Infrastructure\Core\Configuration
     */
    private $configuration;

    /**
     * @var array
     */
    public $data;

    /**
     * @var string
     */
    private $eventName;

    /**
     * @param $marketKey
     * @param $key
     * @param $value
     */
    public function __construct($marketKey, $key, $value)
    {
        $this->eventName = Domain\Core\Events::MARKET_CREATE_CONFIGURATION;
        $this->configuration = new Configuration();
        $this->data = ['marketKey' => $marketKey, 'key' => $key, 'value' => $value];
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

    public function configurationEntity()
    {
        return $this->configuration;
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
}