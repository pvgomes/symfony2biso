<?php

namespace AppBundle\Application\Order;

use AppBundle\Application\ApplicationEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\Event;
use AppBundle\Application\QueueAbstract;
use AppBundle\Infrastructure;

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
            /** @var \Domain\Order\Order $order */
            $order = $orderRepository->get($command->orderId);
            $configuration = $order->getMarket()->getConfiguration();
            /** @var \AppBundle\Infrastructure\Market\ClientOrderInterface $marketClient */
            $marketClient = new Infrastructure\Market\MarketClient($configuration);
            $response = $marketClient->createOrder($order);
            $marketOrderNumber = json_decode($response);
            $order->setMarketOrderNumber($marketOrderNumber->order_number);
            $orderRepository->add($order);
        } catch (\Exception $exception) {
            // retry
        }
    }

}
