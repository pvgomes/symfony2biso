<?php

namespace AppBundle\Infrastructure\Seller;

use AppBundle\Infrastructure\Order\Order;
use AppBundle\Infrastructure\Order\Seller\SellerOrder;

interface AdapterInterface
{
    /**
     * Create an order at Seller API
     * @param SellerOrder $sellerOrder
     * @return string
     * @throws InvalidOrderException
     */
    public function createOrder(SellerOrder $sellerOrder);

    /**
     * Confirm an order at Seller API
     * @param Order $order
     * @return string
     */
    public function confirmOrder(Order $order);

    public function cancelOrder(Order $order);

    public function confirmReservation(array $body);
}
