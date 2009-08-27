<?php
/**
 * Abstract base implementation of a website cache factory.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_cache
 */
stubClassLoader::load('net::stubbles::websites::cache::stubCachableProcessor',
                      'net::stubbles::websites::cache::stubCachingProcessor',
                      'net::stubbles::websites::cache::stubWebsiteCache',
                      'net::stubbles::websites::cache::stubWebsiteCacheFactory'
);
/**
 * Abstract base implementation of a website cache factory.
 *
 * @package     stubbles
 * @subpackage  websites_cache
 */
abstract class stubAbstractWebsiteCacheFactory extends stubBaseObject implements stubWebsiteCacheFactory
{
    /**
     * configures the processor with a website cache factory and returns the
     * configured processor
     *
     * @param   stubProcessor  $processor
     * @return  stubProcessor
     */
    public function configure(stubProcessor $processor)
    {
        if (($processor instanceof stubCachableProcessor) === false) {
            return $processor;
        }
        
        return new stubCachingProcessor($processor, $this->getWebsiteCache());
    }

    /**
     * helper method to retrieve the website cache instance
     *
     * @return  stubWebsiteCache
     */
    protected abstract function getWebsiteCache();
}
?>