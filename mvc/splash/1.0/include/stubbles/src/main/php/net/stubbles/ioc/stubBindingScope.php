<?php
/**
 * Interface for all scopes
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  ioc
 */
stubClassLoader::load('net::stubbles::ioc::stubInjectionProvider',
                      'net::stubbles::reflection::stubBaseReflectionClass'
);
/**
 * Interface for all scopes
 *
 * @package     stubbles
 * @subpackage  ioc
 */
interface stubBindingScope extends stubObject
{
    /**
     * returns the provider that has or creates the required instance
     *
     * @param   stubBaseReflectionClass  $type      type of the object
     * @param   stubBaseReflectionClass  $impl      concrete implementation
     * @param   stubInjectionProvider    $provider
     * @return  stubInjectionProvider
     */
    public function getProvider(stubBaseReflectionClass $type, stubBaseReflectionClass $impl, stubInjectionProvider $provider);
}
?>