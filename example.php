<?php

require_once 'vendor/autoload.php';

use EbayApi\EbayApiIntegration;

$apiKey = 'your_ebay_api_key_here';
$ebayApi = new EbayApiIntegration($apiKey);

try {
    $latestOrders = $ebayApi->getLatestOrders();
    foreach ($latestOrders as $order) {
        echo "Order ID: " . $order['orderId'] . "\n";
        echo "Tracking Numbers: " . implode(', ', $order['trackingNumbers']) . "\n\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}