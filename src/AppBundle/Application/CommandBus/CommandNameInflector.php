<?php

namespace AppBundle\Application\CommandBus;

use Domain\Command;

class CommandNameInflector {

    public function getHandler(Command $command)
    {
        return str_replace('Command', 'Handler', str_replace('AppBundle\Application\\', '\\Domain\\', get_class($command)));
    }
} 