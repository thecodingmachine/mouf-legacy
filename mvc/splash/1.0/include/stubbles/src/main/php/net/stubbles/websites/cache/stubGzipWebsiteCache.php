<?php
/**
 * Gzip cache implementation for websites.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_cache
 */
stubClassLoader::load('net::stubbles::ipo::request::validator::stubContainsValidator',
                      'net::stubbles::websites::cache::stubAbstractWebsiteCache'
);
/**
 * Gzip cache implementation for websites.
 * 
 * @package     stubbles
 * @subpackage  websites_cache
 */
class stubGzipWebsiteCache extends stubAbstractWebsiteCache
{
    /**
     * content header for response
     */
    const HEADER = "\x1f\x8b\x08\x00\x00\x00\x00\x00";
    /**
     * mime type: x-gzip
     */
    const X_GZIP = 'x-gzip';
    /**
     * mime type: gzip
     */
    const GZIP   = 'gzip';
    /**
     * decorated cache instance
     *
     * @var  stubWebsiteCache
     */
    protected $websiteCache;

    /**
     * constructor
     *
     * @param  stubWebsiteCache  $websiteCache
     */
    public function __construct(stubWebsiteCache $websiteCache)
    {
        $this->websiteCache = $websiteCache;
        $this->cache        = $this->websiteCache->getCacheContainer();
    }

    /**
     * sets whether used files should be checked if they are newer then the cached file
     *
     * @param  bool  $checkFiles
     */
    public function setCheckFiles($checkFiles)
    {
        $this->websiteCache->setCheckFiles($checkFiles);
    }

    /**
     * adds a variable to the list of cache variables
     *
     * @param  string  $name
     * @param  scalar  $value
     */
    public function addCacheVar($name, $value)
    {
        $this->websiteCache->addCacheVar($name, $value);
    }

    /**
     * adds a list of variables to the list of cache variables
     *
     * @param  array<string,scalar>  $cacheVars
     */
    public function addCacheVars(array $cacheVars)
    {
        $this->websiteCache->addCacheVars($cacheVars);
    }

    /**
     * list of used files
     *
     * @param  string  $file
     */
    public function addUsedFile($file)
    {
        $this->websiteCache->addUsedFile($file);
    }

    /**
     * adds a list of files to the list of used files
     *
     * @param  array<string>  $files
     */
    public function addUsedFiles(array $files)
    {
        $this->websiteCache->addUsedFiles($files);
    }

    /**
     * returns the cache container used by the implementation
     *
     * @return  stubCacheContainer
     */
    public function getCacheContainer()
    {
        return $this->websiteCache->getCacheContainer();
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
        if (parent::retrieve($request, $response, $pageName) === true) {
            return true;
        }
        
        return $this->websiteCache->retrieve($request, $response, $pageName);
    }

    /**
     * does the real retrieve
     *
     * @param   stubRequest   $request
     * @param   stubResponse  $response
     * @param   string        $cacheKey
     * @return  bool          true if data was retrieved from cache, else false
     */
    protected function doRetrieve(stubRequest $request, stubResponse $response, $cacheKey)
    {
        // we do not use gzipped content because we probably have to insert
        // the session id into the links of the document
        if ($request->acceptsCookies() === false) {
            $this->missReason = 'user agent does not accept cookies';
            return false;
        }
        
        $compression = $this->getCompression($request);
        if (null === $compression) {
            $this->missReason = 'user agent does not accept compressed content';
            return false;
        }
        
        $response->addHeader('Content-Encoding', $compression);
        $response->write(self::HEADER);
        $response->write($this->websiteCache->getCacheContainer()->get($cacheKey));
        return true;
    }

    /**
     * helper method to detect the supported compression
     *
     * If null is returned the user agent does not support compression.
     *
     * @param   stubRequest  $request
     * @return  string
     */
    protected function getCompression(stubRequest $request)
    {
        if ($request->validateValue(new stubContainsValidator(self::X_GZIP), 'HTTP_ACCEPT_ENCODING', stubRequest::SOURCE_HEADER) === true) {
            return self::X_GZIP;
        } elseif ($request->validateValue(new stubContainsValidator(self::GZIP), 'HTTP_ACCEPT_ENCODING', stubRequest::SOURCE_HEADER) === true) {
            return self::GZIP;
        }
        
        return null;
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
        parent::store($request, $response, $page);
        return $this->websiteCache->store($request, $response, $page);
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
        $data  = $response->getData();
        $data  = str_replace('$SID', '', $data);
        $data  = str_replace('$SESSION_NAME', '', $data);
        $data  = str_replace('$SESSION_ID', '', $data);
        $size  = strlen($data);
        $crc32 = crc32($data);
        $data  = gzcompress($data, 9);
        $data  = substr($data, 0, strlen($data) - 4);
        $data .= $this->convert2Gzip($crc32) . $this->convert2Gzip($size);
        return (bool) $this->websiteCache->getCacheContainer()->put($cacheKey, $data);
    }

    /**
     * returns gzip-encoded value
     *
     * @param   int    $value
     * @return  string
     */
    protected function convert2Gzip($value)
    {
        $return = '';
        for ($i = 0; $i < 4; $i++) {
            $return .= chr($value % 256);
            $value   = floor($value / 256);
        }
        
        return $return;
    }

    /**
     * returns true if used files check is enabled
     *
     * @return  bool
     */
    protected function isUsedFilesCheckEnabled()
    {
        return $this->websiteCache->isUsedFilesCheckEnabled();
    }

    /**
     * returns the list of used files
     *
     * @return  array<string>
     */
    protected function getUsedFiles()
    {
        return $this->websiteCache->getUsedFiles();
    }

    /**
     * generates the cache key from given list of cache keys
     *
     * @param   string  $page       name of the page to be cached
     * @return  string
     */
    protected function generateCacheKey($page)
    {
        return parent::generateCacheKey($page) . '.gz';
    }

    /**
     * returns the list of cache variables
     *
     * @return  array<string,scalar>
     */
    protected function getCacheVars()
    {
        return $this->websiteCache->getCacheVars();
    }
}
?>