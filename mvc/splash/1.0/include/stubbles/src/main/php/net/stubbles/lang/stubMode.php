<?php
/**
 * Enum for different runtime modes of Stubbles.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  lang
 */
stubClassLoader::load('net::stubbles::lang::stubEnum',
                      'net::stubbles::lang::exceptions::stubIllegalArgumentException'
);
/**
 * Enum for different runtime modes of Stubbles.
 *
 * The mode instances contain information about which exception handler and
 * which error handler should be used, else well as whether caching is enabled
 * or not. Currently, there are four different modes available:
 * stubMode::$PROD
 *      - uses exception handler net::stubbles::lang::errorhandler::stubProdModeExceptionHandler
 *      - uses default error handler net::stubbles::lang::errorhandler::stubDefaultErrorHandler
 *      - caching enabled
 * stubMode::$TEST
 *      - uses exception handler net::stubbles::lang::errorhandler::stubDisplayExceptionHandler
 *      - uses default error handler net::stubbles::lang::errorhandler::stubDefaultErrorHandler
 *      - caching enabled
 * stubMode::$STAGE
 *      - uses exception handler net::stubbles::lang::errorhandler::stubDisplayExceptionHandler
 *      - no error handler
 *      - caching disabled
 * stubMode::$DEV
 *      - uses exception handler net::stubbles::lang::errorhandler::stubDisplayExceptionHandler
 *      - no error handler
 *      - caching disabled
 * While stage and dev mode currently are not different this may change in
 * future in case new mode depending switches become neccessary.
 * To change the exception and/or error handler to be used, set the new ones
 * via setExceptionHandler()/setErrorHandler().
 * Please be aware that you still need to register the exception/error handler,
 * this is not done automatically, regardless whether you set your own ones or
 * not. Use registerExceptionHandler() and registerErrorHandler() to do so.
 * 
 * Additionally there exists stubMode::$CURRENT. This points to the currently
 * selected mode. Without further action this is stubMode::$PROD. You can set
 * the current mode with stubMode::setCurrent().
 *
 * @package     stubbles
 * @subpackage  lang
 */
class stubMode extends stubEnum
{
    /**
     * handler method must be called statically
     */
    const HANDLER_STATIC        = 'static';
    /**
     * handler has to be an instance
     */
    const HANDLER_INSTANCE      = 'instance';
    /**
     * mode: production
     *
     * @var  stubMode
     */
    public static $PROD;
    /**
     * mode: test
     *
     * @var  stubMode
     */
    public static $TEST;
    /**
     * mode: stage
     *
     * @var  stubMode
     */
    public static $STAGE;
    /**
     * mode: development
     *
     * @var  stubMode
     */
    public static $DEV;
    /**
     * current selected mode, default: PROD
     *
     * @var  stubMode
     */
    public static $CURRENT;
    /**
     * exception handler to be used in the mode
     *
     * @var  array<string,string>
     */
    protected $exceptionHandler = null;
    /**
     * error handler to be used in the mode
     *
     * @var  array<string,string>
     */
    protected $errorHandler     = null;
    /**
     * switch whether cache should be enabled in mode or not
     *
     * @var  bool
     */
    protected $cacheEnabled     = true;

    /**
     * static initializing
     */
    // @codeCoverageIgnoreStart
    public static function __static()
    {
        // production mode
        self::$PROD                     = new self('PROD', 0);
        self::$PROD->exceptionHandler   = array('class'  => 'net::stubbles::lang::errorhandler::stubProdModeExceptionHandler',
                                                'method' => 'handleException',
                                                'type'   => self::HANDLER_INSTANCE
                                          );
        self::$PROD->errorHandler       = array('class'  => 'net::stubbles::lang::errorhandler::stubDefaultErrorHandler',
                                                'method' => 'handle',
                                                'type'   => self::HANDLER_INSTANCE
                                          );
        
        // test mode
        self::$TEST                     = new self('TEST', 1);
        self::$TEST->exceptionHandler   = array('class'  => 'net::stubbles::lang::errorhandler::stubDisplayExceptionHandler',
                                                'method' => 'handleException',
                                                'type'   => self::HANDLER_INSTANCE
                                          );
        self::$TEST->errorHandler       = array('class'  => 'net::stubbles::lang::errorhandler::stubDefaultErrorHandler',
                                                'method' => 'handle',
                                                'type'   => self::HANDLER_INSTANCE
                                          );
        
        // stage mode
        self::$STAGE                    = new self('STAGE', 2);
        self::$STAGE->exceptionHandler  = array('class'  => 'net::stubbles::lang::errorhandler::stubDisplayExceptionHandler',
                                                'method' => 'handleException',
                                                'type'   => self::HANDLER_INSTANCE
                                          );
        self::$STAGE->cacheEnabled      = false;
        
        // development mode
        self::$DEV                      = new self('DEV', 3);
        self::$DEV->exceptionHandler    = array('class'  => 'net::stubbles::lang::errorhandler::stubDisplayExceptionHandler',
                                                'method' => 'handleException',
                                                'type'   => self::HANDLER_INSTANCE
                                          );
        self::$DEV->cacheEnabled        = false;
        
        // current mode, by default PROD
        self::$CURRENT                  = self::$PROD;
    }
    // @codeCoverageIgnoreEnd

    /**
     * sets the current mode
     *
     * @param  stubMode  $mode
     */
    public static function setCurrent(self $mode)
    {
        self::$CURRENT = $mode;
    }

    /**
     * sets the exception handler to given class and method name
     *
     * To register the new exception handler call registerExceptionHandler().
     *
     * @param  string|object  $class        name or instance of exception handler class
     * @param  string         $methodName   name of exception handler method
     * @param  string         $type         optional  whether method has to be called statically or via an instance
     */
    public function setExceptionHandler($class, $methodName, $type = self::HANDLER_INSTANCE)
    {
        $this->exceptionHandler = array('class'  => $class,
                                        'method' => $methodName,
                                        'type'   => $type
                                  );
    }

    /**
     * registers exception handler for current mode
     *
     * Return value depends on registration: if no exception handler set return
     * value will be false, if registered handler was an instance the handler
     * instance will be returned, and true in any other case.
     *
     * @return  bool|object
     */
    public function registerExceptionHandler()
    {
        if (null === $this->exceptionHandler) {
            return false;
        }
        
        $callback = $this->getCallback($this->exceptionHandler);
        set_exception_handler($callback);
        if (is_object($callback[0]) === true) {
            return $callback[0];
        }
        
        return true;
    }

    /**
     * sets the error handler to given class and method name
     *
     * To register the new error handler call registerErrorHandler().
     *
     * @param  string|object  $class        name or instance of error handler class
     * @param  string         $methodName   name of error handler method
     * @param  string         $type         optional  whether method has to be called statically or via an instance
     */
    public function setErrorHandler($class, $methodName, $type = self::HANDLER_INSTANCE)
    {
        $this->errorHandler = array('class'  => $class,
                                    'method' => $methodName,
                                    'type'   => $type
                              );
    }

    /**
     * registers error handler for current mode
     *
     * Return value depends on registration: if no error handler set return value
     * will be false, if registered handler was an instance the handler instance
     * will be returned, and true in any other case.
     *
     * @return  bool|object
     */
    public function registerErrorHandler()
    {
        if (null === $this->errorHandler) {
            return false;
        }
        
        $callback = $this->getCallback($this->errorHandler);
        set_error_handler($callback);
        if (is_object($callback[0]) === true) {
            return $callback[0];
        }
        
        return true;
    }

    /**
     * checks whether cache is enabled or not
     *
     * @return  bool
     */
    public function isCacheEnabled()
    {
        return $this->cacheEnabled;
    }

    /**
     * helper method to create the callback from the handler data
     *
     * @param   array     &$handler  handler data
     * @return  callback
     * @throws  stubIllegalArgumentException
     */
    protected function getCallback(array &$handler)
    {
        if (is_string($handler['class']) === true && class_exists($handler['class'], false) === false) {
            stubClassLoader::load($handler['class']);
        }
        
        if (self::HANDLER_INSTANCE === $handler['type']) {
            if (is_string($handler['class']) === true) {
                $class    = stubClassLoader::getNonQualifiedClassName($handler['class']);
                $instance = new $class();
            } else {
                $instance = $handler['class'];
            }
            
             return array($instance, $handler['method']);
        }
        
        if (is_string($handler['class']) === false) {
            throw new stubIllegalArgumentException('Callback type should be stubMode::HANDLER_STATIC, but given handler class is an instance.');
        }
        
        return array(stubClassLoader::getNonQualifiedClassName($handler['class']), $handler['method']);
    }
}
?>