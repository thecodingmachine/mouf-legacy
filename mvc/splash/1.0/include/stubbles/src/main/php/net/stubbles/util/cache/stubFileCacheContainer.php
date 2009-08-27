<?php
/**
 * Cache containers using files.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_cache
 */
stubClassLoader::load('net::stubbles::lang::stubRegistry',
                      'net::stubbles::util::cache::stubAbstractCacheContainer',
                      'net::stubbles::util::cache::stubCacheContainer'
);
/**
 * Cache containers using files.
 *
 * @package     stubbles
 * @subpackage  util_cache
 */
class stubFileCacheContainer extends stubAbstractCacheContainer implements stubCacheContainer
{
    /**
     * the directory to store the cache files in
     *
     * @var  string
     */
    protected $cacheDirectory = './';
    /**
     * list of keys
     *
     * @var  array<string,string>
     */
    protected $keys           = null;
    /**
     * size of cache entries
     *
     * @var  array<string,int>
     */
    protected $size           = null;

    /**
     * sets the directory to store the cache files in
     *
     * @param  string  $directory
     */
    public function setCacheDirectory($directory)
    {
        $this->cacheDirectory = $directory . DIRECTORY_SEPARATOR . $this->id;
        if (file_exists($this->cacheDirectory) === false) {
            mkdir($this->cacheDirectory, stubRegistry::getConfig('net.stubbles.filemode', 0700), true);
        }
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
    protected function doPut($key, $data)
    {
        $bytes = file_put_contents($this->cacheDirectory . DIRECTORY_SEPARATOR . $this->escapeKey($key) . '.cache', $data);
        if (false === $bytes) {
            return false;
        }
        
        if (null !== $this->keys) {
            $this->keys[$key] = $key;
        }
        
        if (null !== $this->size) {
            $this->size[$key] = $bytes;
        }
        
        return $bytes;
    }

    /**
     * checks whether data is cached under the given key
     *
     * @param   string  $key
     * @return  bool
     */
    protected function doHas($key)
    {
        return file_exists($this->cacheDirectory . DIRECTORY_SEPARATOR . $this->escapeKey($key) . '.cache');
    }

    /**
     * fetches data from the cache
     * 
     * Returns null if no data is cached under the given key.
     *
     * @param   string  $key
     * @return  string
     */
    protected function doGet($key)
    {
        if ($this->doHas($key) == true) {
            return file_get_contents($this->cacheDirectory . DIRECTORY_SEPARATOR . $this->escapeKey($key) . '.cache');
        }
        
        return null;
    }

    /**
     * returns the time in seconds how long the data associated with $key is cached
     *
     * @param   string  $key
     * @return  int
     */
    public function getLifeTime($key)
    {
        if ($this->doHas($key) == true) {
            return (time() - filemtime($this->cacheDirectory . DIRECTORY_SEPARATOR . $this->escapeKey($key) . '.cache'));
        }
        
        return 0;
    }

    /**
     * returns the timestamp when data associated with $key is cached
     *
     * @param   string  $key
     * @return  int
     */
    public function getStoreTime($key)
    {
        if ($this->doHas($key) == true) {
            return filemtime($this->cacheDirectory . DIRECTORY_SEPARATOR . $this->escapeKey($key) . '.cache');
        }
        
        return 0;
    }

    /**
     * returns the allocated space of the data associated with $key in bytes
     *
     * @param   string  $key
     * @return  int
     */
    protected function doGetSize($key)
    {
        if (null !== $this->size) {
            return $this->size[$key];
        }
        
        return filesize($this->cacheDirectory . DIRECTORY_SEPARATOR . $this->escapeKey($key) . '.cache');
    }

    /**
     * returns the amount of bytes the cache data requires
     *
     * @return  int
     */
    public function getUsedSpace()
    {
        if (null === $this->size) {
            $this->size = array();
            $dirIt      = new DirectoryIterator($this->cacheDirectory);
            foreach ($dirIt as $file) {
                if ($file->isDot() == true || $file->isDir() == true) {
                    continue;
                }
                
                $key              = str_replace('.cache', '', $file->getFilename());
                $this->size[$key] = filesize($this->cacheDirectory . DIRECTORY_SEPARATOR . $key . '.cache');
            }
        }
        
        return array_sum($this->size);
    }

    /**
     * returns a list of all keys that are stored in the cache
     *
     * @return  array<string>
     */
    public function getKeys()
    {
        if (null === $this->keys) {
            $this->keys  = array();
            $dirIt = new DirectoryIterator($this->cacheDirectory);
            foreach ($dirIt as $file) {
                if ($file->isDot() == true || $file->isDir() == true) {
                    continue;
                }
                
                $key = str_replace('.cache', '', $file->getFilename());
                if ($this->strategy->isExpired($this, $key) == true) {
                    continue;
                }
                
                $this->keys[$key] = $key;
            }
        } else {
            foreach ($this->keys as $key) {
                if ($this->strategy->isExpired($this, $key) == true) {
                    unset($this->keys[$key]);
                    if (null !== $this->size) {
                        unset($this->size[$key]);
                    }
                }
            }
        }
        
        return $this->keys;
    }

    /**
     * runs the garbage collection
     */
    protected function doGc()
    {
        $dirIt = new DirectoryIterator($this->cacheDirectory);
        foreach ($dirIt as $file) {
            if ($file->isDot() == true || $file->isDir() == true) {
                continue;
            }
            
            $key = str_replace('.cache', '', $file->getFilename());
            if ($this->strategy->isExpired($this, $key) == true) {
                unlink($this->cacheDirectory . DIRECTORY_SEPARATOR . $key . '.cache');
                if (null !== $this->size) {
                    unset($this->size[$key]);
                }
            }
        }
    }

    /**
     * escapes the cache key
     *
     * @param   string  $key
     * @return  string
     */
    protected function escapeKey($key)
    {
        return str_replace(DIRECTORY_SEPARATOR, '', $key);
    }
}
?>