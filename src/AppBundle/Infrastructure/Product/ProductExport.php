<?php

namespace AppBundle\Infrastructure\Product;

class ProductExport
{
    private $handle;

    private $products = [];

    /**
     * @param mixed $handle
     */
    public function setHandle($handle)
    {
        $this->handle = $handle;
    }

    /**
     * @param array $products
     */
    public function setProducts($products)
    {
        $this->products = $products;
    }


    public function export()
    {
        fputcsv($this->handle, [
            'market',
            'name',
            'sku',
            'external_sku',
            'category',
            'stock',
            'price',
            'special_price',
            'created_at',
            'updated_at',
            'status'
        ], ';');

        /**
         * @var \AppBundle\Infrastructure\Product\Product $product
         */
        foreach ($this->products as $product) {

            /**
             * @var \AppBundle\Infrastructure\Product\ExternalProduct $externalProduct
             */
            foreach ($product->getExternalProducts() as $externalProduct) {
                $productRow = [
                    $externalProduct->getMarket()->getKeyName(),
                    $product->getName(),
                    $product->getSku(),
                    $externalProduct->getSku(),
                    $product->getCategory()->getName(),
                    $product->getStock(),
                    $product->getPrice(),
                    $product->getSpecialPrice(),
                    $product->getCreatedAt()->format('Y-m-d h:i:s'),
                    $product->getUpdatedAt()->format('Y-m-d h:i:s'),
                    $externalProduct->getStatus()
                ];
                fputcsv($this->handle, $productRow, ';');
            }
        }
        fclose($this->handle);

        return;
    }
}
