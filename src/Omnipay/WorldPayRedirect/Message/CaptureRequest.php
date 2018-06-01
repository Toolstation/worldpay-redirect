<?php
/**
 * Purchase request.
 */

namespace Omnipay\WorldPayRedirect\Message;

/**
 * Omnipay WorldPay Redirect Purchase Request
 */
class CaptureRequest extends AbstractRequest
{
    /**
     * Sets the day of month value for the request
     *
     * @param string $value
     * @return AbstractRequest Provides a fluent interface
     */
    public function setDayOfMonth($value)
    {
        return $this->setParameter('dayOfMonth', $value);
    }

    /**
     * Sets the month value for the request
     *
     * @param string $value
     * @return AbstractRequest Provides a fluent interface
     */
    public function setMonth($value)
    {
        return $this->setParameter('month', $value);
    }

    /**
     * Sets the year value for the request
     *
     * @param string $value
     * @return AbstractRequest Provides a fluent interface
     */
    public function setYear($value)
    {
        return $this->setParameter('year', $value);
    }

    /**
     * Sets the amount value for the request
     *
     * @param string $value
     * @return AbstractRequest Provides a fluent interface
     */
    public function setAmount($value)
    {
        return $this->setParameter('amount', $value);
    }

    /**
     * Sets the currencyCode value for the request
     *
     * @param string $value
     * @return AbstractRequest Provides a fluent interface
     */
    public function setCurrencyCode($value)
    {
        return $this->setParameter('currencyCode', $value);
    }

    /**
     * Sets the exponent value for the request
     *
     * @param string $value
     * @return AbstractRequest Provides a fluent interface
     */
    public function setExponent($value)
    {
        return $this->setParameter('exponent', $value);
    }

    /**
     * Sets the debitCreditIndicator value for the request
     *
     * @param string $value
     * @return AbstractRequest Provides a fluent interface
     */
    public function setDebitCreditIndicator($value)
    {
        return $this->setParameter('debitCreditIndicator', $value);
    }

    /**
     * Gets the xml data object for the payment capture request to worldpay.
     * Ref: http://support.worldpay.com/support/kb/gg/corporate-gateway-guide/content/manage/modificationrequests.htm#Capture
     *
     * @access public
     * @return \SimpleXMLElement
     */
    public function getData()
    {
        // initialise the document
        $document = $this->getBase();

        // add the modify and orderModification childs to the document
        $orderModification = $document->addChild('modify')->addChild('orderModification');
        // add the orderCode attribute
        $orderModification->addAttribute('orderCode', $this->getParameter('transactionId'));

        // add the capture child to the document
        $capture = $orderModification->addChild('capture');

        // add a date child to the document
        $date = $capture->addChild('date');
        $date->addAttribute('dayOfMonth', $this->getParameter('dayOfMonth'));
        $date->addAttribute('month', $this->getParameter('month'));
        $date->addAttribute('year', $this->getParameter('year'));

        // finally add the amount child to the document
        $amount = $capture->addChild('amount');
        $amount->addAttribute('value', $this->getParameter('amount'));
        $amount->addAttribute('currencyCode', $this->getParameter('currencyCode'));
        $amount->addAttribute('exponent', $this->getParameter('exponent'));
        $amount->addAttribute('debitCreditIndicator', $this->getParameter('debitCreditIndicator'));

        // return the document
        return $document;
    }

    /**
     * Return a value to indicate the transaction type.
     * @return integer
     */
    public function getTransactionType()
    {
        return 4;
    }
}
