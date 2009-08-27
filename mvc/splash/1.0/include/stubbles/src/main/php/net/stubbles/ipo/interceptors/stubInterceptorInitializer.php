<?php
/**
 * Interface for initializing the interceptors.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_interceptors
 */
stubClassLoader::load('net::stubbles::ipo::interceptors::stubPostInterceptor',
                      'net::stubbles::ipo::interceptors::stubPreInterceptor',
                      'net::stubbles::lang::initializer::stubInitializer'
);
/**
 * Interface for initializing the interceptors.
 *
 * @package     stubbles
 * @subpackage  ipo_interceptors
 */
interface stubInterceptorInitializer extends stubInitializer
{
    /**
     * sets the descriptor that identifies the initializer
     *
     * @param  string  $descriptor
     */
    public function setDescriptor($descriptor);

    /**
     * returns the list of pre interceptors
     *
     * @return  array<stubPreInterceptor>
     */
    public function getPreInterceptors();

    /**
     * returns the list of post interceptors
     *
     * @return  array<stubPostInterceptor>
     */
    public function getPostInterceptors();
}
?>