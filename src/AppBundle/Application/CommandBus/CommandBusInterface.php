<?php

namespace AppBundle\Application\CommandBus;

interface CommandBusInterface {

    public function execute(CommandInterface $command);
} 