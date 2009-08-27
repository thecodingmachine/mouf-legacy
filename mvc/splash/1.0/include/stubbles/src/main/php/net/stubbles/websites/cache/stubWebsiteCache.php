<?php
/**
 * Cache for websites.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_cache
 */
stubClassLoader::load('net::stubbles::ipo::request::stubRequest',
                      'net::stubbles::ipo::response::stubResponse'
);
/**
 * Cache for websites.
 * 
 * @package     stubbles
 * @subpackage  websites_cache
 */
interface stubWebsiteCache extends stubObject
{
    /**
     * cache hit
     */
    const HIT  = 'hit';
    /**
     * cache miss
     */
    const MISS = 'miss';

    /**
     * sets whether used files should be checked if they are newer then the cached file
     *
     * @param  bool  $checkFiles
     */
    public function setCheckFiles($checkFiles);

    /**
     * adds a variable to the list of cache variables
     *
     * @param  string  $name
     * @param  scalar  $value
     */
    public function addCacheVar($name, $value);

    /**
     * adds a list of variables to the list of cache variables
     *
     * @param  array<string,scalar>  $cacheVars
     */
    public function addCacheVars(array $cacheVars);

    /**
     * adds a file to the list of used files
     *
     * @param  string  $file
     */
    public function addUsedFile($file);

    /**
     * adds a list of files to the list of used files
     *
     * @param  array<string>  $files
     */
    public function addUsedFiles(array $files);

    /**
     * returns the cache container used by the implementation
     *
     * @return  stubCacheContainer
     */
    public function getCacheContainer();

    /**
     * retrieves data from cache and puts it into response
     *
     * @param   stubRequest   $request
     * @param   stubResponse  $response
     * @param   string        $pageName  name of the page to be cached
     * @return  bool          true if data was retrieved from cache, else false
     */
    public function retrieve(stubRequest $request, stubResponse $response, $pageName);

    /**
     * stores the data from the response in cche
     *
     * @param   stubRequest   $request
     * @param   stubResponse  $response
     * @param   string        $pageName  name of the page to be cached
     * @return  bool          true if successfully stored, else false
     */
    public function store(stubRequest $request, stubResponse $response, $pageName);
}
?>