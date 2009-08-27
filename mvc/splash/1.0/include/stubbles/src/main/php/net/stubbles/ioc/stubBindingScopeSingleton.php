<?php
/**
 * Scope for singletons
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  ioc
 */
stubClassLoader::load('net::stubbles::ioc::stubBindingScope',
                      'net::stubbles::ioc::stubValueInjectionProvider'
);
/**
 * Scope for singletons
 *
 * @package     stubbles
 * @subpackage  ioc
 */
class stubBindingScopeSingleton extends stubBaseObject implements stubBindingScope
{
    /**
     * instances in this scope
     *
     * @var  array<string,stubValueInjectionProvider>
     */
    protected $instances = array();

    /**
     * returns the provider that has or creates the required instance
     *
     * @param   stubBaseReflectionClass  $type      type of the object
     * @param   stubBaseReflectionClass  $impl      concrete implementation
     * @param   stubInjectionProvider    $provider
     * @return  stubInjectionProvider
     */
    public function getProvider(stubBaseReflectionClass $type, stubBaseReflectionClass $impl, stubInjectionProvider $provider)
    {
        $key = $impl->getName();
        if (isset($this->instances[$key]) === false) {
            $this->instances[$key] = new stubValueInjectionProvider($provider->get($type));
        }
        
        return $this->instances[$key];
    }
}
?>