<?php

namespace AppBundle\Application\Api\v1\Validator;

use JsonSchema\Uri\UriRetriever;
use JsonSchema\Validator as JsonSchemaValidator;

Trait JsonValidator
{
    /**
     * @var \JsonSchema\Validator
     */
    private $validator;

    public function isValidJson($schemaName, $jsonData)
    {
        $this->validator = new JsonSchemaValidator();
        $this->validator->check($jsonData, $this->getSchemaFile($schemaName));

        return $this->validator->isValid();
    }

    /**
     * Returns a array with json validator errors
     * @return string JSON
     */
    public function getJsonErrors()
    {
        return json_encode($this->validator->getErrors());
    }

    private function getSchemaFile($schemaName)
    {
        $retrivier = new UriRetriever();
        $schema = $retrivier->retrieve('file://' . $this->getSchemaDirectory() .DIRECTORY_SEPARATOR. $schemaName);

        return $schema;
    }

    /**
     * @return string
     */
    private function getSchemaDirectory()
    {
        $rootDir = $this->get('kernel')->getRootDir();

        return $rootDir.'/../src/AppBundle/Application/Api/v1/Schemas/';
    }

    /**
     * Get Order Schema by Seller and Market, if not exists, loads default schema
     *
     * @param string $sellerKey
     * @param string $action
     * @param bool $useMarketKey
     * @return string filename
     */
    private function loadOrderSchema($sellerKey, $action, $useMarketKey = true)
    {
        if ($useMarketKey) {
            $partnerKey = (self::PARTNER_KEY)?'_'.self::PARTNER_KEY:'';

            $schema = 'order_'.$action.$partnerKey.'.json';
            $sellerSchema = 'order_'.$action.$partnerKey.'_'.$sellerKey.'.json';

            if (file_exists($this->getSchemaDirectory().DIRECTORY_SEPARATOR.$sellerSchema)) {
                $schema = $sellerSchema;
            }
        } else {
            $schema = "order_$action.json";
        }

        return $schema;
    }

    /**
     * Get Product Schema filename
     *
     * @param string $action
     *
     * @return string filename
     */
    private function productSchema($action)
    {
        $schema = 'product_'.$action.'.json';

        return $schema;
    }

    /**
     * @param string $sellerKey
     *
     * @return string filename
     */
    public function loadOrderCreateGFGSchema($sellerKey)
    {
        return $this->loadOrderSchema($sellerKey, 'create');
    }

    /**
     * @param string $sellerKey
     *
     * @return string filename
     */
    public function loadOrderCreateWalmartSchema($sellerKey)
    {
        return $this->loadOrderSchema($sellerKey, 'create');
    }

    /**
     * @return string filename
     */
    public function productStockUpdateSchema()
    {
        return $this->productSchema('stock_update');
    }

    /**
     * @return string filename
     */
    public function productPriceUpdateSchema()
    {
        return $this->productSchema('price_update');
    }

    public function loadOrderPreviewWalmartSchema()
    {
        return $this->loadOrderSchema('', 'preview');
    }

    public function productUpdateSchema()
    {
        return $this->productSchema('update');
    }

    public function orderCancelSchema()
    {
        return $this->loadOrderSchema('', 'cancel', false);
    }

    public function orderShipSchema($sellerKey = '', $useMarketKey = false)
    {
        return $this->loadOrderSchema($sellerKey, 'ship', $useMarketKey);
    }
}
