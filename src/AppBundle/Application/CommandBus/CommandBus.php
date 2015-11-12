<?php

namespace AppBundle\Application\CommandBus;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Domain;

class CommandBus implements Domain\CommandBus
{
    /**
     * @param ContainerInterface $serviceContainer
     */
    private $container;

    /**
     * @var CommandNameInflector
     */
    private $inflector;

    public function __construct(ContainerInterface $container, CommandNameInflector $inflector)
    {
        $this->container = $container;
        $this->inflector = $inflector;
    }

    public function execute(Domain\Command $command)
    {
        return $this->getHandler($command)->handle($command);
    }

    private function getHandler(Domain\Command $command)
    {
        $handlerName = $this->inflector->getHandler($command);

        if (!class_exists($handlerName)) {
            throw new \InvalidArgumentException("command doesn't exists");
        }

        $handler = new $handlerName($this->container->get($command->resourceName().'_repository'));

        if (!$handler instanceof Domain\Handler) {
            throw new \DomainException("invalid handler");
        }

        return $handler;
    }

}