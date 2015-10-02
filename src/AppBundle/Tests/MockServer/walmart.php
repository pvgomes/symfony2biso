<?php

/**
 * Shipped Order
 */
$r3->post('/ws/seller/*/orders/*/shipping-notification', function($sellerId, $externalShopOrderNumber) {
    return file_get_contents('php://input');
});

/**
 * Delivered Order
 */
$r3->post('/ws/seller/*/orders/*/delivering-notification', function($sellerId, $externalShopOrderNumber) {
    return file_get_contents('php://input');
});