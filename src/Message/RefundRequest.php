<?php namespace Omnipay\VnPay\Message;

/**
 * The RefundRequest class
 */
class RefundRequest extends FetchTransactionRequest
{
    /**
     * Get value of the transactionType parameter
     *
     * @return string
     */
    public function getTransactionType()
    {
        return $this->getParameter('transactionType');
    }

    /**
     * Set value of the transactionType parameter
     *
     * @param  string  $value
     *
     * @return $this
     */
    public function setTransactionType($value)
    {
        return $this->setParameter('transactionType', $value);
    }

    /**
     * Get value of the createBy parameter
     *
     * @return string
     */
    public function getCreateBy()
    {
        return $this->getParameter('createBy');
    }

    /**
     * Set value of the createBy parameter
     *
     * @param  string  $value
     *
     * @return $this
     */
    public function setCreateBy($value)
    {
        return $this->setParameter('createBy', $value);
    }

    /**
     * Collect data for sending to gateway
     *
     * @return array
     */
    public function getData()
    {
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
                'iso_latin_alpha_dash' => true
            ],
            'transDate' => [
                'required' => true,
                'regex'    => '/^\d{14}$/'
            ],
            'transactionType' => [
                'required' => true,
                'in'       => ['02', '03']
            ],
            'amount' => [
                'required' => true,
                'digits'   => true
            ],
            'createBy' => [
                'required' => true,
                'email'    => true
            ],
        ];

        $this->validateWithRules($rules, [
            'transDate' => [
                'regex' => 'The :parameter parameter must be compatible with the format of the function date("YmdHis")'
            ]
        ]);

        $data = array_merge([
            'vnp_Command'         => 'refund',
            'vnp_OrderInfo'       => $this->getOrderInfo() ?: 'Refund request',
            'vnp_TxnRef'          => $this->getTxnRef(),
            'vnp_TransDate'       => $this->getTransDate(),
            'vnp_TransactionType' => $this->getTransactionType(),
            'vnp_Amount'          => $this->getFormatedAmount(),
            'vnp_CreateDate'      => date('YmdHis'),
            'vnp_IpAddr'          => $this->httpRequest->getClientIp(),
            "vnp_CreateBy"        => $this->getCreateBy(),
        ], $this->getBaseData());

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

        return $this->response = new RefundResponse($this, $httpResponse->getBody()->getContents());
    }
}
