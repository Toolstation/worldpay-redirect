<?php
/**
 * WorldPay Redirect Response
 */
namespace Omnipay\WorldPayRedirect\Message;

use DOMDocument;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * Class Response
 * @package Omnipay\WorldPayRedirect\Message
 */
class Response extends AbstractResponse
{

    /**
     * @param RequestInterface $request
     * @param                  $data
     *
     * @return Response
     * @throws InvalidResponseException
     */
    public static function make(RequestInterface $request, $data)
    {
        if (empty($data)) {
            throw new InvalidResponseException();
        }

        $responseDom = new DOMDocument;
        $responseDom->loadXML($data);


        $xmlData = simplexml_import_dom(
            $responseDom->documentElement->firstChild->firstChild
        );

        return new Response($request, $xmlData);
    }

    /**
     * Constructor
     *
     * @param RequestInterface $request Request
     * @param string           $data    Data
     *
     * @access public
     * @throws InvalidResponseException
     */
    public function __construct(RequestInterface $request, $data)
    {
        if (empty($data)) {
            throw new InvalidResponseException();
        }

        $this->request = $request;

        $this->data = $data;
    }

    /**
     * Get Redirection Id
     *
     * @access public
     * @return string
     */
    public function getRedirectionId()
    {
        if (isset($this->data->error)) {
            $message = 'ERROR: ' . $this->data->error;
        }

        return $message;
    }

    /**
     * Get transaction reference
     *
     * @access public
     * @return string
     */
    public function getTransactionReference()
    {
        if ($this->data instanceof \SimpleXMLElement) {
            $attributes = $this->data->attributes();

            if (isset($attributes['orderCode'])) {
                return (string)$attributes['orderCode'];
            }
        }

        return null;
    }

    /**
     * Get is successful
     *
     * @access public
     * @return boolean
     */
    public function isSuccessful()
    {
        return true;
    }
}
