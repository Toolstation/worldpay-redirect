<?php
/**
 * Purchase request.
 */

namespace Omnipay\WorldPayRedirect\Message;

/**
 * Omnipay WorldPay Redirect Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    /**
     * Get order content
     *
     * @access public
     * @return string
     */
    public function getOrderContent()
    {
        return $this->getParameter('orderContent');
    }

    /**
     * Set order content
     *
     * @param string $value Order content
     *
     * @access public
     * @return string
     */
    public function setOrderContent($value)
    {
        return $this->setParameter('orderContent', $value);
    }

    /**
     * Get include payment methods
     *
     * @access public
     * @return array
     */
    public function getPaymentMethodInclude()
    {
        return $this->getParameter('paymentMethodInclude');
    }

    /**
     * Set include payment methods
     *
     * @param array $value Payment methods accepted
     *
     * @access public
     * @return array
     */
    public function setPaymentMethodInclude($value)
    {
        return $this->setParameter('paymentMethodInclude', $value);
    }

    /**
     * Get exclude payment methods
     *
     * @access public
     * @return array
     */
    public function getPaymentMethodExclude()
    {
        return $this->getParameter('paymentMethodExclude');
    }

    /**
     * Set exclude payment methods
     *
     * @param array $value Payment methods not accepted
     *
     * @access public
     * @return array
     */
    public function setPaymentMethodExclude($value)
    {
        return $this->setParameter('paymentMethodExclude', $value);
    }

    /**
     * Get data
     *
     * @access public
     * @return \SimpleXMLElement
     */
    public function getData()
    {
        $this->validate('amount');

        $data = $this->getBase();

        $order = $data->addChild('submit')->addChild('order');
        $order->addAttribute('orderCode', $this->getTransactionId());
        $installationId = $this->getInstallation();
        if (!empty($installationId)) {
            $order->addAttribute('installationId', $installationId);
        }

        $order->addChild('description', $this->getDescription());

        $amount = $order->addChild('amount');
        $amount->addAttribute('value', $this->getAmountInteger());
        $amount->addAttribute('currencyCode', $this->getCurrency());
        $amount->addAttribute('exponent', $this->getCurrencyDecimalPlaces());

        $orderContent = $order->addChild('orderContent');
        $orderContentNode = dom_import_simplexml($orderContent);
        $orderContentOwner = $orderContentNode->ownerDocument;
        $orderContentNode->appendChild($orderContentOwner->createCDATASection($this->getOrderContent()));

        $paymentMethodMask = $order->addChild('paymentMethodMask');

        foreach ($this->getPaymentMethodInclude() as $paymentMethodInclude) {
            $include = $paymentMethodMask->addChild('include');
            $include->addAttribute('code', $paymentMethodInclude);
        }

        foreach ($this->getPaymentMethodExclude() as $paymentMethodExclude) {
            $include = $paymentMethodMask->addChild('exclude');
            $include->addAttribute('code', $paymentMethodExclude);
        }

        $shopper = $order->addChild('shopper');

        $email = $this->getCard()->getEmail();

        if (!empty($email)) {
            $shopper->addChild(
                'shopperEmailAddress',
                $this->getCard()->getEmail()
            );
        }

        $shippingAddress = $order->addChild('shippingAddress');
        $address = $shippingAddress->addChild('address');
        $address->addChild('firstName', $this->getCard()->getShippingFirstName());
        $address->addChild('lastName', $this->getCard()->getShippingLastName());
        $address->addChild('street', $this->getCard()->getShippingAddress1());
        $address->addChild('postalCode', $this->getCard()->getShippingPostcode());
        $address->addChild('city', $this->getCard()->getShippingCity());
        $address->addChild('countryCode', $this->getCard()->getShippingCountry());
        $address->addChild('telephoneNumber', $this->getCard()->getshippingPhone());

        return $data;
    }

    /**
     * Return a value to indicate the transaction type.
     * @return integer
     */
    public function getTransactionType()
    {
        return 1;
    }
}
