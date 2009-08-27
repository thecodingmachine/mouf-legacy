<?php
/**
 * Integration test for cache.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  test_integration
 */
stubClassLoader::load('net::stubbles::util::cache::stubCacheXJConfInitializer');
/**
 * Integration test for cache.
 *
 * @package     stubbles
 * @subpackage  test_integration
 * @group       integration
 */
class CacheTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * clean up test environment
     */
    public function setUp()
    {
        foreach (stubCache::getContainerIds() as $id) {
            stubCache::removeContainer($id);
        }
    }

    /**
     * helper method
     *
     * @return  stubFileCacheContainer
     */
    protected function getCacheContainer()
    {
        $cacheInitializer = new stubCacheXJConfInitializer();
        $cacheInitializer->init();
        $this->assertTrue(stubCache::has('default'));
        return stubCache::factory('default');
    }

    /**
     * assure that creating the cache instances works correct
     * @test
     */
    public function cacheInitializer()
    {
        $this->assertType('stubFileCacheContainer', $this->getCacheContainer());
        // cached
        $this->assertType('stubFileCacheContainer', $this->getCacheContainer());
    }
}
?>