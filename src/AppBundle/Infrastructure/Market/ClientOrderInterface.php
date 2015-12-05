<?php

namespace AppBundle\Infrastructure\Market;

use Domain\Order\Order;

interface ClientOrderInterface
{
    /**
     * @param Order $order
     * @return bool
     */
    public function createOrder(Order $order);

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
