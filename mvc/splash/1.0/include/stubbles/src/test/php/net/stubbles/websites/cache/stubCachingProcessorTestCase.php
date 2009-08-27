<?php
/**
 * Tests for net::stubbles::websites::cache::stubCachingProcessor.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_cache_test
 */
stubClassLoader::load('net::stubbles::websites::cache::stubCachingProcessor');
/**
 * Helper class for the test.
 *
 * @package     stubbles
 * @subpackage  websites_cache_test
 */
class TeststubCachingProcessor extends stubCachingProcessor
{
    /**
     * helper method
     */
    public function callSetSessionData()
    {
        $this->setSessionData();
    }
}
/**
 * Tests for net::stubbles::websites::cache::stubCachingProcessor.
 *
 * @package     stubbles
 * @subpackage  websites_cache_test
 * @group       websites
 * @group       websites_cache
 */
class stubCachingProcessorTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to be used for tests
     *
     * @var  stubCachingProcessor
     */
    protected $cachingProcessor;
    /**
     * mocked cachable processor
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockCachableProcessor;
    /**
     * mocked request instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockRequest;
    /**
     * mocked session instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockSession;
    /**
     * mocked response instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockResponse;
    /**
     * mocked website cache instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockWebsiteCache;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->mockCachableProcessor = $this->getMock('stubCachableProcessor');
        $this->mockRequest           = $this->getMock('stubRequest');
        $this->mockSession           = $this->getMock('stubSession');
        $this->mockResponse          = $this->getMock('stubResponse');
        $this->mockCachableProcessor->expects($this->once())->method('getRequest')->will($this->returnValue($this->mockRequest));
        $this->mockCachableProcessor->expects($this->once())->method('getSession')->will($this->returnValue($this->mockSession));
        $this->mockCachableProcessor->expects($this->once())->method('getResponse')->will($this->returnValue($this->mockResponse));
        $this->mockWebsiteCache = $this->getMock('stubWebsiteCache');
        $this->cachingProcessor = $this->getMock('stubCachingProcessor',
                                                 array('setSessionData'),
                                                 array($this->mockCachableProcessor,
                                                       $this->mockWebsiteCache
                                                 )
                                  );
    }

    /**
     * caching processor should use same instances as cachable processor
     *
     * @test
     */
    public function sameInstance()
    {
        $this->assertSame($this->mockRequest, $this->cachingProcessor->getRequest());
        $this->assertSame($this->mockSession, $this->cachingProcessor->getSession());
        $this->assertSame($this->mockResponse, $this->cachingProcessor->getResponse());
    }

    /**
     * interceptor descriptor should just be handled by cachable processor
     *
     * @test
     */
    public function interceptorDescriptor()
    {
        $this->mockCachableProcessor->expects($this->once())
                                    ->method('setInterceptorDescriptor')
                                    ->with($this->equalTo('foo'));
        $this->mockCachableProcessor->expects($this->once())
                                    ->method('getInterceptorDescriptor')
                                    ->will($this->returnValue('foo'));
        $this->cachingProcessor->setInterceptorDescriptor('foo');
        $this->assertEquals('foo', $this->cachingProcessor->getInterceptorDescriptor());
    }

    /**
     * ssl should just be handled by cachable processor
     *
     * @test
     */
    public function sslHandling()
    {
        $this->mockCachableProcessor->expects($this->once())
                                    ->method('forceSSL')
                                    ->will($this->returnValue(true));
        $this->mockCachableProcessor->expects($this->once())
                                    ->method('isSSL')
                                    ->will($this->returnValue(false));
        $this->assertTrue($this->cachingProcessor->forceSSL());
        $this->assertFalse($this->cachingProcessor->isSSL());
    }

    /**
     * generated content is cachable and already cached
     *
     * @test
     */
    public function cachableAndCached()
    {
        $this->mockCachableProcessor->expects($this->once())
                                    ->method('isSSL')
                                    ->will($this->returnValue(false));
        $this->mockCachableProcessor->expects($this->once())
                                    ->method('addCacheVars')
                                    ->will($this->returnValue(true));
        $this->mockCachableProcessor->expects($this->once())
                                    ->method('getPageName')
                                    ->will($this->returnValue('pageName'));
        $this->mockWebsiteCache->expects($this->once())
                               ->method('addCacheVar')
                               ->with($this->equalTo('ssl'), $this->equalTo(false));
        $this->mockWebsiteCache->expects($this->once())
                               ->method('retrieve')
                               ->with($this->equalTo($this->mockRequest), $this->equalTo($this->mockResponse), $this->equalTo('pageName'))
                               ->will($this->returnValue(true));
        $this->mockCachableProcessor->expects($this->never())
                                    ->method('process');
        $this->mockWebsiteCache->expects($this->never())
                               ->method('store');
        $this->cachingProcessor->expects($this->once())
                               ->method('setSessionData');
        $this->cachingProcessor->process();
    }

    /**
     * generated content is cachable but not cached
     *
     * @test
     */
    public function cachableAndNotCached()
    {
        $this->mockCachableProcessor->expects($this->once())
                                    ->method('isSSL')
                                    ->will($this->returnValue(true));
        $this->mockCachableProcessor->expects($this->once())
                                    ->method('addCacheVars')
                                    ->will($this->returnValue(true));
        $this->mockCachableProcessor->expects($this->exactly(2))
                                    ->method('getPageName')
                                    ->will($this->returnValue('pageName'));
        $this->mockWebsiteCache->expects($this->once())
                               ->method('addCacheVar')
                               ->with($this->equalTo('ssl'), $this->equalTo(true));
        $this->mockWebsiteCache->expects($this->once())
                               ->method('retrieve')
                               ->with($this->equalTo($this->mockRequest), $this->equalTo($this->mockResponse), $this->equalTo('pageName'))
                               ->will($this->returnValue(false));
        $this->mockCachableProcessor->expects($this->once())
                                    ->method('process');
        $this->mockWebsiteCache->expects($this->once())
                               ->method('store')
                               ->with($this->equalTo($this->mockRequest), $this->equalTo($this->mockResponse), $this->equalTo('pageName'));
        $this->cachingProcessor->expects($this->once())
                               ->method('setSessionData');
        $this->cachingProcessor->process();
    }

    /**
     * generated content is not cachable
     *
     * @test
     */
    public function notCachable()
    {
        $this->mockCachableProcessor->expects($this->never())
                                    ->method('isSSL');
        $this->mockCachableProcessor->expects($this->once())
                                    ->method('addCacheVars')
                                    ->will($this->returnValue(false));
        $this->mockCachableProcessor->expects($this->never())
                                    ->method('getPageName');
        $this->mockWebsiteCache->expects($this->never())
                               ->method('addCacheVar');
        $this->mockWebsiteCache->expects($this->never())
                               ->method('retrieve')
                               ->will($this->returnValue(false));
        $this->mockCachableProcessor->expects($this->once())
                                    ->method('process');
        $this->mockWebsiteCache->expects($this->never())
                               ->method('store');
        $this->cachingProcessor->expects($this->once())
                               ->method('setSessionData');
        $this->cachingProcessor->process();
    }

    /**
     * make sure session data is replaced correctly
     *
     * @test
     */
    public function sessionDataReplacement()
    {
        $this->mockCachableProcessor = $this->getMock('stubCachableProcessor');
        $this->mockRequest           = $this->getMock('stubRequest');
        $this->mockSession           = $this->getMock('stubSession');
        $this->mockResponse          = $this->getMock('stubResponse');
        $this->mockCachableProcessor->expects($this->once())->method('getRequest')->will($this->returnValue($this->mockRequest));
        $this->mockCachableProcessor->expects($this->once())->method('getSession')->will($this->returnValue($this->mockSession));
        $this->mockCachableProcessor->expects($this->once())->method('getResponse')->will($this->returnValue($this->mockResponse));
        $cachingProcessor = new TeststubCachingProcessor($this->mockCachableProcessor, $this->mockWebsiteCache);
        $this->mockResponse->expects($this->once())
                           ->method('getData')
                           ->will($this->returnValue('foo$SIDbar$SESSION_NAMEbaz$SESSION_IDdummy'));
        $this->mockResponse->expects($this->once())
                           ->method('replaceData')
                           ->with($this->equalTo('foosid=ac1704barsidbazac1704dummy'));
        $this->mockSession->expects($this->exactly(2))
                          ->method('getId')
                          ->will($this->returnValue('ac1704'));
        $this->mockSession->expects($this->exactly(2))
                          ->method('getName')
                          ->will($this->returnValue('sid'));
        $cachingProcessor->callSetSessionData();
    }
}
?>