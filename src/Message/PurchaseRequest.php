<?php namespace Omnipay\VnPay\Message;

/**
 * The PurchaseRequest class
 */
class PurchaseRequest extends AbstractRequest
{
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
                'nullable'             => true,
                'required'             => true,
                'iso_latin_alpha_dash' => true
            ],
            'amount' => [
                'required' => true,
                'digits'   => true,
            ],
            'currency' => [
                'required' => true,
                'in'       => ['VND']
            ],
            'returnUrl' => [
                'required' => true,
                'url'      => true
            ],
            'locale' => [
                'nullable' => true,
                'required' => true,
                'in'       => ['vi', 'en']
            ]
        ];

        $this->validateWithRules($rules);

        $data = array_merge([
            'vnp_Command'    => 'pay',
            'vnp_OrderInfo'  => $this->getOrderInfo() ?: 'Payment request',
            'vnp_TxnRef'     => $this->getTxnRef() ?: date('YmdHis-') . rand(),
            'vnp_Amount'     => $this->getFormatedAmount(),
            'vnp_CurrCode'   => $this->getCurrency(),
            'vnp_ReturnUrl'  => $this->getReturnUrl(),
            'vnp_Locale'     => $this->getLocale() ?: $this->httpRequest->getLocale(),
            'vnp_IpAddr'     => $this->httpRequest->getClientIp(),
            'vnp_CreateDate' => date('YmdHis'),
        ], $this->getBaseData());

        $checkSum = $this->createCheckSum($data, $this->getHashSecret());

        $data['vnp_SecureHashType'] = 'MD5';
        $data['vnp_SecureHash']     = $checkSum;

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
        return $this->response = new PurchaseResponse($this, $data);
    }
}
