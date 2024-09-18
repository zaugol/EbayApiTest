<?php

use PHPUnit\Framework\TestCase;
use EbayApi\EbayApiIntegration;

class EbayApiIntegrationTest extends TestCase {
    private $apiIntegration;

    protected function setUp(): void {
        $this->apiIntegration = new EbayApiIntegration('dummy_api_key');
    }

    public function testGetLatestOrders() {
        $mockResponse = json_encode([
            'orders' => [
                [
                    'orderId' => '12345',
                    'fulfillmentStartInstructions' => [
                        [
                            'shippingStep' => [
                                'shipTo' => [
                                    'trackingNumber' => 'TRACK123'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        // Mock curl_exec function
        $this->mockCurlExec($mockResponse);

        $result = $this->apiIntegration->getLatestOrders();

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('12345', $result[0]['orderId']);
        $this->assertEquals(['TRACK123'], $result[0]['trackingNumbers']);
    }

    private function mockCurlExec($response) {
        $mock = $this->createPartialMock(EbayApiIntegration::class, ['curlExec']);
        $mock->method('curlExec')->willReturn($response);

        $reflectionClass = new ReflectionClass(EbayApiIntegration::class);
        $reflectionProperty = $reflectionClass->getProperty('instance');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue(null, $mock);
    }
}