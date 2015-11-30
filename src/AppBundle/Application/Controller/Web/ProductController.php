<?php

namespace AppBundle\Application\Controller\Web;

use AppBundle\Infrastructure\Product\ProductContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use AppBundle\Application\Controller\Pagination;
use AppBundle\Application\AppEvents;
use AppBundle\Application\Product\LoadProductType;
use AppBundle\Infrastructure\Product;

class ProductController extends Controller
{
    /**
     * @Route("/product/list", name="product_list")
     */
    public function listAction(Request $request)
    {
        return $this->render('web/product/list.html.twig', []);
    }

    /**
     * @Route("/external-product-table/{productId}")
     */
    public function getExternalProductsTableAction($productId)
    {
        return $this->render('web/product/external-product.html.twig', []);
    }

    /**
     * @Route("/external-product-status/{productId}")
     */
    public function getExternalProductsStatusAction($productId)
    {
        $result = false;

        return new JsonResponse(['isActive' => $result]);
    }

    /**
     * @Route("/product/load/report/{action}/{id}", name="product_load_report")
     */
    public function loadProductReportAction($action, $id)
    {
        return $this->redirectToRoute('product_publish');
    }


    /**
     * @Route("/product/import", name="product_publish")
     */
    public function publishAction(Request $request)
    {
        return $this->render('web/product/publish.html.twig', []);
    }

}
