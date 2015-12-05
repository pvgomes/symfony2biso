<?php
/**
 * Created by PhpStorm.
 * User: pvgomes
 * Date: 12/4/15
 * Time: 3:58 PM
 */

namespace AppBundle\Infrastructure\Market;

use Domain\Order\InvalidOrderException;
use GuzzleHttp\Client;
use Domain\Order\Order;

class MarketClient implements ClientOrderInterface, ClientProductInterface
{

    /**
     * @var Client
     */
    private $client;

    /**
     * @var array
     */
    private $configuration;

    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;

        $clientConfiguration = ['base_uri' => $configuration['base_url']];
        if (isset($configuration['auth'])) {
            $clientConfiguration['auth'] = $configuration['auth'];
        }

        $this->client = new Client($clientConfiguration);
    }

    public function createOrder(Order $order)
    {
        try {
            $response = $this->client->post(
                $this->configuration['uri_create_order'],
                ['body' => $order->getRawData()]
            );

            return $response->getBody()->getContents();

        } catch (\Exception $exception) {
            if ($exception->getCode() == 400) {
                throw new InvalidOrderException($exception->getMessage());
            }

            throw $exception;
        }
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