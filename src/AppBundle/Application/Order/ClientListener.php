<?php

namespace AppBundle\Application\Order;

use AppBundle\Application\ApplicationEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\Event;
use AppBundle\Application\QueueAbstract;


class ClientListener
{
    /**
     * @var ContainerInterface
     */
    private $serviceContainer;

    public function __construct(ContainerInterface $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * @param ApplicationEvent $event
     */
    public function onMarketCreateOrder(ApplicationEvent $event)
    {
        try {
            $orderRepository = $this->serviceContainer->get('order_repository');
            $command = $event->getCommand();
            $order = $orderRepository->get($command->orderId);
            $marketClient = $this->serviceContainer->get('market_client');
            $marketClient->createOrder($order);
        } catch (\Exception $exception) {
            // retry
        }
    }

}
