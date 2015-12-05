<?php

namespace AppBundle\Infrastructure\Core;

class Market extends \Domain\Core\Market{

    // @TODO: THINK ABOUT GOOD STRATEGY FOR THIS CONFIGURATION GETS
    public function getConfiguration()
    {
        $json =  '{"api_key":"123123","id_seller":"78","base_url":"http:\/\/symfony2biso_mockServer_1","uri_create_order":"\/tricae\/order","uri_confirm_order":"\/tricae\/order\/confirm","uri_cancel_order":"\/tricae\/order\/cancel"}';
        return json_decode($json, true);
    }

}
