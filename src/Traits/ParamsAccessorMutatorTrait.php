<?php namespace Omnipay\VnPay\Traits;

use Omnipay\Common\Exception\BadMethodCallException;

/**
 * ParamsAccessorMutatorTrait
 *
 * @package omnipay-vnpay
 * @author Jackie Do <anhvudo@gmail.com>
 * @copyright 2018
 * @version $Id$
 * @access public
 */
trait ParamsAccessorMutatorTrait
{
    /**
     * Initialize this gateway with default parameters
     *
     * @param  array $parameters
     *
     * @return $this
     */
    public function initialize(array $parameters = array())
    {
        $modifiers = [];

        foreach ($parameters as $key => $value) {
            if (substr($key, 0, 4) == 'vnp_') {
                $key = substr_replace($key, '', 0, 4);
            }

            $modifiers[$key] = $value;
        }

        return parent::initialize($modifiers);
    }

    /**
     * Method overloading
     *
     * @param  string $name      The name of the method being called
     * @param  array  $arguments Enumerated array containing the parameters passed to the $name'ed method.
     * @throws BadMethodCallException
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        $catchFirstSix = substr($name, 0, 6);
        $catchAtSeven  = substr($name, 6, 1);
        $replaceLength = $catchAtSeven == '_' ? 7 : 6;

        switch (true) {
            case (strtolower($catchFirstSix) == 'setvnp'):
                $alias = substr_replace($name, 'set', 0, $replaceLength);
                break;

            case (strtolower($catchFirstSix) == 'getvnp'):
                $alias = substr_replace($name, 'get', 0, $replaceLength);
                break;

            default:
                $alias = $name;
                break;
        }

        if (!method_exists($this, $alias)) {
            throw new BadMethodCallException('Call to undefined method ' .get_class($this). '::' . $alias . '()');
        }

        return call_user_func_array([$this, $alias], $arguments);
    }

    /**
     * Get the value of the tmnCode parameter
     *
     * @return string
     */
    public function getTmnCode()
    {
        return $this->getParameter('tmnCode');
    }

    /**
     * Set the value of the tmnCode parameter
     *
     * @param  string $value
     *
     * @return $this
     */
    public function setTmnCode($value)
    {
        return $this->setParameter('tmnCode', $value);
    }

    /**
     * Get the value of the hashSecret parameter
     *
     * @return string
     */
    public function getHashSecret()
    {
        return $this->getParameter('hashSecret');
    }

    /**
     * Set the value of the hashSecret parameter
     *
     * @param  string $value
     *
     * @return $this
     */
    public function setHashSecret($value)
    {
        return $this->setParameter('hashSecret', $value);
    }
}
