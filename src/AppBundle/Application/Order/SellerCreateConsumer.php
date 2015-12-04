<?php

namespace AppBundle\Application\Order;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Doctrine\DBAL\Exception\DriverException;
use Symfony\Component\DependencyInjection\Container;

class SellerCreateConsumer implements ConsumerInterface
{
    /**
     * @var \Exception
     */
    private $exception;

    public function __construct(Container $container)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function execute(AMQPMessage $message)
    {
        $messageObject = json_decode($message->body);

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
