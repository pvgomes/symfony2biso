<?php
/**
 * Created by PhpStorm.
 * User: pvgomes
 * Date: 12/4/15
 * Time: 3:58 PM
 */

namespace AppBundle\Infrastructure\Market;


use Domain\Order\Order;

class Client implements ClientOrderInterface, ClientProductInterface
{

    public function createOrder(Order $order)
    {
        // TODO: Implement createOrder() method.
    }

    public function cancelOrder(Order $order, $message)
    {
        // TODO: Implement cancelOrder() method.
    }

    public function shipOrder(Order $order, $message)
    {
        // TODO: Implement shipOrder() method.
    }

    public function deliverOrder(Order $order, $message)
    {
        // TODO: Implement deliverOrder() method.
    }

    public function createProduct($sendData)
    {
        // TODO: Implement createProduct() method.
    }

    public function updateProduct($skuId, $sendData)
    {
        // TODO: Implement updateProduct() method.
    }

    public function updatePrice($skuId, $price, $specialPrice)
    {
        // TODO: Implement updatePrice() method.
    }

    public function updateStock($skuId, $stock)
    {
        // TODO: Implement updateStock() method.
    }


}