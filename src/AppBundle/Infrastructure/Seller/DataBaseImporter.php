<?php

namespace Application\Product;

class DataBaseImporter implements Domain\Importer
{
    private $dbAdapter;

    public function __construct(DbAdapter $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    public function import(\Domain\Product\Product $product)
    {
        $this->dbAdapter->insert($productEntity->toArray());
    }
}