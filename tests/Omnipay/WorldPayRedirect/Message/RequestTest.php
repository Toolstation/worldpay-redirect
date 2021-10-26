<?php

namespace Omnipay\WorldPayRedirect\Message;

use Omnipay\Tests\TestCase;
use Mockery;
use Omnipay\Common\Http\Client;

class RequestTest extends TestCase
{
    public function setUp()
    {
        date_default_timezone_set('UTC');
    }

    public function testGetEndPoint()
    {
        $httpClient = Mockery::mock(Client::class);
        $request = Mockery::mock('Symfony\Component\HttpFoundation\Request');
        $omnipayRequest = new class ($httpClient, $request) extends AbstractRequest {
            public function getTransactionType()
            {
            }

            public function getData()
            {
            }

            public function getEndpoint()
            {
                return parent::getEndpoint();
            }
        };
        $omnipayRequest->setTestMode(true);
        $this->assertEquals(AbstractRequest::EP_HOST_TEST.AbstractRequest::EP_PATH, $omnipayRequest->getEndpoint());
    }

    public function testPurchaseRequestSetters()
    {
        $httpClient = Mockery::mock(Client::class);
        $request = Mockery::mock('Symfony\Component\HttpFoundation\Request');

        $card = Mockery::mock('Omnipay\Common\CreditCard');
        $card->shouldReceive('getEmail')->andReturn('name@test.com');
        $card->shouldReceive('getShippingFirstName')->andReturn('firstname');
        $card->shouldReceive('getShippingLastName')->andReturn('lastname');
        $card->shouldReceive('getShippingAddress1')->andReturn('address1');
        $card->shouldReceive('getShippingAddress2')->andReturn('address2');
        $card->shouldReceive('getShippingPostcode')->andReturn('postcode');
        $card->shouldReceive('getShippingCity')->andReturn('city');
        $card->shouldReceive('getShippingCountry')->andReturn('country');
        $card->shouldReceive('getShippingPhone')->andReturn('telephone');

        $card->shouldReceive('getBillingFirstName')->andReturn('firstname');
        $card->shouldReceive('getBillingLastName')->andReturn('lastname');
        $card->shouldReceive('getBillingAddress1')->andReturn('address1');
        $card->shouldReceive('getBillingAddress2')->andReturn('address2');
        $card->shouldReceive('getBillingPostcode')->andReturn('postcode');
        $card->shouldReceive('getBillingCity')->andReturn('city');
        $card->shouldReceive('getBillingCountry')->andReturn('country');
        $card->shouldReceive('getBillingPhone')->andReturn('telephone');

        $omnipayRequest = new PurchaseRequest($httpClient, $request);

        $this->assertEquals(1, $omnipayRequest->getTransactionType());

        $omnipayRequest->setOrderContent('order content');
        $this->assertEquals('order content', $omnipayRequest->getOrderContent());

        $omnipayRequest->setPaymentMethodInclude('include');
        $this->assertEquals('include', $omnipayRequest->getPaymentMethodInclude());

        $omnipayRequest->setPaymentMethodExclude('exclude');
        $this->assertEquals('exclude', $omnipayRequest->getPaymentMethodExclude());

        $omnipayRequest->setAmount(12.34);

        $omnipayRequest->setCard($card);

        $data = $omnipayRequest->getData();

        $this->assertContains('submit', $data->asXml());
        $this->assertNotContains('exclude', $data->asXml());

        $omnipayRequest->setPaymentMethodInclude(['include']);
        $data = $omnipayRequest->getData();
        $this->assertContains('include', $data->asXml());

        $omnipayRequest->setPaymentMethodExclude(['exclude']);
        $data = $omnipayRequest->getData();
        $this->assertContains('exclude', $data->asXml());

        $this->assertNotContains('installationId', $data->asXml());
        $omnipayRequest->setInstallation('installation');
        $data = $omnipayRequest->getData();
        $this->assertContains('installationId', $data->asXml());
    }
}
