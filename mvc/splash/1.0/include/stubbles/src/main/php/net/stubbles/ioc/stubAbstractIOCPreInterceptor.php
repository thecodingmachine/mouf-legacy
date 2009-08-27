<?php
/**
 * Base class for preinterceptors that configure the bindings
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  ioc
 */
stubClassLoader::load('net::stubbles::ipo::interceptors::stubPreInterceptor',
                      'net::stubbles::ioc::stubBinder',
                      'net::stubbles::ioc::stubBinderRegistry'
);
/**
 * Base class for preinterceptors that configure the bindings
 *
 * @package     stubbles
 * @subpackage  ioc
 */
abstract class stubAbstractIOCPreInterceptor extends stubBaseObject implements stubPreInterceptor
{
    /**
     * does the preprocessing stuff
     *
     * @param  stubRequest   $request   access to request data
     * @param  stubSession   $session   access to session data
     * @param  stubResponse  $response  access to response data
     */
    public function preProcess(stubRequest $request, stubSession $session, stubResponse $response)
    {
        $this->configure(stubBinderRegistry::create());
    }

    /**
     * configure the binder
     *
     * @param  stubBinder  $binder
     */
    abstract protected function configure(stubBinder $binder);
}
?>