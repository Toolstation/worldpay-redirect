<?php

namespace Omnipay\WorldPayRedirect;

use Omnipay\Common\CreditCard;
use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    /**
     * The WorldPayXML gateway
     * @var \Omnipay\WorldPayRedirect\Gateway
     */
    public $gateway;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway(
            $this->getHttpClient(),
            $this->getHttpRequest()
        );
    }

    public function testGetName()
    {
        $this->assertEquals('WorldPayRedirect', $this->gateway->getName());
    }

    public function testGetShortName()
    {
        $this->assertEquals('WorldPayRedirect', $this->gateway->getShortName());
    }

    public function testGetDefaultParameters()
    {
        $defaultParameters = $this->gateway->getDefaultParameters();

        $this->assertInternalType('array', $defaultParameters);
        $this->assertArrayHasKey('installation', $defaultParameters);
        $this->assertArrayHasKey('merchant', $defaultParameters);
        $this->assertArrayHasKey('password', $defaultParameters);
        $this->assertArrayHasKey('testMode', $defaultParameters);
    }

    public function testPurchaseSuccess()
    {
        $options = [
            'amount' => '10.00',
            'transactionId' => 'T0211010',
            'card' => new CreditCard(
                [
                    'firstName' => 'Example',
                    'lastName' => 'User',
                    'email' => 'example.user@test.com',
                ]
            ),
        ];

        $this->setMockHttpResponse('PurchaseSuccess.txt');

        $response = $this->gateway->purchase($options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('T0211010', $response->getTransactionReference());
    }

    public function testPurchaseError()
    {
        $options = [
            'amount' => '10.00',
            'transactionId' => 'T0211010',
            'card' => new CreditCard(
                [
                    'firstName' => 'Example',
                    'lastName' => 'User',
                    'email' => 'example.user@test.com',
                ]
            ),
        ];

        $this->setMockHttpResponse('PurchaseFailure.txt');

        $response = $this->gateway->purchase($options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('No description for XMLOrder', $response->getMessage());
    }
}
