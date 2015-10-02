<?php

namespace HelperBundle\Command;

/**
 * @author Paulo Victor Gomes
 */
Class DummyData
{
    protected $redisData =  [

##region Biso Configurations
        // TRICAE - Configurations ----------------------------------------------------------------
        'biso.base_url' => 'http://www.biso.com.br',
        'biso.image_static_url' => 'http://static.biso.com.br',
        'biso.repository_type' => 'redis',
        'biso.redis.repository_url' => 'tcp://localhost:6379',
        'biso.redis.product_prefix'  => 'ww_product_',
        'biso.redis.category_prefix'  => 'ww_categories_category_',
        'biso.walmart.webservice' => [
            'id' => '123',
            'base_uri' => 'http://symfony2biso_mockServer_1',
            'version' => 'v0.2',
            'headers' => ['Accept' => 'application/json', 'Content-Type' => 'application/json'],
            'auth'    => ['biso', 'biso'],
        ],
        'biso.webservice' => [
            'base_url' => 'http://symfony2biso_mockServer_1',
            'uri_create_order' => 'iris/webservice/symfony2biso-create-order',
            'uri_confirm_order' => 'iris/webservice/symfony2biso-confirm-order',
            'uri_cancel_order' => 'iris/webservice/symfony2biso-market-order-cancel',
            'uri_confirm_reservation' => 'iris/webservice/symfony2bisoconfirmreservation?view=json',
        ],
        'biso.freight' => [
            'base_url' => 'http://freight.biso.net.br',
            'uri'      => 'biso/freight',
            'postalCode_black_list' => [
                ['from' => ' 53990000', 'to' => '53990970'] // Fernando de Noronha
            ]
        ],
##endregion

    ];

    public function getConfiguration()
    {
        return $this->redisData;
    }
}
