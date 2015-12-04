<?php

namespace AppBundle\Application\Order;

use AppBundle\Application\ApplicationEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\Event;
use AppBundle\Application\QueueAbstract;

class QueueListener extends QueueAbstract
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
     * {@inheritdoc}
     * @throws QueueException
     */
    protected function publish($producerKey, Event $event, array $message = [])
    {
        $command = $event->getCommand();
        $order = $command->orderEntity();
        $baseMessage = $event->getMessage();

        if (is_null($baseMessage)) {
            $baseMessage = [
                'orderId' => $order->getId(),
                'marketKey' => $order->getMarket()->getKeyName(),
                'sellerKey' => $order->getSeller()->getKeyName(),
                'timestamp' => date('c'),
            ];
        }

        $finalMessage = array_merge($baseMessage, $message);

        try {
            $producerName = $this->handleProducerName($producerKey);
            $this->serviceContainer->get($producerName)->publish(json_encode($finalMessage));

        } catch (\Exception $exception) {
            $exceptionMessage = [
                'queueName' => $producerName,
                'queueMessage' => $finalMessage,
                'exceptionCode' => $exception->getCode(),
                'exceptionMessage' => $exception->getMessage(),
                'exceptionTrace' => $exception->getTraceAsString(),
            ];

            throw new \Exception(json_encode($exceptionMessage), 500);
        }
    }

    /**
     * @param ApplicationEvent $event
     */
    public function onSellerCreateOrder(ApplicationEvent $event)
    {
        $this->publish('seller_create_order', $event);
    }

}
