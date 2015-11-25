<?php

namespace AppBundle\Infrastructure\Order;

class ItemStatus extends \Domain\Order\ItemStatus
{
    const STATUS_PARTNER_CREATE_ORDER = 'market-create-order';
    const STATUS_VENTURE_CREATE_WAITING = 'seller-create-waiting';
    const STATUS_VENTURE_CREATE_PROCESSING = 'seller-create-processing';
    const STATUS_VENTURE_CREATE_ORDER = 'seller-create-order';
    const STATUS_PARTNER_CONFIRM_ORDER = 'market-confirm-order';
    const STATUS_VENTURE_CONFIRM_ORDER = 'seller-confirm-order';
    const STATUS_PARTNER_SHIPPED_ORDER = 'market-shipped-order';
    const STATUS_VENTURE_SHIPPED_ORDER = 'seller-shipped-order';
    const STATUS_PARTNER_DELIVERED_ORDER = 'market-delivered-order';
    const STATUS_VENTURE_DELIVERED_ORDER = 'seller-delivered-order';
    const STATUS_VENTURE_DELIVERED_FAIL_ORDER = 'seller-delivered-fail-order';
    const STATUS_PARTNER_CANCEL_ORDER = 'market-cancel-order';
    const STATUS_VENTURE_CANCEL_ORDER = 'seller-cancel-order';
    const STATUS_CANCELED = 'canceled';
}