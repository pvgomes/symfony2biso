<?php

namespace AppBundle\Infrastructure\Product;

class ExternalProduct extends \Domain\Product\ExternalProduct
{
    const STATUS_NEW = 'new';
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    /**
     * @return array
     */
    public static function listStatus()
    {
        return [
            self::STATUS_NEW => self::STATUS_NEW,
            self::STATUS_ACTIVE => self::STATUS_ACTIVE,
            self::STATUS_INACTIVE => self::STATUS_INACTIVE,
        ];
    }
}