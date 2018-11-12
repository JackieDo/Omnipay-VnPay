<?php namespace Omnipay\VnPay\Message;

/**
 * The FetchCheckoutResponse class
 */
class FetchCheckoutResponse extends FetchTransactionResponse
{
    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return parent::isSuccessful() && $this->isPurchaseTransaction();
    }

    /**
     * Is the response pending?
     *
     * @return boolean
     */
    public function isPending()
    {
        return parent::isPending() && $this->isPurchaseTransaction();
    }
}
