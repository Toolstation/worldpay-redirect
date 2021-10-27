<?php
/**
 * Purchase request.
 */

namespace Omnipay\WorldPayRedirect\Message;

/**
 * Omnipay WorldPay Redirect Purchase Request.
 */
class PurchaseRequest extends AbstractRequest
{
    /**
     * Get order content.
     *
     * @return string
     */
    public function getOrderContent()
    {
        return $this->getParameter('orderContent');
    }

    /**
     * Set order content.
     *
     * @param string $value Order content
     *
     * @return string
     */
    public function setOrderContent($value)
    {
        return $this->setParameter('orderContent', $value);
    }

    /**
     * Get include payment methods.
     *
     * @return array
     */
    public function getPaymentMethodInclude()
    {
        return is_null($this->getParameter('paymentMethodInclude')) ? [] : $this->getParameter('paymentMethodInclude');
    }

    /**
     * Set include payment methods.
     *
     * @param array $value Payment methods accepted
     *
     * @return array
     */
    public function setPaymentMethodInclude($value)
    {
        return $this->setParameter('paymentMethodInclude', $value);
    }

    /**
     * Get exclude payment methods.
     *
     * @return array
     */
    public function getPaymentMethodExclude()
    {
        return is_null($this->getParameter('paymentMethodExclude')) ? [] : $this->getParameter('paymentMethodExclude');
    }

    /**
     * Set exclude payment methods.
     *
     * @param array $value Payment methods not accepted
     *
     * @return array
     */
    public function setPaymentMethodExclude($value)
    {
        return $this->setParameter('paymentMethodExclude', $value);
    }

    /**
     * Get data.
     *
     * @return \SimpleXMLElement
     */
    public function getData()
    {
        $this->validate('amount');

        $data = $this->getBase();

        $order = $data->addChild('submit')->addChild('order');
        $order->addAttribute('orderCode', $this->getTransactionId());
        $installationId = $this->getInstallation();
        if (! empty($installationId)) {
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

        $paymentMethodIncludes = $this->getPaymentMethodInclude();
        if (is_array($paymentMethodIncludes) && count($paymentMethodIncludes) > 0) {
            foreach ($paymentMethodIncludes as $paymentMethodInclude) {
                $include = $paymentMethodMask->addChild('include');
                $include->addAttribute('code', $paymentMethodInclude);
            }
        } else {
            $include = $paymentMethodMask->addChild('include');
            $include->addAttribute('code', 'ALL');
        }

        $paymentMethodExcludes = $this->getPaymentMethodExclude();
        if (is_array($paymentMethodExcludes) && count($paymentMethodExcludes) > 0) {
            foreach ($paymentMethodExcludes as $paymentMethodExclude) {
                $include = $paymentMethodMask->addChild('exclude');
                $include->addAttribute('code', $paymentMethodExclude);
            }
        }

        $shopper = $order->addChild('shopper');

        $email = $this->getCard()->getEmail();

        if (! empty($email)) {
            $shopper->addChild(
                'shopperEmailAddress',
                $this->getCard()->getEmail()
            );
        }

        $shippingAddress = $order->addChild('shippingAddress');
        $address = $shippingAddress->addChild('address');
        $address->addChild('firstName', $this->getCard()->getShippingFirstName());
        $address->addChild('lastName', $this->getCard()->getShippingLastName());
        $address->addChild('address1', $this->getCard()->getShippingAddress1());
        $address->addChild('address2', $this->getCard()->getShippingAddress2());
        $address->addChild('postalCode', $this->getCard()->getShippingPostcode());
        $address->addChild('city', $this->getCard()->getShippingCity());
        $address->addChild('countryCode', $this->getCard()->getShippingCountry());
        $address->addChild('telephoneNumber', $this->getCard()->getShippingPhone());

        $billingAddress = $order->addChild('billingAddress');
        $address = $billingAddress->addChild('address');
        $address->addChild('firstName', $this->getCard()->getBillingFirstName());
        $address->addChild('lastName', $this->getCard()->getBillingLastName());
        $address->addChild('address1', $this->getCard()->getBillingAddress1());
        $address->addChild('address2', $this->getCard()->getBillingAddress2());
        $address->addChild('postalCode', $this->getCard()->getBillingPostcode());
        $address->addChild('city', $this->getCard()->getBillingCity());
        $address->addChild('countryCode', $this->getCard()->getBillingCountry());
        $address->addChild('telephoneNumber', $this->getCard()->getBillingPhone());

        return $data;
    }

    /**
     * Return a value to indicate the transaction type.
     * @return int
     */
    public function getTransactionType()
    {
        return 1;
    }
}
