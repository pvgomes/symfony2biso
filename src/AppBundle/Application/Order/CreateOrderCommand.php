<?php

namespace AppBundle\Application\Order;

use AppBundle\Infrastructure\Order\Item;
use AppBundle\Infrastructure\Order\Order;
use Domain;

class CreateOrderCommand implements Domain\Command
{

    private $eventName;

    /**
     * @var Domain\Order\Order
     */
    private $order;

    /**
     * @var array
     */
    public $data;

    public function __construct($marketKey, $sellerKey, $orderData, $orderEvent = Domain\Order\Events::SELLER_CREATE_ORDER)
    {
        $this->data = $orderData;
        $this->data['marketKey'] = $marketKey;
        $this->data['sellerKey'] = $sellerKey;
        $this->data['orderData'] = json_encode($orderData);

        $this->eventName = $orderEvent;
        $this->order = new Order();
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
        return ['order','product'];
    }

    public function eventName()
    {
        return $this->eventName;
    }

    public function eventNameError()
    {
        return $this->eventName . ".error";
    }

    public function orderEntity()
    {
        return $this->order;
    }

    public function itemEntity()
    {
        return new Item();
    }

}