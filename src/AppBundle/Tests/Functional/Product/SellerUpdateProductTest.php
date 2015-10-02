<?php

namespace AppBundle\Tests\Functional\Product;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class SellerUpdateProductTest extends WebTestCase
{
    private $client;
    private $defaultHeaders;

    public function setUp()
    {
        $this->loadFixtures(array(
            'HelperBundle\DataFixtures\ORM\LoadAppData',
            'HelperBundle\DataFixtures\ORM\LoadProductData'
        ));

        // Do not propagate this Events
        $mockQueueListener = $this
            ->getServiceMockBuilder('product.queue.listener')
            ->getMock();
        $mockQueueListener
            ->method('publish')
            ->willReturn(null);

        $mockLogListener = $this
            ->getServiceMockBuilder('product.log.listener')
            ->getMock();
        $mockLogListener
            ->method('log')
            ->willReturn(null);

        $this->client = static::createClient();
        $this->client->getContainer()
            ->set('product.queue.listener', $mockQueueListener);
        $this->client->getContainer()
            ->set('product.log.listener', $mockLogListener);

        $this->defaultHeaders = [
            'HTTP_Accept' => 'application/json',
            'HTTP_seller-key' => 'tricae',
            'HTTP_seller-access-token' => '123'
        ];
    }

    public function testUpdateStockWithSuccessResponse()
    {
        $this->client->request(
            'PUT',
            '/api/v1/product/HA032TO39BOQTRI-237547/stock',
            [],
            [],
            $this->defaultHeaders,
            '{"quantity":100}'
        );

        /** @var \AppBundle\Domain\Product\Product $product */
        $product = $this->getObjectManager()
            ->getRepository('AppBundle\Domain\Product\Product')
            ->findOneBySku('HA032TO39BOQTRI-237547')
        ;

        $this->assertEquals(204, $this->client->getResponse()->getStatusCode());
        $this->assertEmpty($this->client->getResponse()->getContent());
        $this->assertEquals(100, $product->getStock());
    }

    public function testUpdateStockWithWrongRequestBody()
    {
        $this->client->request('PUT', '/api/v1/product/HA032TO39BOQTRI-237547/stock', [], [], $this->defaultHeaders, '{"quantity":"stringRequest"}');
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
    }

    public function testUpdateStockWithInternalServerError()
    {
        $mockProductService = $this->getServiceMockBuilder('product_service')->getMock();
        $mockProductService
            ->method('update')
            ->will($this->throwException(new \Exception('Internal Error')));

        $this->client->getContainer()->set('product_service', $mockProductService);

        $this->client->request('PUT', '/api/v1/product/HA032TO39BOQTRI-237547/stock', [], [], $this->defaultHeaders, '{"quantity":100}');
        $this->assertEquals(500, $this->client->getResponse()->getStatusCode());
    }

    public function testUpdatePriceWithSuccessResponse()
    {
        $this->client->request(
            'PUT',
            '/api/v1/product/HA032TO39BOQTRI-237547/price',
            [],
            [],
            $this->defaultHeaders,
            '{"price":100, "special_price": 90}'
        );

        /** @var \AppBundle\Domain\Product\Product $product */
        $product = $this->getObjectManager()
            ->getRepository('AppBundle\Domain\Product\Product')
            ->findOneBySku('HA032TO39BOQTRI-237547')
        ;

        $this->assertEquals(204, $this->client->getResponse()->getStatusCode());
        $this->assertEmpty($this->client->getResponse()->getContent());
        $this->assertEquals(100, $product->getPrice());
        $this->assertEquals(90, $product->getSpecialPrice());
    }

    public function testUpdatePriceRequestBodyValidation()
    {
        $this->client->request('PUT', '/api/v1/product/HA032TO39BOQTRI-237547/price', [], [], $this->defaultHeaders, '{"price": 100}');
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
    }

    public function testUpdatePriceWithInternalServerError()
    {
        $mockProductService = $this->getServiceMockBuilder('product_service')->getMock();
        $mockProductService
            ->method('update')
            ->will($this->throwException(new \Exception('Internal Error')));

        $this->client->getContainer()->set('product_service', $mockProductService);

        $this->client->request('PUT', '/api/v1/product/HA032TO39BOQTRI-237547/price', [], [], $this->defaultHeaders, '{"price":100, "special_price": 90}');
        $this->assertEquals(500, $this->client->getResponse()->getStatusCode());
    }

    public function testUpdatePriceWithoutCredentials()
    {
        $this->client->request('PUT', '/api/v1/product/HA032TO39BOQTRI-237547/price', [], [], ['HTTP_Accept' => 'application/json'], '{"price": 100}');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testUpdateProductWithSuccessResponse()
    {
        $this->client->request(
            'PATCH',
            '/api/v1/product/HA032TO39BOQTRI-237547',
            [],
            [],
            $this->defaultHeaders,
            '{
                "status": "active",
                "name": "New product name",
                "description": "New product description",
                "price": 19.99,
                "special_price": 9.99,
                "quantity": 11
            }'
        );

        /** @var \AppBundle\Domain\Product\Product $product */
        $product = $this->getObjectManager()
            ->getRepository('AppBundle\Domain\Product\Product')
            ->findOneBySku('HA032TO39BOQTRI-237547')
        ;

        $this->assertEquals(204, $this->client->getResponse()->getStatusCode());
        $this->assertEmpty($this->client->getResponse()->getContent());
        $this->assertEquals('New product name', $product->getName());
        $this->assertEquals(19.99, $product->getPrice());
        $this->assertEquals(9.99, $product->getSpecialPrice());
        $this->assertEquals(11, $product->getStock());
    }
}
