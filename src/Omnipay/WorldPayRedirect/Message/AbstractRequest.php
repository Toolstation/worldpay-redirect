<?php
/**
 * Abstract class that all Requests must extend.
 */

namespace Omnipay\WorldPayRedirect\Message;

/**
 * Class AbstractRequest.
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    /**
     * The base URL for the live service.
     */
    public const EP_HOST_LIVE = 'https://secure.worldpay.com';

    /**
     * The base URL for the sandbox service.
     */
    public const EP_HOST_TEST = 'https://secure-test.worldpay.com';

    /**
     * The service URI.
     */
    public const EP_PATH = '/jsp/merchant/xml/paymentService.jsp';

    /**
     * The curent Worldpay Redirect APi version.
     */
    public const VERSION = '1.4';

    /**
     * Observers. Used to report requests and responses to.
     *
     * @var array
     */
    private $observers = [];

    /**
     * Get merchant.
     *
     * @return string
     */
    public function getMerchant()
    {
        return $this->getParameter('merchant');
    }

    /**
     * Set merchant.
     *
     * @param string $value Merchant
     *
     * @return void
     */
    public function setMerchant($value)
    {
        $this->setParameter('merchant', $value);
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->getParameter('password');
    }

    /**
     * Set password.
     *
     * @param string $value Password
     *
     * @return void
     */
    public function setPassword($value)
    {
        $this->setParameter('password', $value);
    }

    /**
     * Set up the base SimpleXMLElelment for the request with items common to all requests.
     *
     * @return \SimpleXMLElement
     */
    protected function getBase()
    {
        $data = new \SimpleXMLElement('<paymentService />');
        $data->addAttribute('version', self::VERSION);
        $data->addAttribute('merchantCode', $this->getMerchant());

        return $data;
    }

    /**
     * Get installation.
     *
     * @return string
     */
    public function getInstallation()
    {
        return $this->getParameter('installation');
    }

    /**
     * Set installation.
     *
     * @param string $value Installation
     *
     * @return void
     */
    public function setInstallation($value)
    {
        $this->setParameter('installation', $value);
    }

    /**
     * Send data.
     *
     * @param \SimpleXMLElement $data Data
     *
     * @return \Omnipay\Common\Message\ResponseInterface
     */
    public function sendData($data)
    {
        $implementation = new \DOMImplementation();

        $dtd = $implementation->createDocumentType(
            'paymentService',
            '-//WorldPay//DTD WorldPay PaymentService v1//EN',
            'http://dtd.worldpay.com/paymentService_v1.dtd'
        );

        $document = $implementation->createDocument(null, '', $dtd);
        $document->encoding = 'utf-8';

        $node = $document->importNode(dom_import_simplexml($data), true);
        $document->appendChild($node);

        $authorisation = base64_encode(
            $this->getMerchant().':'.$this->getPassword()
        );

        $headers = [
            'Authorization' => 'Basic '.$authorisation,
            'Content-Type' => 'text/xml; charset=utf-8',
        ];

        $xml = $document->saveXML();

        $this->notify(
            [
                'request' => $xml,
            ]
        );

        $httpResponse = $this->httpClient
            ->request('POST', $this->getEndpoint(), $headers, $xml);

        $this->notify(['response' => (string) $httpResponse->getBody()]);

        return $this->response = Response::make(
            $this,
            $httpResponse->getBody()
        );
    }

    /**
     * Get endpoint.
     *
     * Returns endpoint depending on test mode
     *
     * @return string
     */
    protected function getEndpoint()
    {
        if ($this->getTestMode()) {
            return self::EP_HOST_TEST.self::EP_PATH;
        }

        return self::EP_HOST_LIVE.self::EP_PATH;
    }

    /**
     * Attach an observer.
     *
     * @param Observer $observer
     */
    public function attach(Observer $observer)
    {
        $this->observers[] = $observer;
    }

    /**
     * Detach an attached observer.
     *
     * @param Observer $observer
     */
    public function detach(Observer $observer)
    {
        $this->observers = array_filter(
            $this->observers,
            function ($a) use ($observer) {
                return ! ($a === $observer);
            }
        );
    }

    /**
     * Notify all observers.
     *
     * @param $data
     */
    public function notify($data)
    {
        foreach ($this->observers as $observer) {
            $observer->update($this, $data);
        }
    }

    /**
     * Return a value to indicate the transaction type.
     * @return int
     */
    abstract public function getTransactionType();
}
