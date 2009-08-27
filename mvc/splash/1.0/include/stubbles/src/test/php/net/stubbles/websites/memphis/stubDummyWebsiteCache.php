<?php
/**
 * Cache for websites.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_cache
 */
stubClassLoader::load('net::stubbles::websites::cache::stubWebsiteCache');
/**
 * Cache for websites.
 * 
 * @package     stubbles
 * @subpackage  websites_cache
 */
class stubDummyWebsiteCache extends stubBaseObject implements stubWebsiteCache
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
     * @param  array<string,scalar>
     */
    public function addCacheVars(array $cacheVars)
    {
        foreach ($cacheVars as $name => $value) {
            $this->cacheVars[$name] = $value;
        }
    }

    /**
     * returns collected cache variables
     *
     * @return  array<string,scalar>
     */
    public function getCacheVars()
    {
        return $this->cacheVars;
    }

    /**
     * adds a file to the list of used files
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
     * @param  array<string>  $file
     */
    public function addUsedFiles(array $files)
    {
        foreach ($files as $file) {
            $this->usedFiles[$file] = $file;
        }
    }

    /**
     * returns a list of used files
     *
     * @return  array<string>
     */
    public function getUsedFiles()
    {
        return $this->usedFiles;
    }

    /**
     * returns the cache container used by the implementation
     *
     * @return  stubCacheContainer
     */
    public function getCacheContainer()
    {
        return null;
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
        return false;
    }

    /**
     * stores the data from the response in cche
     *
     * @param   stubRequest   $request
     * @param   stubResponse  $response
     * @param   string        $page      name of the page to be cached
     * @return  bool          true if successfully stored, else false
     */
    public function store(stubRequest $request, stubResponse $response, $page)
    {
        return false;
    }
}
?>