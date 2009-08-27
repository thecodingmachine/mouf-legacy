<?php
/**
 * Decorator arround the facade for XJConf.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_xjconf
 */
/**
 * Decorator arround the facade for XJConf.
 *
 * @package     stubbles
 * @subpackage  util_xjconf
 */
class stubXJConfFacade extends stubBaseObject
{
    /**
     * the real facade
     *
     * @var  XJConfFacade
     */
    protected $realFacade;

    /**
     * construct the facade
     *
     * @param  XJConfFacade  $realFacade  the real facade
     */
    public function __construct($realFacade)
    {
        $this->realFacade = $realFacade;
    }

    /**
     * call interceptor
     *
     * @param   string  $method     name of the method to call
     * @param   array   $arguments  arguments to call
     * @return  mixed
     * @throws  stubXJConfException
     */
    public function __call($method, $arguments)
    {
        try {
            return call_user_func_array(array($this->realFacade, $method), $arguments);
        } catch (Exception $e) {
            throw new stubXJConfException($e->getMessage(), $e);
        }
    }
}
?>