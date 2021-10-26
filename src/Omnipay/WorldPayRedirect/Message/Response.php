<?php
/**
 * WorldPay Redirect Response.
 */

namespace Omnipay\WorldPayRedirect\Message;

use DOMDocument;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * Class Response.
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

        $responseDom = new DOMDocument();
        $responseDom->loadXML($data);

        $xmlData = simplexml_import_dom(
            $responseDom->documentElement->firstChild
        );

        return new self($request, $xmlData);
    }

    /**
     * Constructor.
     *
     * @param RequestInterface $request Request
     * @param string           $data    Data
     *
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
     * Get Redirection Id.
     *
     * @return string|null
     */
    public function getRedirectionId()
    {
        if (isset($this->data->orderStatus)) {
            $attributes = $this->data->orderStatus->reference->attributes();
            if (isset($attributes['id'])) {
                return (string) $attributes['id'];
            }
        }

        return null;
    }

    /**
     * Get Redirection.
     *
     * @return string|null
     */
    public function getRedirection()
    {
        if (isset($this->data->orderStatus)) {
            $reference = $this->data->orderStatus->reference;

            return (string) $reference;
        }

        return null;
    }

    /**
     * Get transaction reference.
     *
     * @return string
     */
    public function getTransactionReference()
    {
        if (isset($this->data->orderStatus)) {
            $attributes = $this->data->orderStatus->attributes();

            if (isset($attributes['orderCode'])) {
                return (string) $attributes['orderCode'];
            }
        }

        return null;
    }

    /**
     * Get is successful.
     *
     * @return booleanis
     */
    public function isSuccessful()
    {
        if (isset($this->data->error)) {
            return false;
        }

        return true;
    }

    /**
     * Get an error message.
     *
     * @return string
     */
    public function getMessage()
    {
        if (isset($this->data->error)) {
            return (string) $this->data->error;
        }

        return '';
    }
}
