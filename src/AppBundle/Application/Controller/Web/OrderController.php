<?php

namespace AppBundle\Application\Controller\Web;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Application\Controller\Pagination;
use AppBundle\Domain\Product;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Pagination
{
    /**
     * @Route("/order/list", name="order_list")
     */
    public function listAction(Request $request)
    {
        $pageCurrent = abs($request->query->get('page', 1));
        $maxResult = 20;
        $firstResult = ($pageCurrent > 1)
            ? ($pageCurrent - 1) * $maxResult
            : 0;

        $user = $this->getUser();
        $seller = $user->getVenture();

        $form = $this->createFormBuilder([$request->getContent()], ['method' => 'GET'])
            ->add('search', 'text')
            ->add('dateStart', 'text')
            ->add('dateEnd', 'text')
            ->getForm();
        $form->submit($request);
        $searchData = $form->getData();


        $orders = $this
            ->get('order_repository')
            ->paginateByVenture($seller, $firstResult, $maxResult, $searchData);

        $viewVars = $this->getPagination($pageCurrent, $maxResult, $firstResult, count($orders));

        $viewVars['orders'] = $orders;
        $viewVars['form']   = $form->createView();
        $viewVars['filterParam'] = $this->getFilterUrl($searchData);

        return $this->render('web/order/list.html.twig', $viewVars);
    }

    /**
     * @Route("/order/export", name="order_export_csv")
     */
    public function exportCsvAction(Request $request)
    {

        $response = new StreamedResponse();
        $orderRepository = $this->get('order_repository');

        $form = $this->createFormBuilder([$request->getContent()], ['method' => 'GET'])
            ->add('search', 'text')
            ->add('dateStart', 'text')
            ->add('dateEnd', 'text')
            ->getForm();
        $form->submit($request);
        $searchData = $form->getData();

        $orders = $orderRepository->getAllByVentureWithFilter($this->getUser()->getVenture(), $searchData);

        $handle = fopen('php://output', 'w+');
        $orderExport = $this->get('order_export');
        $orderExport->setOrders($orders);
        $orderExport->setHandle($handle);

        $response->setCallback([$orderExport, 'export']);

        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition','attachment; filename="orders.csv"');

        return $response;

    }

    /**
     * @Route("/order/{id}",name="order_view")
     */
    public function viewAction($id)
    {
        $repository = $this->get('order_repository');
        $order = $repository->get($id);

        return $this->render('web/order/view.html.twig',[
            'order' => $order
        ]);
    }

}
