<?php
/**
 * Default caching strategy.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_cache
 */
stubClassLoader::load('net::stubbles::util::cache::stubCacheContainer');
/**
 * Default caching strategy.
 *
 * @package     stubbles
 * @subpackage  util_cache
 */
class stubDefaultCacheStrategy extends stubSerializableObject implements stubCacheStrategy
{
    /**
     * time to live for single cached data
     *
     * @var  int
     */
    protected $ttl;
    /**
     * maximum size of cache
     * 
     * To allow an infinite size set this to -1.
     *
     * @var  string
     */
    protected $maxSize;
    /**
     * probability of a garbage collection run
     * 
     * Should be a value between 0 and 100 where 0 means never and 100 means always.
     *
     * @var  int
     */
    protected $gcProbability;

    /**
     * constructor
     *
     * @param  int     $ttl            time to live for single cached data
     * @param  string  $maxSize        maximum size of cache, -1 for infinite size
     * @param  int     $gcProbability  probability of a garbace collection run
     */
    public function __construct($ttl, $maxSize, $gcProbability)
    {
        $this->ttl           = $ttl;
        $this->maxSize       = $maxSize;
        $this->gcProbability = $gcProbability;
    }

    /**
     * checks whether an item is cacheable or not
     *
     * @param   stubCacheContainer  $container  the container to cache the data in
     * @param   string              $key        the key to cache the data under
     * @param   string              $data       data to cache
     * @return  bool
     */
    public function isCachable(stubCacheContainer $container, $key, $data)
    {
        if (-1 == $this->maxSize) {
            return true;
        }
        
        if (($container->getUsedSpace() + strlen($data) - $container->getSize($key)) > $this->maxSize) {
            return false;
        }
        
        return true;
    }

    /**
     * checks whether a cached item is expired
     *
     * @param   stubCacheContainer  $container  the container that contains the cached data
     * @param   string              $key        the key where the data is cached under
     * @return  bool
     */
    public function isExpired(stubCacheContainer $container, $key)
    {
        return ($container->getLifeTime($key) > $this->ttl);
    }

    /**
     * checks whether the garbage collection should be run
     *
     * @param stubCacheContainer $container
     * @return  bool
     */
    public function shouldRunGc(stubCacheContainer $container)
    {
        if (rand(1, 100) < $this->gcProbability) {
            return true;
        }
        
        return false;
    }
}
?>