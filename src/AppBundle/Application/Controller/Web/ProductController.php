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

class ProductController extends Pagination
{
    /**
     * @Route("/product/list", name="product_list")
     */
    public function listAction(Request $request)
    {
        $pageCurrent = abs($request->query->get('page', 1));
        $maxResult = 20;
        $firstResult = ($pageCurrent > 1)
            ? ($pageCurrent - 1) * $maxResult
            : 0;


        $form = $this->createFormBuilder([$request->getContent()], ['method' => 'GET', 'csrf_protection' => false])
            ->add('search', 'text')
            ->add('category', 'text')
            ->getForm();
        $form->submit($request);
        $searchData = $form->getData();


        $products = $this
            ->get('product_repository')
            ->listByMarket($this->getUser()->getMarket(), $firstResult, $maxResult, $searchData);

        $viewVars = $this->getPagination($pageCurrent, $maxResult, $firstResult, count($products), 15);

        $viewVars['products'] = $products;
        $viewVars['form'] = $form->createView();
        $viewVars['searchTerm'] = $searchData['search'];
        $viewVars['filterParam'] = $this->getFilterUrl($searchData);

        return $this->render('web/product/list.html.twig', $viewVars);
    }

    /**
     * @Route("/external-product-table/{productId}")
     */
    public function getExternalProductsTableAction($productId)
    {
        $externalProducts = $this
            ->get('external_product_repository')
            ->byProductId($productId);

        return $this->render('web/product/external-product.html.twig', [
            'externalProducts' => $externalProducts,
            'productId' => $productId,
        ]);
    }

    /**
     * @Route("/external-product-status/{productId}")
     */
    public function getExternalProductsStatusAction($productId)
    {
        $result = false;

        $externalProducts = $this
            ->get('external_product_repository')
            ->byProductId($productId);

        foreach ($externalProducts as $externalProduct) {
            if ($externalProduct->getStatus() == Product\ExternalProduct::STATUS_ACTIVE) {
                $result = true;
                break;
            }
        }

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
