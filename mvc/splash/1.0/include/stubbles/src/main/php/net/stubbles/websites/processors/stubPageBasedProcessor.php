<?php
/**
 * Interface for page-based processors.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_processors
 */
stubClassLoader::load('net::stubbles::websites::stubPageFactory',
                      'net::stubbles::websites::processors::stubProcessor'
);
/**
 * Interface for page-based processors.
 *
 * @package     stubbles
 * @subpackage  websites_processors
 */
interface stubPageBasedProcessor extends stubProcessor
{
    /**
     * selects the page to display with help of the page factory
     *
     * @param  stubPageFactory  $pageFactory
     */
    public function selectPage(stubPageFactory $pageFactory);
}
?>