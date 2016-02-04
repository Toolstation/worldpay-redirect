<?php
/**
 * Class to test observations
 */

namespace Omnipay\WorldPayRedirect;

use Omnipay\WorldPayRedirect\Message\AbstractRequest;
use Omnipay\WorldPayRedirect\Message\Observer;

/**
 * Class TestObserver
 *
 * @package Omnipay\WorldPayRedirect
 */
class TestObserver implements Observer
{

    public $observed = false;

    /**
     * @param AbstractRequest $observable
     * @param array           $data
     *
     * @return void
     */
    public function update(AbstractRequest $observable, array $data)
    {
        $this->observed = true;
    }
}
