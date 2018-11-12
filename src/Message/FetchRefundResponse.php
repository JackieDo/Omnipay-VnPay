<?php namespace Omnipay\VnPay\Message;

/**
 * The FetchRefundResponse class
 */
class FetchRefundResponse extends FetchTransactionResponse
{
    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return parent::isSuccessful() && $this->isRefundTransaction();
    }

    /**
     * Is the response pending?
     *
     * @return boolean
     */
    public function isPending()
    {
        return parent::isPending() && $this->isRefundTransaction();
    }
}
