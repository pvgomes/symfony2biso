<?php
/**
 * Created by PhpStorm.
 * User: pvgomes
 * Date: 10/2/15
 * Time: 5:42 PM
 */

namespace AppBundle\Command;


interface CommandBus
{
    public function execute($command);
}