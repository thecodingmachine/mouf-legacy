<?php
/**
 * Abstract base class for cache containers.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_cache
 */
stubClassLoader::load('net::stubbles::util::cache::stubCacheContainer');
/**
 * Abstract base class for cache containers.
 *
 * @package     stubbles
 * @subpackage  util_cache
 */
abstract class stubAbstractCacheContainer extends stubSerializableObject implements stubCacheContainer
{
    /**
     * the strategy used for decisions about caching
     *
     * @var  stubCacheStrategy
     */
    protected $strategy;
    /**
     * id of the container
     *
     * @var  string
     */
    protected $id;
    /**
     * unix timestamp when last run of garbage collection happened
     *
     * @var  int
     */
    protected $lastGcRun = null;

    /**
     * constructor
     *
     * @param  string             $id        id of the container
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * sets the id of the container
     *
     * @param  string  $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * returns the id of the container
     *
     * @return  string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * sets the strategy the container should use
     *
     * @param  stubCacheStrategy  $strategy
     */
    public function setStrategy(stubCacheStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * puts date into the cache
     *
     * Returns amount of cached bytes or false if caching failed.
     *
     * @param   string    $key   key under which the data should be stored
     * @param   string    $data  data that should be cached
     * @return  int|bool
     */
    public function put($key, $data)
    {
        if ($this->strategy->isCachable($this, $key, $data) == false) {
            return false;
        }
        
        return $this->doPut($key, $data);
    }

    /**
     * puts date into the cache
     *
     * Returns amount of cached bytes or false if caching failed.
     *
     * @param   string    $key   key under which the data should be stored
     * @param   string    $data  data that should be cached
     * @return  int|bool
     */
    protected abstract function doPut($key, $data);

    /**
     * checks whether data is cached under the given key
     *
     * @param   string  $key
     * @return  bool
     */
    public function has($key)
    {
        if ($this->strategy->isExpired($this, $key) == true) {
            return false;
        }
        
        return $this->doHas($key);
    }

    /**
     * checks whether data is cached under the given key
     *
     * @param   string  $key
     * @return  bool
     */
    protected abstract function doHas($key);

    /**
     * checks whether cache data is expired
     *
     * @param   string  $key   key under which the data is stored
     * @return  bool
     */
    public function isExpired($key)
    {
        return $this->strategy->isExpired($this, $key);
    }

    /**
     * fetches data from the cache
     * 
     * Returns null if no data is cached under the given key.
     *
     * @param   string  $key
     * @return  string
     */
    public function get($key)
    {
        if ($this->strategy->isExpired($this, $key) == true) {
            return null;
        }
        
        return $this->doGet($key);
    }

    /**
     * fetches data from the cache
     * 
     * Returns null if no data is cached under the given key.
     *
     * @param   string  $key
     * @return  string
     */
    protected abstract function doGet($key);

    /**
     * returns the allocated space of the data associated with $key in bytes
     *
     * @param   string  $key
     * @return  int
     */
    public function getSize($key)
    {
        if ($this->has($key) == true) {
            return $this->doGetSize($key);
        }
        
        return 0;
    }
    
    /**
     * returns the allocated space of the data associated with $key in bytes
     *
     * @param   string  $key
     * @return  int
     */
    protected abstract function doGetSize($key);

    /**
     * returns the unix timestamp of the last run of the garbage collection
     *
     * @return  int
     */
    public function lastGcRun()
    {
        return $this->lastGcRun;
    }

    /**
     * runs the garbage collection
     */
    public function gc()
    {
        if ($this->strategy->shouldRunGc($this) == true) {
            $this->doGc();
            $this->lastGcRun = time();
        }
    }

    /**
     * runs the garbage collection
     */
    protected abstract function doGc();
}
?>