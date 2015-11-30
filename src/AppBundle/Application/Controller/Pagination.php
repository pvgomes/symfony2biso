<?php

namespace AppBundle\Application\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class Pagination
 *
 */
class Pagination extends Controller
{
    protected function getPagination($pageCurrent, $maxResult, $firstResult, $totalCollection, $maxPagination =15)
    {
        $pageTotal = ceil($totalCollection / $maxResult);
        $pageStart = $pageCurrent > $maxPagination
            ? $pageCurrent - $maxPagination
            : 1;
        $pages = $pageTotal <= $maxPagination
            ? $pageTotal
            : $pageStart + $maxPagination;
        $prev = ($pageCurrent - 1) >= 1 ? ($pageCurrent - 1) : 1;
        $next = ($pageCurrent + 1) < $pageTotal ? ($pageCurrent + 1) : $pageTotal;
        $last = $pageTotal;

        return [
            'pageStart' => $pageStart,
            'pages' => $pages,
            'page' => $pageCurrent,
            'prev' => $prev,
            'next' => $next,
            'last' => $last,
            'counter' => $firstResult
        ];
    }

    protected  function getFilterUrl($filterData)
    {
        $result = null;
        foreach ($filterData as $key => $value) {
            if ($value) {
                $result .= urlencode("form[{$key}]") . "={$value}&";
            }
        }
        return $result;
    }
}
