<?php namespace Omnipay\VnPay\Message;

/**
 * The FetchCheckoutRequest class
 */
class FetchCheckoutRequest extends FetchTransactionRequest
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

        return $this->response = new FetchCheckoutResponse($this, $httpResponse->getBody()->getContents());
    }
}
