<?php
/**
 * Default implementation of a website cache factory.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_cache
 */
stubClassLoader::load('net::stubbles::util::cache::stubCache',
                      'net::stubbles::websites::cache::stubAbstractWebsiteCacheFactory',
                      'net::stubbles::websites::cache::stubDefaultWebsiteCache',
                      'net::stubbles::websites::cache::stubGzipWebsiteCache'
);
/**
 * Default implementation of a website cache factory.
 *
 * The default implementation delivers a gzip website cache that gzips the
 * website contents. If this is not possible because the user agent does not
 * accept gzipped content it falls back to the default website cache
 * implementation.
 *
 * @package     stubbles
 * @subpackage  websites_cache
 */
class stubDefaultWebsiteCacheFactory extends stubAbstractWebsiteCacheFactory
{
    /**
     * id of the cache container to be used
     *
     * @var  string
     */
    protected $cacheContainerId;

    /**
     * constructor
     *
     * @param  string  $cacheContainerId  id of the cache container to be used
     */
    public function __construct($cacheContainerId)
    {
        $this->cacheContainerId = $cacheContainerId;
    }

    /**
     * helper method to retrieve the website cache instance
     *
     * @return  stubWebsiteCache
     */
    protected function getWebsiteCache()
    {
        return new stubGzipWebsiteCache(new stubDefaultWebsiteCache(stubCache::factory($this->cacheContainerId)));
    }
}
?>