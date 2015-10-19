<?php
/**
 * Created by PhpStorm.
 * User: pvgomes
 * Date: 10/2/15
 * Time: 5:42 PM
 */

namespace AppBundle\Command;


class SimpleCommandBus implements CommandBus {

    public function execute($command)
    {
        return $this->resolveHandler($command)->handle($command);
    }

}