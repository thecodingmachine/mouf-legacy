<?php
/**
 * Interface for creating a resolver.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_processors
 */
stubClassLoader::load('net::stubbles::lang::initializer::stubInitializer',
                      'net::stubbles::websites::processors::stubProcessorResolver'
);
/**
 * Interface for creating a resolver.
 *
 * @package     stubbles
 * @subpackage  websites_processors
 */
interface stubProcessorResolverFactory extends stubInitializer
{
    /**
     * returns the resolver
     *
     * @return  stubProcessorResolver
     */
    public function getResolver();
}
?>