<?php
/**
 * Test class used to expose getEndPoint method for testing
 */

namespace Omnipay\WorldPayRedirect\Message;

/**
 * Class TestRequest
 *
 * @package Omnipay\WorldPayRedirect
 */
class TestRequest extends AbstractRequest
{

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
}
