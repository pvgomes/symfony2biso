<?php

namespace AppBundle\Infrastructure\Seller;

/**
 * Class ClientTest
 *
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{
    public function testHttpClientKeyMethods()
    {
        $repositoryClient = $this->getMockBuilder('Predis\Client')
            ->setMethods(['isConnected', 'exists', 'get'])
            ->getMock();
        $repositoryClient->method('isConnected')->willReturn(true);
        $repositoryClient->method('exists')->willReturn(true);
        $repositoryClient->method('get')->willReturn(['product attributes']);

        $sellerClient = new Client($repositoryClient);

        $this->assertTrue($sellerClient->exists('TEST-ONLY'));
        $this->assertInternalType('array', $sellerClient->get('TEST-ONLY'));
    }
}
