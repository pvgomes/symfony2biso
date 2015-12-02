<?php

namespace AppBundle\Application\Controller\Web;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Application\Controller\Pagination;
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
        $market = $user->getMarket();

        $form = $this->createFormBuilder([$request->getContent()], ['method' => 'GET'])
            ->add('search', 'text')
            ->add('dateStart', 'text')
            ->add('dateEnd', 'text')
            ->getForm();
        $form->submit($request);
        $searchData = $form->getData();

        $orders = $this
            ->get('order_repository')
            ->listByMarket($market, $firstResult, $maxResult, $searchData);

        $viewVars = $this->getPagination($pageCurrent, $maxResult, $firstResult, count($orders));

        $viewVars['orders'] = $orders;
        $viewVars['form']   = $form->createView();
        $viewVars['filterParam'] = $this->getFilterUrl($searchData);

        return $this->render('web/order/list.html.twig', $viewVars);
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
