<?php
/**
 * Decorator for lazy loading of pre interceptors: load and execute a pre
 * interceptor only if a specific request param is set.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_interceptors
 */
stubClassLoader::load('net::stubbles::ipo::interceptors::stubPreInterceptor');
/**
 * Decorator for lazy loading of pre interceptors: load and execute a pre
 * interceptor only if a specific request param is set.
 *
 * @package     stubbles
 * @subpackage  ipo_interceptors
 */
class stubRequestPreInterceptor extends stubSerializableObject implements stubPreInterceptor
{
    /**
     * class name of the decorated pre interceptor
     *
     * @var  string
     */
    protected $decoratedPreInterceptor;
    /**
     * name of the request param from which the decorated pre interceptor is dependend
     *
     * @var  string
     */
    protected $requestParam;

    /**
     * constructor
     *
     * @param  string  $decoratedPreInterceptor  class name of the decorated pre interceptor
     * @param  string  $requestParam             name of the request param from which the decorated pre interceptor is dependend
     */
    public function __construct($decoratedPreInterceptor, $requestParam)
    {
        $this->decoratedPreInterceptor = $decoratedPreInterceptor;
        $this->requestParam            = $requestParam;
        
    }

    /**
     * does the preprocessing stuff
     *
     * @param  stubRequest   $request   access to request data
     * @param  stubSession   $session   access to session data
     * @param  stubResponse  $response  access to response data
     */
    public function preProcess(stubRequest $request, stubSession $session, stubResponse $response)
    {
        if ($request->hasValue($this->requestParam) === false) {
            return;
        }
        
        $nqClassName = stubClassLoader::getNonQualifiedClassName($this->decoratedPreInterceptor);
        if (class_exists($nqClassName, false) === false) {
            stubClassLoader::load($this->decoratedPreInterceptor);
        }
            
        $interceptor = new $nqClassName();
        $interceptor->preProcess($request, $session, $response);
    }
}
?>