<?php

namespace AppBundle\Infrastructure\Seller;

use AppBundle\Infrastructure\Core;
use AppBundle\Infrastructure\Order\Order;

interface ClientInterface
{
    public function __construct();

    public function create($sendData);


    /**
     * Create again a product on Market.
     *
     * @param string $sendData
     *
     * @return string Response content
     * @throws \Exception
     */
    public function reCreate($sendData);

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

    /**Importer
     * Update Product Stock on Market
     *
     * @param $skuId
     * @param $stock
     *
     * @return string Response content
     * @throws \Exception
     */
    public function updateStock($skuId, $stock);

    public function products($page = 1, $limit = 20);

    /**
     * @param Order $order
     * @param string $message
     * @return string Response content
     * @throws \Exception
     */
    public function cancelOrder(Order $order, $message);

    /**
     * @param Order $order
     * @param $message
     * @return string Response content
     * @throws \Exception
     */
    public function shipOrder(Order $order, $message);

    /**
     * @param Order $order
     * @param string $message
     * @return string Response content
     * @throws \Exception
     */
    public function deliverOrder(Order $order, $message);
}
