<?php
/**
 * Tests for net::stubbles::websites::cache::stubAbstractWebsiteCacheFactory.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_cache_test
 */
stubClassLoader::load('net::stubbles::websites::cache::stubAbstractWebsiteCacheFactory');
/**
 * Tests for net::stubbles::websites::cache::stubAbstractWebsiteCacheFactory.
 *
 * @package     stubbles
 * @subpackage  websites_cache_test
 * @group       websites
 * @group       websites_cache
 */
class stubAbstractWebsiteCacheFactoryTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to be used for tests
     *
     * @var  stubAbstractWebsiteCacheFactory
     */
    protected $abstractWebsiteCacheFactory;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->abstractWebsiteCacheFactory = $this->getMock('stubAbstractWebsiteCacheFactory',
                                                            array('getWebsiteCache')
                                             );
    }

    /**
     * assure that a non-cachable processor is not altered
     *
     * @test
     */
    public function nonCachableProcessor()
    {
        $mockProcessor = $this->getMock('stubProcessor');
        $this->abstractWebsiteCacheFactory->expects($this->never())
                                          ->method('getWebsiteCache');
        $this->assertSame($mockProcessor, $this->abstractWebsiteCacheFactory->configure($mockProcessor));
    }

    /**
     * assure that a cachable processor does get a website cache
     *
     * @test
     */
    public function cachableProcessor()
    {
        $mockWebsiteCache  = $this->getMock('stubWebsiteCache');
        $cachableProcessor = $this->getMock('stubCachableProcessor');
        $this->abstractWebsiteCacheFactory->expects($this->once())
                                          ->method('getWebsiteCache')
                                          ->will($this->returnValue($mockWebsiteCache));
        $cachingProcessor = $this->abstractWebsiteCacheFactory->configure($cachableProcessor);
        $this->assertType('stubCachingProcessor', $cachingProcessor);
        $this->assertSame($cachableProcessor, $cachingProcessor->getProcessor());
    }
}
?>