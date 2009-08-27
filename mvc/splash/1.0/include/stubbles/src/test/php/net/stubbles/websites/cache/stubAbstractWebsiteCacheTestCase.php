<?php
/**
 * Tests for net::stubbles::websites::cache::stubAbstractWebsiteCache.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_cache_test
 */
stubClassLoader::load('net::stubbles::util::log::log',
                      'net::stubbles::util::log::stubMemoryLogAppender',
                      'net::stubbles::websites::cache::stubAbstractWebsiteCache'
);
/**
 * Helper class for unit test to set the cache container to be used.
 *
 * @package     stubbles
 * @subpackage  websites_cache_test
 */
abstract class TeststubAbstractWebsiteCache extends stubAbstractWebsiteCache
{
    /**
     * sets the cache container to be used
     *
     * @param  stubCacheContainer  $cache
     */
    public function setCacheContainer(stubCacheContainer $cache)
    {
        $this->cache = $cache;
    }

    /**
     * sets the reason why the cache entry was missed
     *
     * @param  string  $missReason
     */ 
    public function setMissReason($missReason)
    {
        $this->missReason = $missReason;
    }

    /**
     * returns the reason why the cache entry was missed
     *
     * @return  string
     */
    public function getMissReason()
    {
        return $this->missReason;
    }

    /**
     * helper method to access log cache acticity
     *
     * @param  string  $page      name of page
     * @param  string  $cacheKey  key for cache data
     * @param  string  $type      'hit' or 'miss'
     */
    public function callLog($page, $cacheKey, $type)
    {
        parent::log($page, $cacheKey, $type);
    }
}
/**
 * Tests for net::stubbles::websites::cache::stubAbstractWebsiteCache.
 *
 * @package     stubbles
 * @subpackage  websites_cache_test
 * @group       websites
 * @group       websites_cache
 */
class stubAbstractWebsiteCacheTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to be used for tests
     *
     * @var  TeststubAbstractWebsiteCache
     */
    protected $abstractWebsiteCache;
    /**
     * mocked cache container instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockCacheContainer;
    /**
     * mocked request instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockRequest;
    /**
     * mocked response instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockResponse;
 
    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->abstractWebsiteCache = $this->getMock('TeststubAbstractWebsiteCache', array('doRetrieve',
                                                                                           'doStore',
                                                                                           'isUsedFilesCheckEnabled',
                                                                                           'getUsedFiles',
                                                                                           'log',
                                                                                           'getCacheVars',
                                                                                           'setCheckFiles',
                                                                                           'addCacheVar',
                                                                                           'addCacheVars',
                                                                                           'addUsedFile',
                                                                                           'addUsedFiles'
                                                                                     )
                                      );
        $this->mockCacheContainer   = $this->getMock('stubCacheContainer');
        $this->abstractWebsiteCache->setCacheContainer($this->mockCacheContainer);
        $this->abstractWebsiteCache->expects($this->any())
                              ->method('getCacheVars')
                              ->will($this->returnValue(array('foo' => 'bar')));
        $this->mockRequest  = $this->getMock('stubRequest');
        $this->mockResponse = $this->getMock('stubResponse');
        stubMode::setCurrent(stubMode::$PROD);
    }

    /**
     * assure that disabled caching leads to nothing set in the response
     *
     * @test
     */
    public function retrieveCachingDisabled()
    {
        stubMode::setCurrent(stubMode::$DEV);
        $this->abstractWebsiteCache->expects($this->once())
                                   ->method('log')
                                   ->with($this->equalTo('baz'), $this->anything(), $this->equalTo(stubWebsiteCache::MISS));
        $this->abstractWebsiteCache->expects($this->never())->method('doRetrieve');
        $this->mockResponse->expects($this->never())->method('addHeader');
        $this->mockCacheContainer->expects($this->never())->method('has');
        $this->assertFalse($this->abstractWebsiteCache->retrieve($this->mockRequest, $this->mockResponse, 'baz'));
        $this->assertEquals('disabled', $this->abstractWebsiteCache->getMissReason());
    }

    /**
     * assure that a missing cache entry leads to nothing set in the response
     *
     * @test
     */
    public function cachingEntryMissing()
    {
        $this->abstractWebsiteCache->expects($this->once())
                                   ->method('log')
                                   ->with($this->equalTo('baz'), $this->anything(), $this->equalTo(stubWebsiteCache::MISS));
        $this->abstractWebsiteCache->expects($this->never())->method('doRetrieve');
        $this->mockResponse->expects($this->never())->method('addHeader');
        $this->mockCacheContainer->expects($this->once())->method('has')->will($this->returnValue(false));
        $this->assertFalse($this->abstractWebsiteCache->retrieve($this->mockRequest,$this->mockResponse, 'baz'));
        $this->assertEquals('no cache file', $this->abstractWebsiteCache->getMissReason());
    }

    /**
     * assure that a failing to set the response works as expected
     *
     * @test
     */
    public function cachingEntryFoundAndFailedToSet()
    {
        $this->abstractWebsiteCache->expects($this->once())
                                   ->method('log')
                                   ->with($this->equalTo('baz'), $this->anything(), $this->equalTo(stubWebsiteCache::MISS));
        $this->abstractWebsiteCache->expects($this->once())
                                   ->method('doRetrieve')
                                   ->with($this->equalTo($this->mockRequest), $this->equalTo($this->mockResponse))
                                   ->will($this->returnValue(false));
        $this->abstractWebsiteCache->expects($this->once())->method('isUsedFilesCheckEnabled')->will($this->returnValue(false));
        $this->abstractWebsiteCache->expects($this->never())->method('getUsedFiles');
        $this->mockResponse->expects($this->never())->method('addHeader');
        $this->mockCacheContainer->expects($this->once())->method('has')->will($this->returnValue(true));
        $this->assertFalse($this->abstractWebsiteCache->retrieve($this->mockRequest, $this->mockResponse, 'baz'));
        $this->assertEquals('', $this->abstractWebsiteCache->getMissReason());
    }

    /**
     * assure that a response data is set
     *
     * @test
     */
    public function cachingEntryFound()
    {
        $this->abstractWebsiteCache->expects($this->once())
                                   ->method('log')
                                   ->with($this->equalTo('baz'), $this->anything(), $this->equalTo(stubWebsiteCache::HIT));
        $this->abstractWebsiteCache->expects($this->once())
                                   ->method('doRetrieve')
                                   ->with($this->equalTo($this->mockRequest), $this->equalTo($this->mockResponse))
                                   ->will($this->returnValue(true));
        $this->abstractWebsiteCache->expects($this->once())->method('isUsedFilesCheckEnabled')->will($this->returnValue(false));
        $this->abstractWebsiteCache->expects($this->never())->method('getUsedFiles');
        $this->mockResponse->expects($this->never())->method('addHeader');
        $this->mockCacheContainer->expects($this->once())->method('has')->will($this->returnValue(true));
        $this->assertTrue($this->abstractWebsiteCache->retrieve($this->mockRequest, $this->mockResponse, 'baz'));
        $this->assertEquals('', $this->abstractWebsiteCache->getMissReason());
    }

    /**
     * assure that a response data is set
     *
     * @test
     */
    public function cachingEntryFoundTestMode()
    {
        stubMode::setCurrent(stubMode::$TEST);
         $this->abstractWebsiteCache->expects($this->once())
                                   ->method('log')
                                   ->with($this->equalTo('baz'), $this->anything(), $this->equalTo(stubWebsiteCache::HIT));
        $this->abstractWebsiteCache->expects($this->once())
                                   ->method('doRetrieve')
                                   ->with($this->equalTo($this->mockRequest), $this->equalTo($this->mockResponse))
                                   ->will($this->returnValue(true));
        $this->abstractWebsiteCache->expects($this->once())->method('isUsedFilesCheckEnabled')->will($this->returnValue(false));
        $this->abstractWebsiteCache->expects($this->never())->method('getUsedFiles');
        $this->mockResponse->expects($this->once())->method('addHeader');
        $this->mockCacheContainer->expects($this->once())->method('has')->will($this->returnValue(true));
        $this->assertTrue($this->abstractWebsiteCache->retrieve($this->mockRequest, $this->mockResponse, 'baz'));
        $this->assertEquals('', $this->abstractWebsiteCache->getMissReason());
    }

    /**
     * assure that disabled caching leads to no store
     *
     * @test
     */
    public function storeCachingDisabled()
    {
        stubMode::setCurrent(stubMode::$DEV);
        $this->abstractWebsiteCache->expects($this->never())->method('doStore');
        $this->assertFalse($this->abstractWebsiteCache->store($this->mockRequest, $this->mockResponse, 'baz'));
    }

    /**
     * assure that disabled caching leads to storing data
     *
     * @test
     */
    public function store()
    {
        $this->abstractWebsiteCache->expects($this->exactly(2))
                                   ->method('doStore')
                                   ->with($this->equalTo($this->mockRequest), $this->equalTo($this->mockResponse))
                                   ->will($this->onConsecutiveCalls(false, true));
        $this->assertFalse($this->abstractWebsiteCache->store($this->mockRequest, $this->mockResponse, 'baz'));
        $this->assertTrue($this->abstractWebsiteCache->store($this->mockRequest, $this->mockResponse, 'baz'));
    }

    /**
     * logging should log the correct stuff
     *
     * @test
     */
    public function logging()
    {
        $this->abstractWebsiteCache = $this->getMock('TeststubAbstractWebsiteCache', array('doRetrieve',
                                                                                           'doStore',
                                                                                           'isUsedFilesCheckEnabled',
                                                                                           'getUsedFiles',
                                                                                           'getCacheVars',
                                                                                           'setCheckFiles',
                                                                                           'addCacheVar',
                                                                                           'addCacheVars',
                                                                                           'addUsedFile',
                                                                                           'addUsedFiles',
                                                                                           'getClassName'
                                                                                     )
                                      );
        $this->abstractWebsiteCache->setMissReason('missReason');
        $this->abstractWebsiteCache->expects($this->once())->method('getClassName')->will($this->returnValue('MockWebsiteCache'));
        stubRegistry::setConfig(stubLogData::CLASS_REGISTRY_KEY, 'net::stubbles::util::log::stubBaseLogData');
        $logger      = stubLogger::getInstance(__CLASS__);
        $logAppender = new stubMemoryLogAppender();
        $logger->addLogAppender($logAppender);
        $this->abstractWebsiteCache->callLog('pageName', 'cacheKey', 'hit');
        $logData = $logAppender->getLogData();
        $this->assertEquals(1, count($logData));
        $this->assertEquals(1, count($logData['cache']));
        $this->assertEquals('cache', $logData['cache'][0]->getTarget());
        $this->assertEquals(stubLogger::LEVEL_INFO, $logData['cache'][0]->getLevel());
        $logDataContents = explode(stubLogData::SEPERATOR, $logData['cache'][0]->get());
        $this->assertEquals('pageName', $logDataContents[1]);
        $this->assertEquals('hit', $logDataContents[2]);
        $this->assertEquals('MockWebsiteCache', $logDataContents[3]);
        $this->assertEquals('missReason', $logDataContents[4]);
        $this->assertEquals('cacheKey', $logDataContents[5]);
        stubLogger::destroyInstance(__CLASS__);
    }
}
?>