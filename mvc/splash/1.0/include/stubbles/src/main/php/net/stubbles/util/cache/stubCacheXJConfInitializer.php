<?php
/**
 * Interface for initializing the cache.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_cache
 */
stubClassLoader::load('net::stubbles::util::cache::stubCacheInitializer',
                      'net::stubbles::util::cache::stubCache',
                      'net::stubbles::util::xjconf::xjconf'
);
/**
 * Interface for initializing the cache.
 *
 * @package     stubbles
 * @subpackage  util_cache
 */
class stubCacheXJConfInitializer extends stubXJConfAbstractInitializer implements stubCacheInitializer
{
    /**
     * returns the descriptor that identifies the initializer
     *
     * @param   string  $type  type of descriptor: config or definition
     * @return  string
     */
    public function getDescriptor($type)
    {
        return 'cache';
    }

    /**
     * returns the data to cache
     *
     * @return  array
     */
    public function getCacheData()
    {
        $cacheData = array();
        foreach (stubCache::getContainerIds() as $containerId) {
            $cacheData[$containerId] = stubCache::factory($containerId)->getSerialized();
        }
        
        return $cacheData;
    }

    /**
     * sets the data from the cache
     *
     * @param  array  $cacheData
     */
    public function setCacheData(array $cacheData)
    {
        foreach ($cacheData as $serialized) {
            stubCache::addContainer($serialized->getUnserialized());
        }
    }

    /**
     * will be called in case the stubXJConfProxy did not found the data in the
     * cache and the initializer has to load values from the facade
     *
     * @param  stubXJConfFacade  $xjconf
     */
    public function loadData(stubXJConfFacade $xjconf)
    {
        // intentionally empty
    }
}
?>