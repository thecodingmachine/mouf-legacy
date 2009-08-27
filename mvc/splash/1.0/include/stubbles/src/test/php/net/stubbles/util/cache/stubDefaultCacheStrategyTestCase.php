<?php
/**
 * Tests for net::stubbles::util::cache::stubDefaultCacheStrategy.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_cache_test
 */
stubClassLoader::load('net::stubbles::util::cache::stubDefaultCacheStrategy');
/**
 * Tests for net::stubbles::util::cache::stubDefaultCacheStrategy.
 *
 * @package     stubbles
 * @subpackage  util_cache_test
 * @group       util_cache
 */
class stubDefaultCacheStrategyTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * assure that retrieving a non-existing container triggers an exception
     *
     * @test
     */
    public function isCachable()
    {
        $defaultCacheStrategy = new stubDefaultCacheStrategy(10, 2, 0);
        $mockContainer        = $this->getMock('stubCacheContainer');
        $mockContainer->expects($this->exactly(6))
                      ->method('getUsedSpace')
                      ->will($this->onConsecutiveCalls(1, 1, 1, 2, 2, 0));
        $mockContainer->expects($this->exactly(6))
                      ->method('getSize')
                      ->will($this->onConsecutiveCalls(0, 0, 1, 2, 0, 0));
        $this->assertTrue($defaultCacheStrategy->isCachable($mockContainer, 'a', 'a'));
        $this->assertFalse($defaultCacheStrategy->isCachable($mockContainer, 'a', 'ab'));
        $this->assertTrue($defaultCacheStrategy->isCachable($mockContainer, 'a', 'a'));
        $this->assertTrue($defaultCacheStrategy->isCachable($mockContainer, 'a', 'a'));
        $this->assertFalse($defaultCacheStrategy->isCachable($mockContainer, 'a', 'a'));
        $this->assertTrue($defaultCacheStrategy->isCachable($mockContainer, 'a', 'ab'));
        
        $defaultCacheStrategy = new stubDefaultCacheStrategy(10, -1, 0);
        $this->assertTrue($defaultCacheStrategy->isCachable($mockContainer, 'a', 'ab'));
    }

    /**
     * assure that retrieving a container returns the correct one
     *
     * @test
     */
    public function isExpired()
    {
        $defaultCacheStrategy = new stubDefaultCacheStrategy(10, 2, 0);
        $mockContainer        = $this->getMock('stubCacheContainer');
        $mockContainer->expects($this->exactly(3))
                      ->method('getLifeTime')
                      ->will($this->onConsecutiveCalls(9, 10, 11));
        
        $this->assertFalse($defaultCacheStrategy->isExpired($mockContainer, 'a'));
        $this->assertFalse($defaultCacheStrategy->isExpired($mockContainer, 'a'));
        $this->assertTrue($defaultCacheStrategy->isExpired($mockContainer, 'a'));
    }

    /**
     * assure that retrieving a container returns the correct one
     *
     * @test
     */
    public function shouldRunGc()
    {
        $defaultCacheStrategy = new stubDefaultCacheStrategy(10, 2, 0);
        $mockContainer        = $this->getMock('stubCacheContainer');
        $this->assertFalse($defaultCacheStrategy->shouldRunGc($mockContainer));
        $this->assertFalse($defaultCacheStrategy->shouldRunGc($mockContainer));
        
        $defaultCacheStrategy = new stubDefaultCacheStrategy(10, 2, 100);
        $this->assertTrue($defaultCacheStrategy->shouldRunGc($mockContainer));
        $this->assertTrue($defaultCacheStrategy->shouldRunGc($mockContainer));
    }
}
?>