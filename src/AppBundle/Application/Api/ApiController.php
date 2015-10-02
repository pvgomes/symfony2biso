<?php

namespace AppBundle\Application\Api;

use FOS\RestBundle\Controller\FOSRestController;

class ApiController extends FOSRestController
{
    /**
     * @var string
     */
    protected $requestId;

    public function __construct()
    {
        $this->requestId = uniqid('', true);
    }
}
