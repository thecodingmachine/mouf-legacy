<?php
/**
 * Tests for net::stubbles::websites::xml::stubXMLProcessor.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_xml_test
 */
stubClassLoader::load('net::stubbles::websites::xml::stubXMLProcessor',
                      'net::stubbles::websites::xml::generator::stubXMLGenerator'
);
/**
 * Helper class for the test.
 *
 * @package     stubbles
 * @subpackage  websites_xml_test
 */
class TeststubXMLProcessor extends stubXMLProcessor
{
    /**
     * sets the page and page name to be used
     *
     * @param  stubPage  $page
     * @param  string    $pageName
     */
    public function setPage(stubPage $page, $pageName)
    {
        $this->page     = $page;
        $this->pageName = $pageName;
    }

    /**
     * public access to protected method
     *
     * @return  array<string>
     */
    public function callGetXMLGenerators()
    {
        return $this->getXMLGenerators();
    }

    /**
     * public access to protected method
     *
     * @param   stubSkinGenerator  $skinGenerator
     * @return  string
     */
    public function callGetSkinName(stubSkinGenerator $skinGenerator)
    {
        return $this->getSkinName($skinGenerator);
    }
}
/**
 * Tests for net::stubbles::websites::xml::stubXMLProcessor.
 *
 * @package     stubbles
 * @subpackage  websites_xml_test
 * @group       websites
 * @group       websites_xml
 */
class stubXMLProcessorTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to be used for tests
     *
     * @var  TeststubXMLProcessor
     */
    protected $xmlProcessor;
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
     * mocked page factory instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockPageFactory;
    /**
     * mocked xml stream writer instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockXMLStreamWriter;
    /**
     * mocked xml serializer instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockXMLSerializer;
    /**
     * mocked page configuration instance
     *
     * @var  stubPage
     */
    protected $page;
    /**
     * mocked injector instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockInjector;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->mockInjector = $this->getMock('stubInjector');
        stubRegistry::set(stubBinder::REGISTRY_KEY, new stubBinder($this->mockInjector));
        $this->mockRequest         = $this->getMock('stubRequest');
        $this->mockRequest->expects($this->any())->method('getValueErrors')->will($this->returnValue(array()));
        $this->mockSession         = $this->getMock('stubSession');
        $this->mockResponse        = $this->getMock('stubResponse');
        $this->mockPageFactory     = $this->getMock('stubPageFactory');
        $this->xmlProcessor        = $this->getMock('stubXMLProcessor',
                                                    array('createXMLStreamWriter',
                                                          'createXMLSerializer',
                                                          'createSkinGenerator',
                                                          'createXSLProcessor'
                                                    ),
                                                    array($this->mockRequest, $this->mockSession, $this->mockResponse)
                                     );
        $this->mockXMLStreamWriter = $this->getMock('stubXMLStreamWriter');
        $this->mockXMLSerializer   = $this->getMock('stubXMLSerializer');
        $this->page = new stubPage();
        $this->mockPageFactory->expects($this->once())->method('getPageName')->will($this->returnValue('index'));
        $this->mockPageFactory->expects($this->once())->method('getPage')->will($this->returnValue($this->page));
        $this->mockSession->expects($this->at(0))->method('putValue')->with($this->equalTo('net.stubbles.websites.lastPage'), $this->equalTo('index'));
        $this->xmlProcessor->selectPage($this->mockPageFactory);
    }

    /**
     * clean up test environment
     */
    public function tearDown()
    {
        stubRegistry::remove(stubBinder::REGISTRY_KEY);
    }

    /**
     * no binder available > runtime exception
     *
     * @test
     * @expectedException  stubRuntimeException
     */
    public function cachabilityTestWithoutBinderInRegistryThrowsRuntimeException()
    {
        stubRegistry::remove(stubBinder::REGISTRY_KEY);
        $this->xmlProcessor->addCacheVars($this->getMock('stubWebsiteCache'));
    }

    /**
     * no binder available > runtime exception
     *
     * @test
     * @expectedException  stubRuntimeException
     */
    public function processWithoutBinderInRegistryThrowsRuntimeException()
    {
        stubRegistry::remove(stubBinder::REGISTRY_KEY);
        $this->xmlProcessor->process();
    }

    /**
     * page to be generated is cachable
     *
     * @test
     */
    public function isCachable()
    {
        $this->assertEquals('index', $this->xmlProcessor->getPageName());
        
        $mockXMLGenerator = $this->getMock('stubXMLGenerator');
        $this->mockInjector->expects($this->exactly(3))->method('getInstance')->will($this->returnValue($mockXMLGenerator));
        $mockXMLGenerator->expects($this->exactly(6))->method('isCachable')->will($this->returnValue(true));
        $mockXMLGenerator->expects($this->exactly(6))->method('getCacheVars')->will($this->returnValue(array('foo' => 'bar')));
        $mockXMLGenerator->expects($this->exactly(6))->method('getUsedFiles')->will($this->returnValue(array('foo.bar')));
        $mockWebsiteCache = $this->getMock('stubWebsiteCache');
        $mockWebsiteCache->expects($this->exactly(2))->method('addCacheVar')->with($this->equalTo('page'), $this->equalTo('index'));
        $mockWebsiteCache->expects($this->exactly(6))->method('addCacheVars')->with($this->equalTo(array('foo' => 'bar')));
        $mockWebsiteCache->expects($this->exactly(6))->method('addUsedFiles')->with($this->equalTo(array('foo.bar')));
        $this->assertTrue($this->xmlProcessor->addCacheVars($mockWebsiteCache));
        $this->assertTrue($this->xmlProcessor->addCacheVars($mockWebsiteCache));
    }

    /**
     * page to be generated is not cachable
     *
     * @test
     */
    public function isNotCachable()
    {
        $this->assertEquals('index', $this->xmlProcessor->getPageName());
        
        $mockXMLGenerator = $this->getMock('stubXMLGenerator');
        $this->mockInjector->expects($this->exactly(3))->method('getInstance')->will($this->returnValue($mockXMLGenerator));
        $mockXMLGenerator->expects($this->once())->method('isCachable')->will($this->returnValue(false));
        $mockXMLGenerator->expects($this->never())->method('getCacheVars');
        $mockXMLGenerator->expects($this->never())->method('getUsedFiles');
        $mockWebsiteCache = $this->getMock('stubWebsiteCache');
        $mockWebsiteCache->expects($this->never())->method('addCacheVar');
        $mockWebsiteCache->expects($this->never())->method('addCacheVars');
        $mockWebsiteCache->expects($this->never())->method('addUsedFiles');
        $this->assertFalse($this->xmlProcessor->addCacheVars($mockWebsiteCache));
    }

    /**
     * assure that processing works as expected
     *
     * @test
     */
   public function processSuccessful()
    {
        $this->mockRequest->expects($this->exactly(3))
                          ->method('isCancelled')
                          ->will($this->returnValue(false));
        $mockXMLGenerator = $this->getMock('stubXMLGenerator');
        $this->mockInjector->expects($this->exactly(3))->method('getInstance')->will($this->returnValue($mockXMLGenerator));
        $mockXMLGenerator->expects($this->exactly(3))->method('generate')->with($this->equalTo($this->mockXMLStreamWriter), $this->equalTo($this->mockXMLSerializer));
        $this->xmlProcessor->expects($this->once())->method('createXMLStreamWriter')->will($this->returnValue($this->mockXMLStreamWriter));
        $this->xmlProcessor->expects($this->once())->method('createXMLSerializer')->will($this->returnValue($this->mockXMLSerializer));
        $this->mockXMLStreamWriter->expects($this->once())->method('writeStartElement');
        $this->mockXMLStreamWriter->expects($this->once())->method('writeAttribute')->with($this->equalTo('page'), $this->equalTo('index'));
        $this->mockXMLStreamWriter->expects($this->once())->method('writeEndElement');
        $resultXSL         = new DOMDocument();
        $mockSkinGenerator = $this->getMock('stubSkinGenerator');
        $mockSkinGenerator->expects($this->once())
                          ->method('generate')
                          ->will($this->returnValue($resultXSL));
        $this->xmlProcessor->expects($this->once())->method('createSkinGenerator')->will($this->returnValue($mockSkinGenerator));
        
        $domDocument0 = new DOMDocument();
        $domDocument0->createElement('bar', 'foo');
        $this->mockXMLStreamWriter->expects($this->any())->method('asDOM')->will($this->returnValue($domDocument0));
        $this->mockXMLStreamWriter->expects($this->any())->method('asXML')->will($this->returnValue('<bar>foo</bar>'));
        $mockXSLProcessor = $this->getMock('stubXSLProcessor');
        $mockXSLProcessor->expects($this->once())
                         ->method('andApplyStylesheet')
                         ->with($this->equalTo($resultXSL))
                         ->will($this->returnValue($mockXSLProcessor));
        $mockXSLProcessor->expects($this->once())
                         ->method('onDocument')
                         ->with($this->equalTo($domDocument0))
                         ->will($this->returnValue($mockXSLProcessor));
        $mockXSLProcessor->expects($this->once())
                         ->method('toXML')
                         ->will($this->returnValue('<html><head><title>Test</title></head><body><p>Hello world.</p></body></html>'));
        $this->xmlProcessor->expects($this->once())
                           ->method('createXSLProcessor')
                           ->will($this->returnValue($mockXSLProcessor));
        $this->mockSession->expects($this->once())->method('putValue')->with($this->equalTo('net.stubbles.websites.lastRequestResponseData'), $this->equalTo('<bar>foo</bar>'));
        $this->mockResponse->expects($this->once())->method('replaceData')->with($this->equalTo('<html><head><title>Test</title></head><body><p>Hello world.</p></body></html>'));
        $this->xmlProcessor->process();
    }

    /**
     * assure that processing works as expected
     *
     * @test
     */
   public function processCancelledByGenerator()
    {
        $this->mockRequest->expects($this->once())
                          ->method('isCancelled')
                          ->will($this->returnValue(true));
        $mockXMLGenerator = $this->getMock('stubXMLGenerator');
        $this->mockInjector->expects($this->exactly(3))->method('getInstance')->will($this->returnValue($mockXMLGenerator));
        $mockXMLGenerator->expects($this->once())->method('generate')->with($this->equalTo($this->mockXMLStreamWriter), $this->equalTo($this->mockXMLSerializer));
        $this->xmlProcessor->expects($this->once())->method('createXMLStreamWriter')->will($this->returnValue($this->mockXMLStreamWriter));
        $this->xmlProcessor->expects($this->once())->method('createXMLSerializer')->will($this->returnValue($this->mockXMLSerializer));
        $this->xmlProcessor->expects($this->never())->method('createSkinGenerator');
        $this->xmlProcessor->expects($this->never())->method('createXSLProcessor');
        $this->mockXMLStreamWriter->expects($this->never())->method('asDOM');
        $this->mockXMLStreamWriter->expects($this->never())->method('asXML');
        $this->mockSession->expects($this->never())->method('putValue');
        $this->mockResponse->expects($this->never())->method('replaceData');
        $this->xmlProcessor->process();
    }

    /**
     * xml generators by default are without mode generator
     *
     * @test
     */
    public function xmlGeneratorsByDefault()
    {
        stubRegistry::removeConfig(stubXMLProcessor::SERIALIZE_MODE_REGISTRY_KEY);
        $xmlProcessor = new TeststubXMLProcessor($this->mockRequest, $this->mockSession, $this->mockResponse);
        $this->assertEquals(array('net::stubbles::websites::xml::generator::stubSessionXMLGenerator',
                                  'net::stubbles::websites::xml::generator::stubPageXMLGenerator',
                                  'net::stubbles::websites::xml::generator::stubRequestXMLGenerator'
                            ),
                            $xmlProcessor->callGetXMLGenerators()
        );
    }

    /**
     * xml generators are with mode generator if enabled
     *
     * @test
     */
    public function xmlGeneratorsWithMode()
    {
        stubRegistry::setConfig(stubXMLProcessor::SERIALIZE_MODE_REGISTRY_KEY, true);
        $xmlProcessor = new TeststubXMLProcessor($this->mockRequest, $this->mockSession, $this->mockResponse);
        $this->assertEquals(array('net::stubbles::websites::xml::generator::stubSessionXMLGenerator',
                                  'net::stubbles::websites::xml::generator::stubPageXMLGenerator',
                                  'net::stubbles::websites::xml::generator::stubRequestXMLGenerator',
                                  'net::stubbles::websites::xml::generator::stubModeXMLGenerator'
                            ),
                            $xmlProcessor->callGetXMLGenerators()
        );
    }

    /**
     * skin from request should be used
     *
     * @test
     */
    public function skinFromRequest()
    {
        $xmlProcessor = new TeststubXMLProcessor($this->mockRequest, $this->mockSession, $this->mockResponse);
        $xmlProcessor->setPage($this->page, 'index');
        $this->mockRequest->expects($this->once())
                          ->method('hasValue')
                          ->will($this->returnValue(true));
        $this->mockRequest->expects($this->once())
                          ->method('getValidatedValue')
                          ->will($this->returnValue('foo'));
        $mockSkinGenerator = $this->getMock('stubSkinGenerator');
        $mockSkinGenerator->expects($this->once())
                          ->method('hasSkin')
                          ->with($this->equalTo('foo'))
                          ->will($this->returnValue(true));
        $this->assertEquals('foo', $xmlProcessor->callGetSkinName($mockSkinGenerator));
    }

    /**
     * non-existing skin from request falls back to default
     *
     * @test
     */
    public function nonExistingSkinFromRequest()
    {
        $xmlProcessor = new TeststubXMLProcessor($this->mockRequest, $this->mockSession, $this->mockResponse);
        $xmlProcessor->setPage($this->page, 'index');
        $this->mockRequest->expects($this->once())
                          ->method('hasValue')
                          ->will($this->returnValue(true));
        $this->mockRequest->expects($this->once())
                          ->method('getValidatedValue')
                          ->will($this->returnValue('foo'));
        $mockSkinGenerator = $this->getMock('stubSkinGenerator');
        $mockSkinGenerator->expects($this->once())
                          ->method('hasSkin')
                          ->with($this->equalTo('foo'))
                          ->will($this->returnValue(false));
        $this->assertEquals('default', $xmlProcessor->callGetSkinName($mockSkinGenerator));
    }

    /**
     * invalid skin from request should fallback to default
     *
     * @test
     */
    public function invalidSkinFromRequest()
    {
        $xmlProcessor = new TeststubXMLProcessor($this->mockRequest, $this->mockSession, $this->mockResponse);
        $xmlProcessor->setPage($this->page, 'index');
        $this->mockRequest->expects($this->once())
                          ->method('hasValue')
                          ->will($this->returnValue(true));
        $this->mockRequest->expects($this->once())
                          ->method('getValidatedValue')
                          ->will($this->returnValue(null));
        $mockSkinGenerator = $this->getMock('stubSkinGenerator');
        $mockSkinGenerator->expects($this->never())
                          ->method('hasSkin');
        $this->assertEquals('default', $xmlProcessor->callGetSkinName($mockSkinGenerator));
    }

    /**
     * skin from page should be used
     *
     * @test
     */
    public function skinFromPage()
    {
        $xmlProcessor = new TeststubXMLProcessor($this->mockRequest, $this->mockSession, $this->mockResponse);
        $xmlProcessor->setPage($this->page, 'index');
        $this->mockRequest->expects($this->once())
                          ->method('hasValue')
                          ->will($this->returnValue(false));
        $this->mockRequest->expects($this->never())
                          ->method('getValidatedValue');
        $this->page->setProperty('skin', 'bar');
        $mockSkinGenerator = $this->getMock('stubSkinGenerator');
        $mockSkinGenerator->expects($this->once())
                          ->method('hasSkin')
                          ->with($this->equalTo('bar'))
                          ->will($this->returnValue(true));
        $this->assertEquals('bar', $xmlProcessor->callGetSkinName($mockSkinGenerator));
    }

    /**
     * non-existing skin from page falls back to default
     *
     * @test
     */
    public function nonExistingSkinFromPage()
    {
        $xmlProcessor = new TeststubXMLProcessor($this->mockRequest, $this->mockSession, $this->mockResponse);
        $xmlProcessor->setPage($this->page, 'index');
        $this->mockRequest->expects($this->once())
                          ->method('hasValue')
                          ->will($this->returnValue(false));
        $this->mockRequest->expects($this->never())
                          ->method('getValidatedValue');
        $this->page->setProperty('skin', 'bar');
        $mockSkinGenerator = $this->getMock('stubSkinGenerator');
        $mockSkinGenerator->expects($this->once())
                          ->method('hasSkin')
                          ->with($this->equalTo('bar'))
                          ->will($this->returnValue(false));
        $this->assertEquals('default', $xmlProcessor->callGetSkinName($mockSkinGenerator));
    }

    /**
     * no special skin requested
     *
     * @test
     */
    public function noSkinSelected()
    {
        $xmlProcessor = new TeststubXMLProcessor($this->mockRequest, $this->mockSession, $this->mockResponse);
        $xmlProcessor->setPage($this->page, 'index');
        $this->mockRequest->expects($this->once())
                          ->method('hasValue')
                          ->will($this->returnValue(false));
        $this->mockRequest->expects($this->never())
                          ->method('getValidatedValue');
        $mockSkinGenerator = $this->getMock('stubSkinGenerator');
        $mockSkinGenerator->expects($this->never())
                          ->method('hasSkin');
        $this->assertEquals('default', $xmlProcessor->callGetSkinName($mockSkinGenerator));
    }
}
?>