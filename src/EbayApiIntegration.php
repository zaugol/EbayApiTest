<?php

namespace EbayApi;

class EbayApiIntegration {
    private $apiKey;
    private $apiUrl = 'https://api.ebay.com/sell/fulfillment/v1/order';
    private static $instance;

    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
        self::$instance = $this;
    }

    public function getLatestOrders($limit = 10) {
        $url = $this->apiUrl . '?limit=' . $limit . '&filter=orderFulfillmentStatus:{NOT_STARTED|IN_PROGRESS}';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->apiKey,
            'Content-Type: application/json'
        ]);

        $response = $this->curlExec($ch);

        if (curl_errno($ch)) {
            throw new \Exception("cURL Error: " . curl_error($ch));
        }

        curl_close($ch);

        $data = json_decode($response, true);

        if (!isset($data['orders'])) {
            throw new \Exception("Unexpected API response");
        }

        return $this->extractOrderInfo($data['orders']);
    }

    protected function curlExec($ch) {
        return curl_exec($ch);
    }

    private function extractOrderInfo($orders) {
        $result = [];
        foreach ($orders as $order) {
            $trackingNumbers = [];
            foreach ($order['fulfillmentStartInstructions'] as $instruction) {
                if (isset($instruction['shippingStep']['shipTo']['trackingNumber'])) {
                    $trackingNumbers[] = $instruction['shippingStep']['shipTo']['trackingNumber'];
                }
            }

            $result[] = [
                'orderId' => $order['orderId'],
                'trackingNumbers' => $trackingNumbers
            ];
        }
        return $result;
    }

    public static function getInstance() {
        return self::$instance;
    }
}