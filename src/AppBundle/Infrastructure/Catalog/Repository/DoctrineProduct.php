<?php
/**
 * Created by PhpStorm.
 * User: pvgomes
 * Date: 3/23/15
 * Time: 12:04 PM
 */

namespace AppBundle\Infrastructure\Catalog\Repository;

use AppBundle\Domain\Catalog\Repository\Product as IRepository;

class DoctrineProduct implements IRepository{


    /**
     * Get Product By Sku
     * @param $sku
     * @return
     */
    public function getBySku($sku)
    {
    }

} 