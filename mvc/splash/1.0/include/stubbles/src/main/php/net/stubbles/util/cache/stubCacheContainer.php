<?php
/**
 * Interface for cache containers.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_cache
 */
stubClassLoader::load('net::stubbles::util::cache::stubCacheStrategy');
/**
 * Interface for cache containers.
 *
 * @package     stubbles
 * @subpackage  util_cache
 */
interface stubCacheContainer extends stubObject, stubSerializable
{
    /**
     * sets the id of the container
     *
     * @param  string  $id
     */
    public function setId($id);

    /**
     * returns the id of the container
     *
     * @return  string
     */
    public function getId();

    /**
     * sets the strategy the container should use
     *
     * @param  stubCacheStrategy  $strategy
     */
    public function setStrategy(stubCacheStrategy $strategy);

    /**
     * puts date into the cache
     *
     * Returns amount of cached bytes or false if caching failed.
     *
     * @param   string    $key   key under which the data should be stored
     * @param   string    $data  data that should be cached
     * @return  int|bool
     */
    public function put($key, $data);

    /**
     * checks whether data is cached under the given key
     *
     * @param   string  $key
     * @return  bool
     */
    public function has($key);

    /**
     * checks whether cache data is expired
     *
     * @param   string  $key   key under which the data is stored
     * @return  bool
     */
    public function isExpired($key);

    /**
     * fetches data from the cache
     * 
     * Returns null if no data is cached under the given key.
     *
     * @param   string  $key
     * @return  string
     */
    public function get($key);

    /**
     * returns the time in seconds how long the data associated with $key is cached
     *
     * @param   string  $key
     * @return  int
     */
    public function getLifeTime($key);

    /**
     * returns the timestamp when data associated with $key is cached
     *
     * @param   string  $key
     * @return  int
     */
    public function getStoreTime($key);

    /**
     * returns the allocated space of the data associated with $key in bytes
     *
     * @param   string  $key
     * @return  int
     */
    public function getSize($key);

    /**
     * returns the amount of bytes the cache data requires
     *
     * @return  int
     */
    public function getUsedSpace();

    /**
     * returns a list of all keys that are stored in the cache
     *
     * @return  array<string>
     */
    public function getKeys();

    /**
     * returns the unix timestamp of the last run of the garbage collection
     *
     * @return  int
     */
    public function lastGcRun();

    /**
     * runs the garbage collection
     */
    public function gc();
}
?>