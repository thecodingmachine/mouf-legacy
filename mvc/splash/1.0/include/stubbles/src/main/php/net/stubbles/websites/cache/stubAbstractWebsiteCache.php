<?php
/**
 * Abstract base cache implementation for websites.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_cache
 */
stubClassLoader::load('net::stubbles::lang::stubMode',
                      'net::stubbles::util::cache::stubCache',
                      'net::stubbles::util::log::log',
                      'net::stubbles::websites::cache::stubWebsiteCache'
);
/**
 * Abstract base cache implementation for websites.
 *
 * @package     stubbles
 * @subpackage  websites_cache
 */
abstract class stubAbstractWebsiteCache extends stubBaseObject implements stubWebsiteCache
{
    /**
     * reason why cache is missing
     *
     * @var  string
     */
    protected $missReason = '';
    /**
     * the real cache
     *
     * @var  stubCacheContainer
     */
    protected $cache;

    /**
     * returns the cache container used by the implementation
     *
     * @return  stubCacheContainer
     */
    public function getCacheContainer()
    {
        return $this->cache;
    }

    /**
     * retrieves data from cache and puts it into response
     *
     * @param   stubRequest   $request
     * @param   stubResponse  $response
     * @param   string        $pageName  name of the page to be cached
     * @return  bool          true if data was retrieved from cache, else false
     */
    public function retrieve(stubRequest $request, stubResponse $response, $pageName)
    {
        $cacheKey = $this->generateCacheKey($pageName);
        if ($this->isCached($cacheKey) === false) {
            $this->log($pageName, $cacheKey, stubWebsiteCache::MISS);
            return false;
        }
        
        if (stubMode::$CURRENT->name() !== 'PROD') {
            $response->addHeader('X-Cached', $this->getClassName());
        }
        
        if ($this->doRetrieve($request, $response, $cacheKey) === true) {
            $this->log($pageName, $cacheKey, stubWebsiteCache::HIT);
            return true;
        }
        
        $this->log($pageName, $cacheKey, stubWebsiteCache::MISS);
        return false;
    }

    /**
     * does the real retrieve
     *
     * @param   stubRequest   $request
     * @param   stubResponse  $response
     * @param   string        $cacheKey
     * @return  bool          true if data was retrieved from cache, else false
     */
    protected abstract function doRetrieve(stubRequest $request, stubResponse $response, $cacheKey);

    /**
     * stores the data from the response in cche
     *
     * @param   stubRequest   $request
     * @param   stubResponse  $response
     * @param   string        $pageName  name of the page to be cached
     * @return  bool          true if successfully stored, else false
     */
    public function store(stubRequest $request, stubResponse $response, $pageName)
    {
        if (stubMode::$CURRENT->isCacheEnabled() === false) {
            return false;
        }
        
        return $this->doStore($request, $response, $this->generateCacheKey($pageName));
    }

    /**
     * does the real storage
     *
     * @param   stubRequest   $request
     * @param   stubResponse  $response
     * @param   string        $cacheKey
     * @return  bool          true if successfully stored, else false
     */
    protected abstract function doStore(stubRequest $request, stubResponse $response, $cacheKey);

    /**
     * checks whether data is cached or not
     *
     * @param   string  $cacheKey
     * @return  bool
     */
    protected function isCached($cacheKey)
    {
        if (stubMode::$CURRENT->isCacheEnabled() === false) {
            $this->missReason = 'disabled';
            return false;
        }
        
        if ($this->cache->has($cacheKey) === false) {
            $this->missReason = 'no cache file';
            return false;
        }
        
        if (false === $this->isUsedFilesCheckEnabled()) {
            return true;
        }
        
        $cacheTime = $this->cache->getStoreTime($cacheKey);
        clearstatcache();
        foreach ($this->getUsedFiles() as $fileName) {
            if (filemtime($fileName) > $cacheTime) {
                $this->missReason = $fileName . ' is newer';
                return false;
            }
        }
        
        return true;
    }

    /**
     * returns true if used files check is enabled
     *
     * @return  bool
     */
    protected abstract function isUsedFilesCheckEnabled();

    /**
     * returns the list of used files
     *
     * @return  array<string>
     */
    protected abstract function getUsedFiles();

    /**
     * helper method to log cache acticity
     *
     * @param  string  $page      name of page
     * @param  string  $cacheKey  key for cache data
     * @param  string  $type      'hit' or 'miss'
     */
    protected function log($page, $cacheKey, $type)
    {
        $logData  = stubLogDataFactory::create('cache', stubLogger::LEVEL_INFO);
        $logData->addData($page);
        $logData->addData($type);
        $logData->addData($this->getClassName());
        $logData->addData($this->missReason);
        $logData->addData($cacheKey);
        stubLogger::logToAll($logData);
    }

    /**
     * generates the cache key from given list of cache variables
     *
     * @param   string  $page       name of the page to be
     * @return  string
     */
    protected function generateCacheKey($page)
    {
        $baseKey = $page . '?';
        foreach ($this->getCacheVars() as $name => $value) {
            $baseKey .= '&' . $name . '=' . $value;
        }
        
        return md5($baseKey);
    }

    /**
     * returns the list of cache variables
     *
     * @return  array<string,scalar>
     */
    protected abstract function getCacheVars();
}
?>