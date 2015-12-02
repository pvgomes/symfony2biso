<?php

namespace AppBundle\Application\CommandBus;

use AppBundle\Application\ApplicationEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Domain;

class CommandBus implements Domain\CommandBus
{
    /**
     * @param ContainerInterface $serviceContainer
     */
    private $container;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var CommandNameInflector
     */
    private $inflector;

    /**
     * @var ApplicationEvent
     */
    private $applicationEvent;

    public function __construct(ContainerInterface $container, CommandNameInflector $inflector)
    {
        $this->container = $container;
        $this->inflector = $inflector;
        $this->eventDispatcher = $container->get('event_dispatcher');
        $this->applicationEvent = new ApplicationEvent();
    }

    public function execute(Domain\Command $command)
    {
        try {
            $this->applicationEvent->setCommand($command);
            $response = $this->getHandler($command)->handle($command);
            $this->eventDispatcher->dispatch($command->eventName(), $this->applicationEvent);
        } catch (\Exception $exception) {
            $this->applicationEvent->setException($exception);
            $this->eventDispatcher->dispatch($command->eventNameError(), $this->applicationEvent);
            throw $exception;
        }

        return $response;
    }

    private function getHandler(Domain\Command $command)
    {
        $handlerName = $this->inflector->getHandler($command);

        if (!class_exists($handlerName)) {
            throw new \InvalidArgumentException("command doesn't exists");
        }

        $market = $this->container->get('market_repository')->byKeyName($command->marketKey);
        $seller = $this->container->get('seller_repository')->byKeyName($command->sellerKey);
        $handler = new $handlerName($market, $seller);

        if (count($command->repositories()) > 0) {
            foreach ($command->repositories() as $repositoryName) {
                $repository = $this->container->get($repositoryName.'_repository');
                $method = "set".ucfirst($repositoryName)."Repository";
                if (method_exists($handler, $method)) {
                    $handler->$method($repository);
                }
            }
        }

        if (!$handler instanceof Domain\Handler) {
            throw new \DomainException("invalid handler");
        }

        return $handler;
    }

}