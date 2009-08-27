<?php
/**
 * A processor used in tests.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  test
 */
stubClassLoader::load('net::stubbles::websites::processors::stubPageBasedProcessor');
/**
 * A processor used in tests.
 *
 * @package     stubbles
 * @subpackage  test
 */
class FooPageBasedProcessor extends FooProcessor implements stubPageBasedProcessor
{
    /**
     * injected page factory instance
     *
     * @var  stubPageFactory
     */
    protected $pageFactory;

    /**
     * selects the page to display with help of the page factory
     *
     * @param  stubPageFactory  $pageFactory
     */
    public function selectPage(stubPageFactory $pageFactory)
    {
        $this->pageFactory = $pageFactory;
    }

    /**
     * returns the injected page factory instance
     *
     * @return  stubPageFactory
     */
    public function getPageFactory()
    {
        return $this->pageFactory;
    }
}
?>