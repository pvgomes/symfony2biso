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
        $pageCurrent = abs($request->query->get('page', 1));
        $maxResult = 20;
        $firstResult = ($pageCurrent > 1)
            ? ($pageCurrent - 1) * $maxResult
            : 0;


        $form = $this->createFormBuilder([$request->getContent()], ['method' => 'GET', 'csrf_protection' => false])
            ->add('search', 'text')
            ->add('dateStart', 'text')
            ->add('category', 'text')
            ->add('dateEnd', 'text')
            ->getForm();
        $form->submit($request);
        $searchData = $form->getData();


        $products = $this
            ->get('product_repository')
            ->paginateBySeller($this->getUser()->getSeller(), $firstResult, $maxResult, $searchData);

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
            ->getByProductId($productId);

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
            ->getByProductId($productId);

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
        $loadProductRepository = $this->get('load_product_repository');
        $loadProduct = $loadProductRepository->get($id);
        if ($loadProduct) {

            if ($action == 'success') {
                $title = 'Sucesso - Publicação: ' . $loadProduct->getName();
                $report = $loadProduct->getLoadProductSuccess();
            } else {
                $title = 'Erros - Publicação: ' . $loadProduct->getName();
                $report = $loadProduct->getLoadProductErrors();
            }

            if ($report->isEmpty()) {
                return $this->redirectToRoute('product_publish');
            }

            return $this->render('web/product/report.html.twig', [
                'report' => $report,
                'title' => $title,
            ]);
        }

        return $this->redirectToRoute('product_publish');
    }

    /**
     * @Route("/product/load/report/{id}")
     */
    public function loadsAction($id)
    {
        $loadProductRepository = $this->get('load_product_repository');
        $loadReport = $loadProductRepository->getLoadReportCount($id);

        $response = new JsonResponse($loadReport);

        return $response;
    }

    /**
     * @Route("/product/import", name="product_publish")
     */
    public function publishAction(Request $request)
    {
        /** @var \AppBundle\Infrastructure\Core\User $user */
        $user = $this->getUser();

        /** @var \AppBundle\Infrastructure\Core\Seller $seller */
        $seller = $user->getSeller();

        $loadProduct = new Product\LoadProduct();
        $loadProduct->setUser($user);
        $loadProduct->setSeller($seller);

        $form = $this->createForm(new LoadProductType(), $loadProduct, [
            'action' => $this->generateUrl('product_publish'),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

//            $productContext = new ProductContext($seller->getKeyName(), $loadProduct->getMarket()->getKeyName());
//
//            /** @var \AppBundle\Application\Product\LoadProductService $loadProductService */
//            $loadProductService = $this->get('load_product_service');
//
//            try {
//                $productContext->setEventName(AppEvents::VENTURE_LOAD_PRODUCT);
//                $productContext->setLoadProduct($loadProduct);
//                $loadProductService->setContext($productContext);
//                $loadProductService->create();
//                $flashMsg = "Planilha para publicação de produtos importada. Em alguns instantes os produtos serão publicados.";
//                $flashMsgType = "success";
//            } catch (\Exception $exception) {
//                $flashMsg = "Erro importar a planilha. Confira os erros de acordo com a planilha importada.";
//                $flashMsgType = "warning";
//            }
//
//            $this->addFlash($flashMsgType , $flashMsg);


        }

        $pageCurrent = abs($request->query->get('page', 1));
        $maxResult = 10;
        $firstResult = ($pageCurrent > 1)
            ? ($pageCurrent - 1) * $maxResult
            : 0;

        $loadProducts = $this->get('load_product_repository')->paginateByUser($user, $firstResult, $maxResult);

        $viewVars = $this->getPagination($pageCurrent, $maxResult, $firstResult, count($loadProducts));

        $viewVars['form'] = $form->createView();
        $viewVars['loads'] = $loadProducts;

        return $this->render('web/product/publish.html.twig', $viewVars);
    }


    /**
     * @Route("/product/export", name="product_export_csv")
     */
    public function exportCsvAction(Request $request)
    {

        $response = new StreamedResponse();
        $productRepository = $this->get('product_repository');

        $form = $this->createFormBuilder([$request->getContent()], ['method' => 'GET', 'csrf_protection' => false])
            ->add('search', 'text')
            ->add('dateStart', 'text')
            ->add('category', 'text')
            ->add('dateEnd', 'text')
            ->getForm();
        $form->submit($request);
        $searchData = $form->getData();;

        $products = $productRepository->getAllBySellerWithFilter($this->getUser()->getSeller(), $searchData);

        $handle = fopen('php://output', 'w+');
        $productExport = $this->get('product_export');
        $productExport->setProducts($products);
        $productExport->setHandle($handle);

        $response->setCallback([$productExport, 'export']);

        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="product.csv"');

        return $response;
    }

}
