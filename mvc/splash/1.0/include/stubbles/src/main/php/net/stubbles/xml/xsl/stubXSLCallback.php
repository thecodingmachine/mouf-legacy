<?php
/**
 * Class to register classes and make their methods available as callback in xsl.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  xml_xsl
 */
stubClassLoader::load('net::stubbles::xml::xsl::stubXSLCallbackException',
                      'net::stubbles::xml::xsl::stubXSLMethodAnnotation'
);
/**
 * Class to register classes and make their methods available as callback in xsl.
 *
 * @package     stubbles
 * @subpackage  xml_xsl
 */
class stubXSLCallback extends stubBaseObject
{
    /**
     * the instance
     *
     * @var  stubXSLCallback
     */
    protected static $instance;
    
    /**
     * list of callback instances
     *
     * @var  array<string,stubObject>
     */
    protected $callbacks = array();

    /**
     * constructor
     */
    protected final function __construct()
    {
        // intentionally empty
    }

    /**
     * returns an instance of this class
     *
     * @return  stubXSLCallback
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }

    /**
     * clone is forbidden
     *
     * @throws  stubRuntimeException
     */
    protected final function __clone()
    {
        throw new stubRuntimeException('Cloning of ' . __CLASS__ . ' is not allowed.');
    }

    /**
     * register a new instance as callback
     *
     * @param  string      $name      name to register the callback under
     * @param  stubObject  $callback
     */
    public function setCallback($name, stubObject $callback)
    {
        $this->callbacks[$name] = $callback;
    }

    /**
     * removes callback with given name
     *
     * @param  string  $name  name the callback is registered under
     */
    public function removeCallback($name)
    {
        if (isset($this->callbacks[$name]) == true) {
            $this->callbacks[$name] = null;
        }
    }

    /**
     * clears all callbacks
     */
    public function clearCallbacks()
    {
        $this->callbacks = array();
    }

    /**
     * check if a callback exists for the given name
     *
     * @param   string  $name  name the callback is registered under
     * @return  bool
     */
    public function hasCallback($name)
    {
        return isset($this->callbacks[$name]);
    }

    /**
     * returns the callback with the given name
     *
     * @param   string      $name  name the callback is registered under
     * @return  stubObject
     */
    public function getCallback($name)
    {
        if (isset($this->callbacks[$name]) == true) {
            return $this->callbacks[$name];
        }
        
        return null;
    }

    /**
     * invoke a method on a callback class
     *
     * @return  mixed
     * @throws  stubXSLCallbackException
     */
    public static function invoke()
    {
        $arguments = func_get_args();
        if (count($arguments) < 2) {
            throw new stubXSLCallbackException('To less arguments: need at last two arguments to use callbacks.');
        }
        
        $name   = array_shift($arguments);
        if (self::$instance->hasCallback($name) == false) {
            throw new stubXSLCallbackException('A callback with the name ' . $name . ' does not exist.');
        }
        
        $methodName = array_shift($arguments);
        $callback   = self::$instance->getCallback($name);
        $class      = $callback->getClass();
        if ($class->hasMethod($methodName) == false) {
            throw new stubXSLCallbackException('Callback with name ' . $name . ' does not have a method named ' . $methodName);
        }
        
        $method = $class->getMethod($methodName);
        if ($method->hasAnnotation('XSLMethod') == false) {
            throw new stubXSLCallbackException('The callback\'s ' . $name . ' ' . $callback->getClassName() . '::' . $methodName . '() is not annotated as XSLMethod.');
        }
        
        if ($method->isPublic() == false) {
            throw new stubXSLCallbackException('The callback\'s ' . $name . ' ' . $callback->getClassName() . '::' . $methodName . '() is not a public method.');
        }
        
        if ($method->isStatic() == true) {
            return $method->invokeArgs(null, $arguments);
        }
        
        return $method->invokeArgs($callback, $arguments);
    }
}
?>