<?php

namespace Omnipay\WorldPayRedirect\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Tests\TestCase;
use Mockery;

class ResponseTest extends TestCase
{
    /**
     * @expectedException \Omnipay\Common\Exception\InvalidResponseException
     */
    public function testMakeEmpty()
    {
        $response = Response::make($this->getMockRequest(), '');
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidResponseException
     */
    public function testConstructEmpty()
    {
        $response = new Response($this->getMockRequest(), '');
    }

    public function testPurchaseSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseSuccess.txt');
        $response = Response::make(
            $this->getMockRequest(),
            $httpResponse->getBody()
        );

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('T0211010', $response->getTransactionReference());
        $this->assertEquals(
            'https://secure-test.worldpay.com/jsp/shopper/SelectPaymentMethod.jsp?OrderKey=MYMERCHANT%5ET0211010',
            $response->getRedirection()
        );
        $this->assertEquals('1234567890', $response->getRedirectionId());
        $this->assertEmpty($response->getMessage());
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
        $this->assertSame('No description for XMLOrder', $response->getMessage());
        $this->assertNull($response->getRedirectionId());
        $this->assertNull($response->getRedirection());
        $this->assertNull($response->getTransactionReference());
    }
}
