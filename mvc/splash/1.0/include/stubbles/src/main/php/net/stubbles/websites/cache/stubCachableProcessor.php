<?php
/**
 * Interface for processors with cachable contents.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_cache
 */
stubClassLoader::load('net::stubbles::websites::cache::stubWebsiteCache',
                      'net::stubbles::websites::processors::stubProcessor'
);
/**
 * Interface for processors with cachable contents.
 *
 * @package     stubbles
 * @subpackage  websites_cache
 */
interface stubCachableProcessor extends stubProcessor
{
    /**
     * adds the cache variables for the current request and returns whether
     * response is cachable or not
     *
     * @param   stubWebsiteCache  $cache
     * @return  bool
     */
    public function addCacheVars(stubWebsiteCache $cache);

    /**
     * returns the name of the current page
     *
     * Non-page-based processors should return another unique identifier for
     * the current request if they want to implement this interface.
     *
     * @return  string
     */
    public function getPageName();
}
?>