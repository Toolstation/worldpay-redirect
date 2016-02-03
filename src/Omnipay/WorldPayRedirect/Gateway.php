<?php

namespace Omnipay\WorldPayRedirect;

use Omnipay\Common\AbstractGateway;

/**
 * WorldPay Redirect Class
 *
 * @link http://support.worldpay.com/support/kb/gg/pdf/rxml.pdf
 */
class Gateway extends AbstractGateway
{
    /**
     * Get name
     *
     * @access public
     * @return string
     */
    public function getName()
    {
        return 'WorldPayRedirect';
    }

    /**
     * Get default parameters
     *
     * @access public
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
     * Get installation
     *
     * @access public
     * @return string
     */
    public function getInstallation()
    {
        return $this->getParameter('installation');
    }

    /**
     * Set installation
     *
     * @param string $value Installation value
     *
     * @access public
     * @return $this
     */
    public function setInstallation($value)
    {
        return $this->setParameter('installation', $value);
    }

    /**
     * Get merchant
     *
     * @access public
     * @return string
     */
    public function getMerchant()
    {
        return $this->getParameter('merchant');
    }

    /**
     * Set merchant
     *
     * @param string $value Merchant value
     *
     * @access public
     * @return $this
     */
    public function setMerchant($value)
    {
        return $this->setParameter('merchant', $value);
    }
    
    /**
     * Get password
     *
     * @access public
     * @return string
     */
    public function getPassword()
    {
        return $this->getParameter('password');
    }

    /**
     * Set password
     *
     * @param string $value Password value
     *
     * @access public
     * @return $this
     */
    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }
    
    /**
     * Purchase
     *
     * @param array $parameters Parameters
     *
     * @access public
     * @return \Omnipay\WorldPayRedirect\Message\PurchaseRequest
     */
    public function purchase(array $parameters = [])
    {
        return $this->createRequest(
            '\Omnipay\WorldPayRedirect\Message\PurchaseRequest',
            $parameters
        );
    }
}
