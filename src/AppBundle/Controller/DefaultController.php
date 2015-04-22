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
        $product = $this->get('repository.orm.product')->getById(1);
        return $this->render('default/index.html.twig', [
            'product' => $product
        ]);
    }

}
