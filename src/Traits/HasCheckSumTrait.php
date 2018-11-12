<?php namespace Omnipay\VnPay\Traits;

/**
 * HasCheckSumTrait
 *
 * @package omnipay-vnpay
 * @author Jackie Do <anhvudo@gmail.com>
 * @copyright 2018
 * @version $Id$
 * @access public
 */
trait HasCheckSumTrait
{
    /**
     * Store checksum string
     *
     * @var string
     */
    protected $checkSum;

    /**
     * Get chechsum string
     *
     * @return string
     */
    protected function getCheckSum()
    {
        return $this->checkSum;
    }

    /**
     * Store checksum string
     *
     * @param  array        $data
     * @param  null|string  $secure_secret
     *
     * @return $this
     */
    protected function setCheckSum(array $data = [], $secure_secret = null)
    {
        $this->checkSum = $this->createCheckSum($data, $secure_secret);

        return $this;
    }

    /**
     * Generate a computed hash string for checksum
     *
     * @param  array        $data
     * @param  null|string  $secure_secret
     *
     * @return null|string
     */
    protected function createCheckSum(array $data = [], $secure_secret = null)
    {
        if (isset($secure_secret)) {
            // Remove element with key vnp_SecureHash and vnp_SecureHashType out of from $data if exists
            unset($data['vnp_SecureHash']);
            unset($data['vnp_SecureHashType']);

            // Arrange array data a-z before make a hash
            ksort($data);

            // Generate empty string
            $stringHashData = '';

            // Get all the none-empty element in $data and append it to hash string
            foreach ($data as $key => $value) {
                if ((substr($key, 0, 4) == 'vnp_')) {
                    $stringHashData .= $key . '=' . $value . '&';
                }
            }

            // Remove the last `&` character
            $stringHashData = rtrim($stringHashData, '&');

            return md5($secure_secret . $stringHashData);
        }

        return null;
    }
}
