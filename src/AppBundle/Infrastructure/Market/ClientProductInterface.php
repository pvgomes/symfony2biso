<?php

namespace AppBundle\Infrastructure\Market;

use AppBundle\Infrastructure\BusinessContext;
use AppBundle\Infrastructure\Core;
use AppBundle\Infrastructure\Order\Order;

interface ClientProductInterface
{
    public function createProduct($sendData);

    /**
     * @param string $skuId
     * @param $sendData
     * @return string Response content
     */
    public function updateProduct($skuId, $sendData);

    /**
     * Update Product Price on Market
     *
     * @param $skuId
     * @param $price
     * @return string Response content
     * @throws \Exception
     */
    public function updatePrice($skuId, $price, $specialPrice);

    /**
     * Update Product Stock on Market
     *
     * @param $skuId
     * @param $stock
     *
     * @return string Response content
     * @throws \Exception
     */
    public function updateStock($skuId, $stock);
}
