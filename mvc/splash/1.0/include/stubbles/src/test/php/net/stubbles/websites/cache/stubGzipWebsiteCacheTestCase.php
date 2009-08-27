<?php
/**
 * Tests for net::stubbles::websites::cache::stubGzipWebsiteCache.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_cache_test
 */
stubClassLoader::load('net::stubbles::websites::cache::stubGzipWebsiteCache');
/**
 * Helper class for unit test to access some methods without using implementation
 * of the parent class.
 *
 * @package     stubbles
 * @subpackage  websites_cache_test
 */
class TeststubGzipWebsiteCache extends stubGzipWebsiteCache
{
    /**
     * retrieves data from cache and puts it into response
     *
     * @param   stubRequest   $request
     * @param   stubResponse  $response
     * @param   string        $pageName  name of the page to be cached
     * @return  bool          true if data was retrieved from cache, else false
     */
    public function retrieve(stubRequest $request, stubResponse $response, $pageName)
    {
        return $this->doRetrieve($request, $response, $pageName);
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
     * generates the cache key from given list of cache keys
     *
     * @param   string  $page       name of the page to be cached
     * @return  string
     */
    protected function generateCacheKey($page)
    {
        return $page;
    }
}
/**
 * Tests for net::stubbles::websites::cache::stubGzipWebsiteCache.
 *
 * @package     stubbles
 * @subpackage  websites_cache_test
 * @group       websites
 * @group       websites_cache
 */
class stubGzipWebsiteCacheTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to be used for tests
     *
     * @var  TeststubGzipWebsiteCache
     */
    protected $gzipWebsiteCache;
    /**
     * mocked decorated website cache
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockWebsiteCache;
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
        $this->mockWebsiteCache   = $this->getMock('stubWebsiteCache');
        $this->mockCacheContainer = $this->getMock('stubCacheContainer');
        $this->mockWebsiteCache->expects($this->any())->method('getCacheContainer')->will($this->returnValue($this->mockCacheContainer));
        $this->gzipWebsiteCache = new TeststubGzipWebsiteCache($this->mockWebsiteCache);
        $this->mockRequest      = $this->getMock('stubRequest');
        $this->mockResponse     = $this->getMock('stubResponse');
    }

    /**
     * assert that correct cache container is returned
     *
     * @test
     */
    public function cacheContainer()
    {
        $this->assertSame($this->mockCacheContainer, $this->gzipWebsiteCache->getCacheContainer());
    }

    /**
     * test that check files switch is handled correct
     *
     * @test
     */
    public function checkFiles()
    {
        $this->mockWebsiteCache->expects($this->once())->method('setCheckFiles')->with($this->equalTo(true));
        $this->gzipWebsiteCache->setCheckFiles(true);
    }

    /**
     * assert that cache variables are handled correct
     *
     * @test
     */
    public function cacheVars()
    {
        $this->mockWebsiteCache->expects($this->once())->method('addCacheVar')->with($this->equalTo('foo'), $this->equalTo('bar'));
        $this->gzipWebsiteCache->addCacheVar('foo', 'bar');
        $this->mockWebsiteCache->expects($this->once())->method('addCacheVars')->with($this->equalTo((array('foo' => 'bar'))));
        $this->gzipWebsiteCache->addCacheVars(array('foo' => 'bar'));
    }

    /**
     * assert that used files are handled correct
     *
     * @test
     */
    public function usedFiles()
    {
        $this->mockWebsiteCache->expects($this->once())->method('addUsedFile')->with($this->equalTo('foo.bar'));
        $this->gzipWebsiteCache->addUsedFile('foo.bar');
        $this->mockWebsiteCache->expects($this->once())->method('addUsedFiles')->with($this->equalTo(array('foo.bar')));
        $this->gzipWebsiteCache->addUsedFiles(array('foo.bar'));
    }

    /**
     * assert that gzip cache is not active when cookies are not accepted
     *
     * @test
     */
    public function retrieveCookiesNotAccepted()
    {
        $this->mockRequest->expects($this->once())->method('acceptsCookies')->will($this->returnValue(false));
        $this->mockResponse->expects($this->never())->method('addHeader');
        $this->mockResponse->expects($this->never())->method('write');
        $this->assertFalse($this->gzipWebsiteCache->retrieve($this->mockRequest, $this->mockResponse, 'foo'));
        $this->assertEquals('user agent does not accept cookies', $this->gzipWebsiteCache->getMissReason());
    }

    /**
     * assert that gzip cache is not active when compression is not accepted
     *
     * @test
     */
    public function retrieveCompressionNotAccepted()
    {
        $this->mockRequest->expects($this->once())->method('acceptsCookies')->will($this->returnValue(true));
        $this->mockRequest->expects($this->any())->method('validateValue')->will($this->returnValue(false));
        $this->mockResponse->expects($this->never())->method('addHeader');
        $this->mockResponse->expects($this->never())->method('write');
        $this->assertFalse($this->gzipWebsiteCache->retrieve($this->mockRequest, $this->mockResponse, 'foo'));
        $this->assertEquals('user agent does not accept compressed content', $this->gzipWebsiteCache->getMissReason());
    }

    /**
     * assert that gzip cache returns data in correct compression
     *
     * @test
     */
    public function retrieveXGzipCompression()
    {
        $this->mockRequest->expects($this->once())->method('acceptsCookies')->will($this->returnValue(true));
        $this->mockRequest->expects($this->once())->method('validateValue')->will($this->returnValue(true));
        $this->mockResponse->expects($this->once())->method('addHeader')->with($this->equalTo('Content-Encoding'), $this->equalTo(stubGzipWebsiteCache::X_GZIP));
        $this->mockResponse->expects($this->at(1))
                           ->method('write')
                           ->with(($this->equalTo(stubGzipWebsiteCache::HEADER)));
        $this->mockCacheContainer->expects($this->once())->method('get')->will($this->returnValue('cachedContents'));
        $this->assertTrue($this->gzipWebsiteCache->retrieve($this->mockRequest, $this->mockResponse, 'foo'));
        $this->assertEquals('', $this->gzipWebsiteCache->getMissReason());
    }

    /**
     * assert that gzip cache returns data in correct compression
     *
     * @test
     */
    public function retrieveGzipCompression()
    {
        $this->mockRequest->expects($this->once())->method('acceptsCookies')->will($this->returnValue(true));
        $this->mockRequest->expects($this->exactly(2))->method('validateValue')->will($this->onConsecutiveCalls(false, true));
        $this->mockResponse->expects($this->once())->method('addHeader')->with($this->equalTo('Content-Encoding'), $this->equalTo(stubGzipWebsiteCache::GZIP));
        $this->mockResponse->expects($this->at(1))
                           ->method('write')
                           ->with(($this->equalTo(stubGzipWebsiteCache::HEADER)));
        $this->mockCacheContainer->expects($this->once())->method('get')->will($this->returnValue('cachedContents'));
        $this->assertTrue($this->gzipWebsiteCache->retrieve($this->mockRequest, $this->mockResponse, 'foo'));
        $this->assertEquals('', $this->gzipWebsiteCache->getMissReason());
    }

    /**
     * assert that response data is stored
     *
     * @test
     */
    public function store()
    {
        stubMode::setCurrent(stubMode::$PROD);
        $this->mockResponse->expects($this->any())->method('getData')->will($this->returnValue('fooContent$SIDbla$SESSION_NAMEblub$SESSION_ID'));
        $this->mockWebsiteCache->expects($this->once())
                               ->method('store')
                               ->with($this->equalTo($this->mockRequest), $this->equalTo($this->mockResponse), $this->equalTo('foo'))
                               ->will($this->returnValue(true));
        $this->mockCacheContainer->expects($this->once())->method('put')->with($this->equalTo('foo'));
        $this->assertTrue($this->gzipWebsiteCache->store($this->mockRequest, $this->mockResponse, 'foo'));
    }
}
?>