<?php
/**
 * Created by PhpStorm.
 * User: pvgomes
 * Date: 11/2/15
 * Time: 2:49 PM
 */

namespace AppBundle\Application\Core;

use AppBundle\Application\CommandBus\CommandInterface;

class CreateMarketCommand implements CommandInterface
{

    /**
     * @var array
     */
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function __get($property)
    {
        if( isset($this->data[$property]) )
        {
            return $this->data[$property];
        }

        return null;
    }

}