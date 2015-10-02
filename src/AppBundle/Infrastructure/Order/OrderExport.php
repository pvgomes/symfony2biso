<?php

namespace AppBundle\Infrastructure\Order;


class OrderExport {

    private $handle;

    private $orders = [];

    /**
     * @param mixed $handle
     */
    public function setHandle($handle)
    {
        $this->handle = $handle;
    }

    /**
     * @param array $orders
     */
    public function setOrders($orders)
    {
        $this->orders = $orders;
    }


    public function export()
    {
        fputcsv($this->handle, [
            'market',
            'order_number',
            'seller_order_number',
            'market_order_number',
            'total_amount',
            'freight_amount',
            'status_market',
            'status_seller',
            'created_at',
            'updated_at'], ';');

        /**
         * @var \AppBundle\Infrastructure\Order\Order $order
         */
        foreach ($this->orders as $order) {

            $orderRow = [
                $order->getMarket()->getKeyName(),
                $order->getOrderNumber(),
                $order->getSellerOrderNumber(),
                $order->getMarketOrderNumber(),
                $order->getTotalAmount(),
                $order->getFreightAmount(),
                $order->getItems()->current()->getStatusMarket()->getKeyName(),
                $order->getItems()->current()->getStatusSeller()->getKeyName(),
                $order->getCreatedAt()->format('Y-m-d h:i:s'),
                $order->getUpdatedAt()->format('Y-m-d h:i:s')

            ];
            fputcsv($this->handle, $orderRow, ';');
        }
        fclose($this->handle);

        return;
    }

}