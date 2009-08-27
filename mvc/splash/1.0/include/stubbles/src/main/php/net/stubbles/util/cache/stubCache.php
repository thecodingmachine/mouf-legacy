<?php
/**
 * Class for caching data.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_cache
 */
stubClassLoader::load('net::stubbles::lang::exceptions::stubRuntimeException',
                      'net::stubbles::util::cache::stubCacheContainer'
);
/**
 * Class for caching data.
 *
 * @static
 * @package     stubbles
 * @subpackage  util_cache
 */
class stubCache
{
    /**
     * a list of registered containers
     *
     * @var  array<string,stubCacheContainer>
     */
    protected static $containers = array();

    /**
     * factory method that returns a cache container with the given id
     *
     * @param   string              $id
     * @return  stubCacheContainer
     * @throws  stubRuntimeException
     */
    public static function factory($id)
    {
        if (isset(self::$containers[$id]) === true) {
            self::$containers[$id]->gc();
            return self::$containers[$id];
        }
        
        throw new stubRuntimeException('No cache container registered for id ' . $id);
    }

    /**
     * adds a cache container
     *
     * @param  stubCacheContainer  $container
     */
    public static function addContainer(stubCacheContainer $container)
    {
        self::$containers[$container->getId()] = $container;
    }

    /**
     * checks whether a container with the given id is known
     *
     * @param   string  $id
     * @return  bool
     */
    public static function has($id)
    {
        return isset(self::$containers[$id]);
    }

    /**
     * returns a list of all container ids
     *
     * @return  array<string>
     */
    public static function getContainerIds()
    {
        return array_keys(self::$containers);
    }

    /**
     * removes container with given id
     *
     * @param  string  $id
     */
    public static function removeContainer($id)
    {
        if (isset(self::$containers[$id]) === true) {
            unset(self::$containers[$id]);
        }
    }
}
?>