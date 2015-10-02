<?php

namespace AppBundle\Application\Controller\Web;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Infrastructure\Order\OrderContext;
use AppBundle\Infrastructure\Product\ProductContext;

class DashboardController extends Controller
{

    /**
     * @Route("/dashboard/order", name="order", condition="request.isXmlHttpRequest()")
     */
    public function orderAction(Request $request)
    {
        $user = $this->getUser();
        /** @var \AppBundle\Infrastructure\Core\Seller $seller */
        $seller = $user->getSeller();

        $configuration = $this->get('configuration_repository');
        $seller->setConfiguration($configuration);

        $orderStatistics = $seller->orderStatistics();
        if (!$orderStatistics) {
            /** @var \AppBundle\Application\Order\OrderService $serviceOrder */
            $serviceOrder = $this->get('order_service');
            $orderContext = new OrderContext($seller->getKeyName(), null);
            $serviceOrder->setContext($orderContext);
            $orderStatistics = $serviceOrder->orderStatistics($seller);
            $orderStatistics = $seller->orderStatistics($orderStatistics);
        }

        return new JsonResponse(['orderStatistics' => $orderStatistics]);
    }


    /**
     * @Route("/dashboard/product", name="product", condition="request.isXmlHttpRequest()")
     */
    public function productAction(Request $request)
    {
        $user = $this->getUser();
        /** @var \AppBundle\Infrastructure\Core\Seller $seller */
        $seller = $user->getSeller();

        $configuration = $this->get('configuration_repository');
        $seller->setConfiguration($configuration);

        $productStatistics = $seller->productStatistics();
        if (!$productStatistics) {
            /** @var \AppBundle\Application\Product\ProductService $serviceProduct */
            $serviceProduct = $this->get('product_service');
            $productContext = new ProductContext($seller->getKeyName(), null);
            $serviceProduct->setContext($productContext);
            $productStatistics = $serviceProduct->productStatistics($seller);
            $productStatistics = $seller->productStatistics($productStatistics);
        }

        return new JsonResponse(['productStatistics' => $productStatistics]);
    }

}
