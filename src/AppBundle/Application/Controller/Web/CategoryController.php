<?php

namespace AppBundle\Application\Controller\Web;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Application\Controller\Pagination;
use AppBundle\Domain\Product;

class CategoryController extends Pagination
{
    /**
     * @Route("/category/list", name="category_list")
     */
    public function listAction(Request $request)
    {
        $pageCurrent = abs($request->query->get('page', 1));
        $maxResult = 10;
        $firstResult = ($pageCurrent > 1)
            ? ($pageCurrent - 1) * $maxResult
            : 0;

        $user = $this->getUser();
        $market = $user->getMarket();
        $categories = $this
            ->get('category_repository')
            ->listByMarket($market, $firstResult, $maxResult);

        $viewVars = $this->getPagination($pageCurrent, $maxResult, $firstResult, count($categories));

        $viewVars['categories'] = $categories;

        return $this->render('web/category/list.html.twig', $viewVars);
    }

    /**
     * @Route("/category/", name="product_categories")
     */
    public function getCategoriesBySellerAction(Request $request)
    {
        return new JsonResponse(['categories' => []]);
    }
}
