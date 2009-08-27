<?php
/**
 * Preinterceptor for putting a binder instance into the registry.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  ioc
 */
stubClassLoader::load('net::stubbles::ipo::interceptors::stubPreInterceptor',
                      'net::stubbles::ioc::stubBinderRegistry'
);
/**
 * Preinterceptor for putting a binder instance into the registry.
 *
 * @package     stubbles
 * @subpackage  ioc
 */
class stubIOCPreInterceptor extends stubBaseObject implements stubPreInterceptor
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
        $binder = stubBinderRegistry::create();
        $binder->bind('stubRequest')->toInstance($request);
        $binder->bind('stubSession')->toInstance($session);
        $binder->bind('stubResponse')->toInstance($response);
        $binder->bind('stubInjector')->toInstance($binder->getInjector());
        stubBindingScopes::$SESSION->setSession($session);
    }
}
?>