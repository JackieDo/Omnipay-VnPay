<?php namespace Omnipay\VnPay;

use Omnipay\Common\AbstractGateway;
use Omnipay\VnPay\Traits\ParamsAccessorMutatorTrait;

/**
 * The Gateway class
 *
 * @link https://sandbox.vnpayment.vn/apis/docs/gioi-thieu/
 */
class Gateway extends AbstractGateway
{
    use ParamsAccessorMutatorTrait;

    /**
     * Get gateway display name
     *
     * This can be used by carts to get the display name for each gateway.
     *
     * @return string
     */
    public function getName()
    {
        return 'VnPay';
    }

    /**
     * Define gateway parameters, in the following format:
     *
     * @return array
     */
    public function getDefaultParameters()
    {
        return [
            'tmnCode'    => '',
            'hashSecret' => '',
            'testMode'   => false,
        ];
    }

    /**
     * Create a payment request for an invoice.
     *
     * @param  array $parameters
     *
     * @return \Omnipay\VnPay\Message\PurchaseRequest
     */
    public function purchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\VnPay\Message\PurchaseRequest', $parameters);
    }

    /**
     * Create a request to check the status of payment after purchase based
     * on the parameters returned on the browser.
     *
     * This function is usually executed on the return page provided to
     * VnPay.
     *
     * @param  array $parameters
     *
     * @return \Omnipay\VnPay\Message\CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\VnPay\Message\CompletePurchaseRequest', $parameters);
    }

    /**
     * Create a request to check the status of the purchase transaction,
     * based on the transaction code from the merchant website.
     *
     *
     * @param  array $parameters
     *
     * @return \Omnipay\VnPay\Message\FetchCheckoutRequest
     */
    public function fetchCheckout(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\VnPay\Message\FetchCheckoutRequest', $parameters);
    }

    /**
     * Create a request to check the status of the transaction,
     * based on the transaction code from the merchant website.
     *
     * This is the queryDR function that VnPay has requested.
     *
     * @param  array $parameters
     *
     * @return \Omnipay\VnPay\Message\FetchTransactionRequest
     */
    public function fetchTransaction(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\VnPay\Message\FetchTransactionRequest', $parameters);
    }

    /**
     * Create a refund request for an refund transaction.
     *
     * @param  array $parameters
     *
     * @return \Omnipay\VnPay\Message\RefundRequest
     */
    public function refund(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\VnPay\Message\RefundRequest', $parameters);
    }

    /**
     * Create a request to check the status of the refund transaction,
     * based on the transaction code from the merchant website.
     *
     *
     * @param  array $parameters
     *
     * @return \Omnipay\VnPay\Message\FetchRefundRequest
     */
    public function fetchRefund(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\VnPay\Message\FetchRefundRequest', $parameters);
    }
}
