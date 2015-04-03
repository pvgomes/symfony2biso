<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        die("AOOOO");
    }

    /**
     * @Route("/app/poc", name="poc")
     */
    public function pocAction()
    {
        $product = $this->get('repository.product')->getBySku('MA048AP14WXLTRI-296312');
        die(var_dump($product));
    }
}
