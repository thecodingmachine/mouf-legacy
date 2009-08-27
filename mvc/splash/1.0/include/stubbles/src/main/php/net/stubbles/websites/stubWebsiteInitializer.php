<?php
/**
 * Interface for website initializers.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites
 */
stubClassLoader::load('net::stubbles::ipo::interceptors::stubInterceptorInitializer',
                      'net::stubbles::lang::initializer::stubInitializer',
                      'net::stubbles::lang::initializer::stubGeneralInitializer',
                      'net::stubbles::lang::initializer::stubRegistryInitializer',
                      'net::stubbles::websites::processors::stubProcessorResolverFactory'
);
/**
 * Interface for website initializers.
 *
 * @package     stubbles
 * @subpackage  websites
 */
interface stubWebsiteInitializer extends stubInitializer
{
    /**
     * returns the registry initializer to be used
     *
     * @return  stubRegistryInitializer
     */
    public function getRegistryInitializer();

    /**
     * checks whether a general purpose initializer is set
     *
     * @return  bool
     */
    public function hasGeneralInitializer();

    /**
     * returns the general purpose initializer
     *
     * @return  stubGeneralInitializer
     */
    public function getGeneralInitializer();

    /**
     * returns the interceptor initializer to be used
     *
     * @return  stubInterceptorInitializer
     */
    public function getInterceptorInitializer();

    /**
     * returns the factory to be used to resolve the processor
     *
     * @return  stubProcessorResolverFactory
     */
    public function getProcessorResolverFactory();
}
?>