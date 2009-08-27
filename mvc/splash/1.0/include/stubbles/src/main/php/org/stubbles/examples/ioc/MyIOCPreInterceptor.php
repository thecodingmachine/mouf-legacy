<?php
/**
 * Example pre interceptor for ioc.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles_examples
 * @subpackage  ioc
 */
stubClassLoader::load('net::stubbles::ioc::stubAbstractIOCPreInterceptor');
/**
 * Example pre interceptor for ioc.
 *
 * @package     stubbles_examples
 * @subpackage  ioc
 */
class MyIOCPreInterceptor extends stubAbstractIOCPreInterceptor
{
    /**
     * configure the binder
     *
     * @param  stubBinder  $binder
     */
    protected function configure(stubBinder $binder)
    {
        $binder->bind('MyResource')
               ->to('org::stubbles::examples::resources::MyResourceImpl')
               ->in(stubBindingScopes::$SESSION);
    }
}
?>