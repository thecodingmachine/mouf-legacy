<?php
/**
 * Default cache implementation for websites.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_cache
 */
stubClassLoader::load('net::stubbles::websites::cache::stubAbstractWebsiteCache');
/**
 * Default cache implementation for websites.
 * 
 * @package     stubbles
 * @subpackage  websites_cache
 */
class stubDefaultWebsiteCache extends stubAbstractWebsiteCache
{
    /**
     * list of variables for the cache
     *
     * @var  array<string,scalar>
     */
    protected $cacheVars  = array();
    /**
     * list of files used when generating the page
     *
     * @var  array<string>
     */
    protected $usedFiles  = array();
    /**
     * switch whether to check if any of the used files is newer then the cached file
     *
     * @var  bool
     */
    protected $checkFiles = false;

    /**
     * constructor
     *
     * @param  stubCacheContainer  $cache
     */
    public function __construct(stubCacheContainer $cache)
    {
        $this->cache = $cache;
    }

    /**
     * sets whether used files should be checked if they are newer then the cached file
     *
     * @param  bool  $checkFiles
     */
    public function setCheckFiles($checkFiles)
    {
        $this->checkFiles = $checkFiles;
    }

    /**
     * adds a variable to the list of cache variables
     *
     * @param  string  $name
     * @param  scalar  $value
     */
    public function addCacheVar($name, $value)
    {
        $this->cacheVars[$name] = $value;
    }

    /**
     * adds a list of variables to the list of cache variables
     *
     * @param  array<string,scalar>  $cacheVars
     */
    public function addCacheVars(array $cacheVars)
    {
        foreach ($cacheVars as $name => $value) {
            $this->cacheVars[$name] = $value;
        }
    }

    /**
     * list of used files
     *
     * @param  string  $file
     */
    public function addUsedFile($file)
    {
        $this->usedFiles[$file] = $file;
    }

    /**
     * adds a list of files to the list of used files
     *
     * @param  array<string>  $files
     */
    public function addUsedFiles(array $files)
    {
        foreach ($files as $file) {
            $this->usedFiles[$file] = $file;
        }
    }

    /**
     * does the real retrieve
     *
     * @param   stubRequest   $request
     * @param   stubResponse  $response
     * @param   string        $cacheKey
     * @return  bool          true if successfully retrieved, else false
     */
    protected function doRetrieve(stubRequest $request, stubResponse $response, $cacheKey)
    {
        $response->write($this->cache->get($cacheKey));
        return true;
    }

    /**
     * does the real storage
     *
     * @param   stubRequest   $request
     * @param   stubResponse  $response
     * @param   string        $cacheKey
     * @return  bool          true if successfully stored, else false
     */
    protected function doStore(stubRequest $request, stubResponse $response, $cacheKey)
    {
        return (bool) $this->cache->put($cacheKey, $response->getData());
    }

    /**
     * returns true if used files check is enabled
     *
     * @return  bool
     */
    protected function isUsedFilesCheckEnabled()
    {
        return $this->checkFiles;
    }

    /**
     * returns the list of used files
     *
     * @return  array<string>
     */
    protected function getUsedFiles()
    {
        return $this->usedFiles;
    }

    /**
     * returns the list of cache variables
     *
     * @return  array<string,scalar>
     */
    protected function getCacheVars()
    {
        return $this->cacheVars;
    }
}
?>