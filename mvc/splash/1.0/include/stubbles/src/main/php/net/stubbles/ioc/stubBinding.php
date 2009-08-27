<?php
/**
 * Binding to bind an interface to an implementation
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  ioc
 */
stubClassLoader::load('net::stubbles::ioc::exceptions::stubBindingException');
/**
 * Binding to bind an interface to an implementation
 *
 * @package     stubbles
 * @subpackage  ioc
 */
interface stubBinding extends stubObject
{
    /**
     * set the name of the injection
     *
     * @param   string   $name
     * @return  stubBinding
     */
    public function named($name);

    /**
     * returns the provider that has or creates the required instance
     *
     * @return  stubInjectionProvider
     */
    public function getProvider();

    /**
     * creates a unique key for this binding
     *
     * @return  string
     */
    public function getKey();
}
?>