<?php namespace Omnipay\VnPay\Message;

/**
 * The FetchTransactionRequest class
 */
class FetchTransactionRequest extends AbstractRequest
{
    /**
     * Endpoint URL in production
     *
     * @var string
     */
    protected $liveEndpoint = 'https://merchant.vnpay.vn/merchant_webapi/merchant.html';

    /**
     * Endpoint URL in test mode
     *
     * @var string
     */
    protected $testEndpoint = 'https://sandbox.vnpayment.vn/merchant_webapi/merchant.html';

    /**
     * Get value of the transDate parameter
     *
     * @return string
     */
    public function getTransDate()
    {
        return $this->getParameter('transDate');
    }

    /**
     * Set value of the transDate parameter
     *
     * @param  string $value
     *
     * @return $this
     */
    public function setTransDate($value)
    {
        return $this->setParameter('transDate', $value);
    }

    /**
     * Get value of the transactionNo parameter
     *
     * @return string
     */
    public function getTransactionNo()
    {
        return $this->getParameter('transactionNo');
    }

    /**
     * Set value of the transactionNo parameter
     *
     * @param  string  $value
     *
     * @return $this
     */
    public function setTransactionNo($value)
    {
        return $this->setParameter('transactionNo', $value);
    }

    /**
     * Collect data for sending to gateway
     *
     * @return array
     */
    public function getData()
    {
        if (empty($this->getTxnRef())) {
            $this->setTxnRef($this->httpRequest->query->get('vnp_TxnRef'));
        }

        if (empty($this->getTransDate())) {
            $this->setTransDate($this->httpRequest->query->get('vnp_PayDate'));
        }

        $rules = [
            'tmnCode'    => ['required' => true],
            'hashSecret' => ['required' => true],
            'orderInfo'  => [
                'nullable'                   => true,
                'required'                   => true,
                'iso_latin_alpha_dash_space' => true
            ],
            'txnRef' => [
                'required'             => true,
                'iso_latin_alpha_dash' => true,
            ],
            'transDate' => [
                'required' => true,
                'regex'    => '/^\d{14}$/'
            ]
        ];

        $this->validateWithRules($rules, [
            'transDate' => [
                'regex' => 'The :parameter parameter must be compatible with the format of the function date("YmdHis")'
            ]
        ]);

        $data = array_merge([
            'vnp_Command'    => 'querydr',
            'vnp_OrderInfo'  => $this->getOrderInfo() ?: 'Query transaction result',
            'vnp_TxnRef'     => $this->getTxnRef(),
            'vnp_TransDate'  => $this->getTransDate(),
            'vnp_CreateDate' => date('YmdHis'),
            'vnp_IpAddr'     => $this->httpRequest->getClientIp()
        ], $this->getBaseData());

        if ($this->getTransactionNo()) {
            $data['vnp_TransactionNo'] = $this->getTransactionNo();
        }

        $data['vnp_SecureHash'] = $this->createCheckSum($data, $this->getHashSecret());

        return $data;
    }

    /**
     * Send the request with specified data
     *
     * @param  array  $data The data to send
     *
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        $httpResponse = $this->httpClient->request('GET', $this->getEndpoint() . '?' . http_build_query($data, '', '&'), [], null);

        return $this->response = new FetchTransactionResponse($this, $httpResponse->getBody()->getContents());
    }
}
