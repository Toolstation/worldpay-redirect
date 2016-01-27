<?php

namespace Omnipay\WorldPayXML\Message;

use Omnipay\Tests\TestCase;
use Mockery;

class ResponseTest extends TestCase
{
    /**
     * @expectedException \Omnipay\Common\Exception\InvalidResponseException
     */
    public function testConstructEmpty()
    {
        $response = Response::make($this->getMockRequest(), '');
    }

    public function testPurchaseSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseSuccess.txt');
        $response = Response::make(
            $this->getMockRequest(),
            $httpResponse->getBody()
        );

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('T0211010', $response->getTransactionReference());
        $this->assertEquals('AUTHORISED', $response->getMessage());
    }

    public function testPurchaseFailure()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseFailure.txt');
        $response = Response::make(
            $this->getMockRequest(),
            $httpResponse->getBody()
        );

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('T0211234', $response->getTransactionReference());
        $this->assertSame('CARD EXPIRED', $response->getMessage());
    }
}
