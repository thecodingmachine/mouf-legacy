<?php
/**
 * Tests for net::stubbles::util::cache::stubCache.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_cache_test
 */
stubClassLoader::load('net::stubbles::util::cache::stubCache');
/**
 * Tests for net::stubbles::util::cache::stubCache.
 *
 * @package     stubbles
 * @subpackage  util_cache_test
 * @group       util_cache
 */
class stubCacheTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * assure that retrieving a non-existing container triggers an exception
     *
     * @test
     * @expectedException  stubRuntimeException
     */
    public function noContainer()
    {
        $this->assertFalse(stubCache::has('foo'));
        stubCache::factory('foo');
    }

    /**
     * assure that retrieving a container returns the correct one
     *
     * @test
     */
    public function withContainer()
    {
        $mockContainer = $this->getMock('stubCacheContainer');
        $mockContainer->expects($this->any())->method('getId')->will($this->returnValue('foo'));
        $mockContainer->expects($this->once())->method('gc');
        stubCache::addContainer($mockContainer);
        $this->assertTrue(stubCache::has('foo'));
        $this->assertFalse(stubCache::has('bar'));
        $fooCache = stubCache::factory('foo');
        $this->assertSame($mockContainer, $fooCache);
    }
}
?>