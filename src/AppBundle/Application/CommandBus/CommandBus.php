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

    private function getHandler($command)
    {
        $handler = $this->inflector->getHandler($command);
        
        //$this->container->
        //return $this->container->make( $this->inflector->getHandler($command) );
    }

}