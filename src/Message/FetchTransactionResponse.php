<?php namespace Omnipay\VnPay\Message;

/**
 * The FetchTransactionResponse class
 */
class FetchTransactionResponse extends Response
{
    /**
     * Transaction status codes
     *
     * @var [type]
     */
    protected $transactionCodes = [
        '00' => 'Successful transaction.',
        '01' => 'Transaction is not completed.',
        '02' => 'Transaction failed.',
        '04' => 'Transaction reversed (because customers have been deducted at the bank but the transaction was not successful at VNPAY).',
        '05' => 'VNPAY processing this transaction.',
        '06' => 'VNPAY sent request to Bank.',
        '07' => 'Transaction suspected fraud.',
        '09' => 'Refund transaction is declined.',
        '10' => 'Delivered.',
        '20' => 'The transaction has been settled to the merchant.',
    ];

    /**
     * Get transaction type.
     *
     * There is 3 type of result.
     * 01: This is purchase transaction
     * 02: This is a full refund
     * 03: This is a partial refund transaction
     *
     * @return string|null
     */
    public function getTransactionType()
    {
        if (isset($this->data['vnp_TransactionType'])) {
            return $this->data['vnp_TransactionType'];
        }

        return null;
    }

    /**
     * Get transaction status code
     *
     * @see    $this->transactionCodes
     *
     * @return string|null
     */
    public function getTransactionStatus()
    {
        if (isset($this->data['vnp_TransactionStatus'])) {
            return $this->data['vnp_TransactionStatus'];
        }

        return null;
    }

    /**
     * Is successful query?
     *
     * @return boolean
     */
    public function isSuccessfulQuery()
    {
        return parent::isSuccessful();
    }

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return $this->isSuccessfulQuery() && $this->isSuccessfulTransaction();
    }

    /**
     * Is the response pending?
     *
     * @return boolean
     */
    public function isPending()
    {
        return $this->isSuccessfulQuery() && $this->isPendingTransaction();
    }

    /**
     * Is not exists transaction?
     *
     * @return boolean
     */
    public function isNotExists()
    {
        return $this->getCode() === '91' && $this->isHashMatch();
    }

    /**
     * Is purchase transaction?
     *
     * @return boolean
     */
    public function isPurchaseTransaction()
    {
        return $this->getTransactionType() === '01';
    }

    /**
     * Is purchase transaction?
     *
     * @return boolean
     */
    public function isRefundTransaction()
    {
        return $this->getTransactionType() === '02' || $this->getTransactionType() === '03';
    }

    /**
     * Is successful transaction?
     *
     * @return boolean
     */
    public function isSuccessfulTransaction()
    {
        return $this->getTransactionStatus() === '00';
    }

    /**
     * Is pending transaction?
     *
     * @return boolean
     */
    public function isPendingTransaction()
    {
        return $this->getTransactionStatus() === '01';
    }

    /**
     * Get message from response server
     *
     * @return string
     */
    public function getMessage()
    {
        if ($this->isSuccessfulQuery()) {
            return $this->getTransactionDescription($this->data['vnp_TransactionStatus']);
        }

        if (isset($this->data['vnp_Message'])) {
            return $this->data['vnp_Message'];
        }

        if (isset($this->data['vnp_ResponseCode'])) {
            return $this->getResponseDescription($this->data['vnp_ResponseCode']);
        }

        return null;
    }

    /**
     * Get transaction description
     *
     * @param  string $transactionCode
     *
     * @return string Desciption of transaction code
     */
    public function getTransactionDescription($transactionCode)
    {
        if (array_key_exists($transactionCode, $this->transactionCodes)) {
            return $this->transactionCodes[$transactionCode];
        }

        return 'Transaction status can not be determined. Code: ' . $transactionCode;
    }
}
