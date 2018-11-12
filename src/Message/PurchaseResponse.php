<?php namespace Omnipay\VnPay\Message;

use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;

/**
 * The PurchaseResponse class
 */
class PurchaseResponse extends Response implements RedirectResponseInterface
{
    /**
     * Does the response require a redirect?
     *
     * @return boolean
     */
    public function isRedirect()
    {
        return true;
    }

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return false;
    }

    /**
     * Is the response pending?
     *
     * @return boolean
     */
    public function isPending()
    {
        return false;
    }

    /**
     * Is the transaction cancelled by the user?
     *
     * @return boolean
     */
    public function isCancelled()
    {
        return false;
    }

    /**
     * Gets the redirect target url.
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->getRequest()->getEndpoint() . '?' . http_build_query($this->data, '', '&');
    }

    /**
     * Get message
     *
     * @return null|string Response message from server
     */
    public function getMessage()
    {
        return null;
    }

}
