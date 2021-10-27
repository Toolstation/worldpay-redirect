<?php

namespace Omnipay\WorldPayRedirect;

use Omnipay\Common\AbstractGateway;

/**
 * WorldPay Redirect Class.
 *
 * @link http://support.worldpay.com/support/kb/gg/pdf/rxml.pdf
 */
class Gateway extends AbstractGateway
{
    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return 'WorldPayRedirect';
    }

    /**
     * Get default parameters.
     *
     * @return array
     */
    public function getDefaultParameters()
    {
        return [
            'installation' => '',
            'merchant' => '',
            'password' => '',
            'testMode' => false,
        ];
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
     * @param string $value Installation value
     *
     * @return $this
     */
    public function setInstallation($value)
    {
        return $this->setParameter('installation', $value);
    }

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
     * @param string $value Merchant value
     *
     * @return $this
     */
    public function setMerchant($value)
    {
        return $this->setParameter('merchant', $value);
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
     * @param string $value Password value
     *
     * @return $this
     */
    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    /**
     * Purchase.
     *
     * @param array $parameters Parameters
     *
     * @return \Omnipay\WorldPayRedirect\Message\PurchaseRequest
     */
    public function purchase(array $parameters = [])
    {
        return $this->createRequest(
            '\Omnipay\WorldPayRedirect\Message\PurchaseRequest',
            $parameters
        );
    }

    /**
     * Purchase.
     *
     * @param array $parameters Parameters
     *
     * @return \Omnipay\WorldPayRedirect\Message\PurchaseRequest
     */
    public function capture(array $parameters = [])
    {
        return $this->createRequest(
            '\Omnipay\WorldPayRedirect\Message\CaptureRequest',
            $parameters
        );
    }
}
