<?php

namespace AppBundle\Application\Controller\Web;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Application\Controller\Pagination;
use AppBundle\Domain\Product;

class CategoryController extends Controller
{
    /**
     * @Route("/category/list", name="category_list")
     */
    public function listAction(Request $request)
    {
        return $this->render('web/category/list.html.twig', []);
    }

    /**
     * @Route("/category/", name="product_categories")
     */
    public function getCategoriesBySellerAction(Request $request)
    {
        return new JsonResponse(['categories' => []]);
    }
}
