<?php namespace Omnipay\VnPay\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * The CompletePurchaseRequest class
 */
class CompletePurchaseRequest extends AbstractRequest
{
    /**
     * Collect data for sending to gateway
     *
     * @return array
     */
    public function getData()
    {
        $this->validateWithRules([
            'tmnCode'    => ['required' => true],
            'hashSecret' => ['required' => true],
        ]);

        $data = $this->httpRequest->query->all();

        if (empty($data)) {
            throw new InvalidRequestException('Can not find any query parameter on your URL');
        }

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
        return $this->response = new CompletePurchaseResponse($this, $data);
    }
}
