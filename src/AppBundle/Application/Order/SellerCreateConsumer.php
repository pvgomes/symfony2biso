<?php

namespace AppBundle\Application\Order;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Doctrine\DBAL\Exception\DriverException;
use AppBundle\Application\ApplicationEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
USE \Domain;

class SellerCreateConsumer implements ConsumerInterface
{
    /**
     * @var \Exception
     */
    private $exception;

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(AMQPMessage $message)
    {
        $messageObject = json_decode($message->body);

        try {
            /** @var \AppBundle\Application\CommandBus\CommandBus $commandBus */
            $commandBus = $this->container->get("command_bus");

            $createOrderCommand = new CreateOrderCommand($messageObject->marketKey, $messageObject->sellerKey, [], Domain\Order\Events::MARKET_CREATE_ORDER);
            $commandBus->execute($createOrderCommand);

        } catch (\Exception $exception) {

        }

        return true;
    }

    /**
     * To assist the validation of tests
     *
     * @return \Exception
     */
    public function getException()
    {
        return $this->exception;
    }
}
