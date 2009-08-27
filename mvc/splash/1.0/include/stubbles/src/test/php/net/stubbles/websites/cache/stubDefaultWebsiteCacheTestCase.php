<?php
/**
 * Tests for net::stubbles::websites::cache::stubDefaultWebsiteCache.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_cache_test
 */
stubClassLoader::load('net::stubbles::websites::cache::stubDefaultWebsiteCache');
/**
 * Helper class for unit test to access some methods without using implementation
 * of the parent class.
 *
 * @package     stubbles
 * @subpackage  websites_cache_test
 */
class TeststubDefaultWebsiteCache extends stubDefaultWebsiteCache
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
     * stores the data from the response in cche
     *
     * @param   stubRequest   $request
     * @param   stubResponse  $response
     * @param   string        $pageName  name of the page to be cached
     * @return  bool          true if successfully stored, else false
     */
    public function store(stubRequest $request, stubResponse $response, $pageName)
    {
        return $this->doStore($request, $response, $pageName);
    }

    /**
     * checks whether file check is enabled
     *
     * @return  bool
     */
    public function getCheckFiles()
    {
        return $this->isUsedFilesCheckEnabled();
    }

    /**
     * returns list of cache variables
     *
     * @return  array<string,scalar>
     */
    public function retrieveCacheVars()
    {
        return $this->getCacheVars();
    }

    /**
     * returns list of used files
     *
     * @return  array<string>
     */
    public function retrieveUsedFiles()
    {
        return $this->getUsedFiles();
    }
}
/**
 * Tests for net::stubbles::websites::cache::stubDefaultWebsiteCache.
 *
 * @package     stubbles
 * @subpackage  websites_cache_test
 * @group       websites
 * @group       websites_cache
 */
class stubDefaultWebsiteCacheTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to be used for tests
     *
     * @var  TeststubDefaultWebsiteCache
     */
    protected $defaultWebsiteCache;
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
        $this->mockCacheContainer  = $this->getMock('stubCacheContainer');
        $this->defaultWebsiteCache = new TeststubDefaultWebsiteCache($this->mockCacheContainer);
        $this->mockRequest         = $this->getMock('stubRequest');
        $this->mockResponse        = $this->getMock('stubResponse');
    }

    /**
     * assert that correct cache container is returned
     */
    public function testCacheContainer()
    {
        $this->assertSame($this->mockCacheContainer, $this->defaultWebsiteCache->getCacheContainer());
    }

    /**
     * test that check files switch is handled correct
     *
     * @test
     */
    public function checkFiles()
    {
        $this->assertFalse($this->defaultWebsiteCache->getCheckFiles());
        $this->defaultWebsiteCache->setCheckFiles(true);
        $this->assertTrue($this->defaultWebsiteCache->getCheckFiles());
    }

    /**
     * assert that cache variables are handled correct
     *
     * @test
     */
    public function cacheVars()
    {
        $this->assertEquals(array(), $this->defaultWebsiteCache->retrieveCacheVars());
        $this->defaultWebsiteCache->addCacheVar('foo', 'bar');
        $this->assertEquals(array('foo' => 'bar'), $this->defaultWebsiteCache->retrieveCacheVars());
        $this->defaultWebsiteCache->addCacheVars(array('bar' => 'baz'));
        $this->assertEquals(array('foo' => 'bar',
                                  'bar' => 'baz'
                            ),
                            $this->defaultWebsiteCache->retrieveCacheVars()
        );
    }

    /**
     * assert that used files are handled correct
     *
     * @test
     */
    public function usedFiles()
    {
        $this->assertEquals(array(), $this->defaultWebsiteCache->retrieveUsedFiles());
        $this->defaultWebsiteCache->addUsedFile('foo.bar');
        $this->assertEquals(array('foo.bar' => 'foo.bar'), $this->defaultWebsiteCache->retrieveUsedFiles());
        $this->defaultWebsiteCache->addUsedFiles(array('bar.baz'));
        $this->assertEquals(array('foo.bar' => 'foo.bar',
                                  'bar.baz' => 'bar.baz'
                            ),
                            $this->defaultWebsiteCache->retrieveUsedFiles()
        );
    }

    /**
     * assure that disabled caching leads to nothing set in the response
     *
     * @test
     */
    public function retrieve()
    {
        $this->mockCacheContainer->expects($this->once())
                                 ->method('get')
                                 ->with($this->equalTo('foo'))
                                 ->will($this->returnValue('fooContents'));
        $this->mockResponse->expects($this->once())
                           ->method('write')
                           ->with($this->equalTo('fooContents'));
        $this->assertTrue($this->defaultWebsiteCache->retrieve($this->mockRequest, $this->mockResponse, 'foo'));
    }

    /**
     * assure that a missing cache entry leads to nothing set in the response
     *
     * @test
     */
    public function store()
    {
        $this->mockResponse->expects($this->once())
                           ->method('getData')
                           ->will($this->returnValue('fooContents'));
        $this->mockCacheContainer->expects($this->once())
                                 ->method('put')
                                 ->with($this->equalTo('foo'), $this->equalTo('fooContents'))
                                 ->will($this->returnValue(11));
        $this->assertTrue($this->defaultWebsiteCache->store($this->mockRequest, $this->mockResponse, 'foo'));
    }
}
?>