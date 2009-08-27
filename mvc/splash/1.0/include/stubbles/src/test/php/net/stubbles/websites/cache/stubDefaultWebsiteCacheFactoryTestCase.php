<?php
/**
 * Tests for net::stubbles::websites::cache::stubDefaultWebsiteCacheFactory.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_cache_test
 */
stubClassLoader::load('net::stubbles::websites::cache::stubDefaultWebsiteCacheFactory');
/**
 * Helper class for the test.
 *
 * @package     stubbles
 * @subpackage  websites_cache_test
 */
class TeststubDefaultWebsiteCacheFactory extends stubDefaultWebsiteCacheFactory
{
    /**
     * helper method to retrieve the website cache instance
     *
     * @return  stubWebsiteCache
     */
    public function callGetWebsiteCache()
    {
        return $this->getWebsiteCache();
    }
}
/**
 * Tests for net::stubbles::websites::cache::stubDefaultWebsiteCacheFactory.
 *
 * @package     stubbles
 * @subpackage  websites_cache_test
 * @group       websites
 * @group       websites_cache
 */
class stubDefaultWebsiteCacheFactoryTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to be used for tests
     *
     * @var  stubDefaultWebsiteCacheFactory
     */
    protected $defaultWebsiteCacheFactory;
    
    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->defaultWebsiteCacheFactory = new TeststubDefaultWebsiteCacheFactory(__CLASS__);
    }

    /**
     * assure that a cachable processor does get a website cache
     *
     * @test
     * @expectedException  stubRuntimeException
     */
    public function cachableProcessorWithoutConfiguredCache()
    {
        $this->defaultWebsiteCacheFactory->callGetWebsiteCache();
    }

    /**
     * assure that a cachable processor does get a website cache
     *
     * @test
     */
    public function cachableProcessorWithConfiguredCache()
    {
        $mockCacheContainer = $this->getMock('stubCacheContainer');
        $mockCacheContainer->expects($this->any())->method('getId')->will($this->returnValue(__CLASS__));
        stubCache::addContainer($mockCacheContainer);
        $this->defaultWebsiteCacheFactory->callGetWebsiteCache();
    }
}
?>