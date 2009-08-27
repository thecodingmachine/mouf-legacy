<?php
/**
 * Helper class to retrieve binder instances from the registry.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ioc
 * @version     $Id: stubBinderRegistry.php 1930 2008-11-13 22:14:41Z mikey $
 */
stubClassLoader::load('net::stubbles::ioc::stubBinder',
                      'net::stubbles::lang::stubRegistry',
                      'net::stubbles::lang::exceptions::stubRuntimeException'
);
/**
 * Helper class to retrieve binder instances from the registry.
 *
 * @package     stubbles
 * @subpackage  ioc
 */
class stubBinderRegistry extends stubBaseObject
{
    /**
     * checks if a binder is available
     *
     * @return  bool
     */
    public static function hasInstance()
    {
        return (null !== stubRegistry::get(stubBinder::REGISTRY_KEY));
    }

    /**
     * retrieves binder instance from registry
     *
     * Throws a runtime exception if none is in registry.
     *
     * @return  stubBinder
     * @throws  stubRuntimeException
     */
    public static function get()
    {
        $binder = stubRegistry::get(stubBinder::REGISTRY_KEY);
        if (null === $binder) {
            throw new stubRuntimeException('No instance of net::stubbles::ioc::stubBinder in registry.');
        }
        
        return $binder;
    }

    /**
     * retrieves binder instance from factory or creates one if none is in registry
     *
     * @return  stubBinder
     */
    public static function create()
    {
        $binder = stubRegistry::get(stubBinder::REGISTRY_KEY);
        if (null === $binder) {
            $binder = new stubBinder();
            stubRegistry::set(stubBinder::REGISTRY_KEY, $binder);
        }
        
        return $binder;
    }
}
?>