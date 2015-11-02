<?php

namespace Domain\Product;

interface Importer
{
    public function import(\Domain\Product\Product $product);
}