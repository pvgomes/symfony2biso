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

    /**
     * @Route("/app/poc", name="poc")
     */
    public function pocAction()
    {
        $product = $this->get('repository.product')->getBySku('MA048AP14WXLTRI-296312');
        die(var_dump($product));
    }
}
