<?php
/**
 * Interface for website cache factories.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_cache
 */
stubClassLoader::load('net::stubbles::websites::processors::stubProcessor');
/**
 * Interface for website cache factories.
 *
 * @package     stubbles
 * @subpackage  websites_cache
 */
interface stubWebsiteCacheFactory extends stubObject
{
    /**
     * configures the processor with a website cache factory and returns the
     * configured processor
     *
     * @param   stubProcessor  $processor
     * @return  stubProcessor
     */
    public function configure(stubProcessor $processor);
}
?>