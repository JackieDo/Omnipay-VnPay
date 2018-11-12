<?php namespace Omnipay\VnPay\Message;

/**
 * The FetchRefundRequest class
 */
class FetchRefundRequest extends FetchTransactionRequest
{
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

        return $this->response = new FetchRefundResponse($this, $httpResponse->getBody()->getContents());
    }
}
