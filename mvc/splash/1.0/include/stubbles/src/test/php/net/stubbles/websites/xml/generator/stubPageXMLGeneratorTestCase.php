<?php
/**
 * Tests for net::stubbles::websites::xml::generator::stubPageXMLGenerator.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_xml_generator_test
 */
stubClassLoader::load('net::stubbles::websites::xml::generator::stubPageXMLGenerator');
/**
 * Tests for net::stubbles::websites::xml::generator::stubPageXMLGenerator.
 *
 * @package     stubbles
 * @subpackage  websites_xml_generator_test
 * @group       websites
 * @group       websites_xml
 */
class stubPageXMLGeneratorTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubPageXMLGenerator
     */
    protected $pageXMLGenerator;
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
     * page instance
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
     * set up test environment
     */
    public function setUp()
    {
        $this->mockRequest         = $this->getMock('stubRequest');
        $this->mockSession         = $this->getMock('stubSession');
        $this->mockResponse        = $this->getMock('stubResponse');
        $this->page                = new stubPage();
        $this->mockInjector        = $this->getMock('stubInjector');
        $this->pageXMLGenerator    = new stubPageXMLGenerator($this->mockRequest, $this->mockSession, $this->mockResponse, $this->mockInjector);
        $this->pageXMLGenerator->setPage($this->page);
        $this->mockXMLStreamWriter = $this->getMock('stubXMLStreamWriter');
        $this->mockXMLSerializer   = $this->getMock('stubXMLSerializer');
    }

    /**
     * clean up test environment
     */
    public function tearDown()
    {
        stubRegistry::remove(stubBinder::REGISTRY_KEY);
    }

    /**
     * page with no elements and no resources: nothing to serialize
     *
     * @test
     */
    public function pageWithoutElementsAndRespources()
    {
        $this->mockRequest->expects($this->never())->method('isCancelled');
        $this->mockInjector->expects($this->never())->method('handleInjections');
        $this->mockXMLSerializer->expects($this->once())
                                ->method('serialize')
                                ->with($this->equalTo(array()), $this->equalTo($this->mockXMLStreamWriter));
        $this->mockXMLStreamWriter->expects($this->once())->method('writeStartElement');
        $this->mockXMLStreamWriter->expects($this->once())->method('writeEndElement');
        $this->assertTrue($this->pageXMLGenerator->isCachable());
        $this->assertEquals(array(), $this->pageXMLGenerator->getCacheVars());
        $this->assertEquals(array(), $this->pageXMLGenerator->getUsedFiles());
        $this->pageXMLGenerator->generate($this->mockXMLStreamWriter, $this->mockXMLSerializer);
    }

    /**
     * page with elements but without resources: serialize return values
     *
     * @test
     */
    public function pageWithElementsAndWithoutResources()
    {
        $this->mockRequest->expects($this->exactly(2))->method('isCancelled')->will($this->returnValue(false));
        $this->mockInjector->expects($this->exactly(3))->method('handleInjections');
        $pageElement1 = $this->getMock('stubPageElement');
        $pageElement1->expects($this->any())->method('getName')->will($this->returnValue('foo'));
        $pageElement1->expects($this->once())->method('isAvailable')->will($this->returnValue(true));
        $pageElement1->expects($this->once())->method('isCachable')->will($this->returnValue(true));
        $pageElement1->expects($this->once())->method('getCacheVars')->will($this->returnValue(array()));
        $pageElement1->expects($this->once())->method('getUsedFiles')->will($this->returnValue(array()));
        $pageElement1->expects($this->once())->method('process')->will($this->returnValue('foo'));
        $pageElement2 = $this->getMock('stubPageElement');
        $pageElement2->expects($this->any())->method('getName')->will($this->returnValue('bar'));
        $pageElement2->expects($this->once())->method('isAvailable')->will($this->returnValue(false));
        $pageElement2->expects($this->never())->method('isCachable');
        $pageElement2->expects($this->never())->method('getCacheVars');
        $pageElement2->expects($this->never())->method('getUsedFiles');
        $pageElement2->expects($this->never())->method('process');
        $pageElement3 = $this->getMock('stubXMLPageElement');
        $pageElement3->expects($this->any())->method('getName')->will($this->returnValue('baz'));
        $pageElement3->expects($this->once())->method('isAvailable')->will($this->returnValue(true));
        $pageElement3->expects($this->once())->method('isCachable')->will($this->returnValue(true));
        $pageElement3->expects($this->once())->method('getCacheVars')->will($this->returnValue(array()));
        $pageElement3->expects($this->once())->method('getUsedFiles')->will($this->returnValue(array()));
        $pageElement3->expects($this->once())->method('process')->will($this->returnValue('baz'));
        $pageElement3->expects($this->once())->method('getFormValues')->will($this->returnValue(array('foo')));
        $this->page->addElement($pageElement1);
        $this->page->addElement($pageElement2);
        $this->page->addElement($pageElement3);
        $this->pageXMLGenerator->setPage($this->page);
        
        $this->mockXMLSerializer->expects($this->at(0))
                                ->method('serialize')
                                ->with($this->equalTo('foo'), $this->equalTo($this->mockXMLStreamWriter), $this->equalTo(array(stubXMLSerializer::OPT_ROOT_TAG => 'foo')));
        $this->mockXMLSerializer->expects($this->at(1))
                                ->method('serialize')
                                ->with($this->equalTo('baz'), $this->equalTo($this->mockXMLStreamWriter), $this->equalTo(array(stubXMLSerializer::OPT_ROOT_TAG => 'baz')));
        $this->mockXMLSerializer->expects($this->at(2))
                                ->method('serialize')
                                ->with($this->equalTo(array('baz' => array('foo'))), $this->equalTo($this->mockXMLStreamWriter));
        $this->mockXMLStreamWriter->expects($this->once())->method('writeStartElement');
        $this->mockXMLStreamWriter->expects($this->once())->method('writeEndElement');
        $this->assertTrue($this->pageXMLGenerator->isCachable());
        $this->assertEquals(array(), $this->pageXMLGenerator->getCacheVars());
        $this->assertEquals(array(), $this->pageXMLGenerator->getUsedFiles());
        $this->pageXMLGenerator->generate($this->mockXMLStreamWriter, $this->mockXMLSerializer);
    }

    /**
     * page with cancelling elements: stop processing
     *
     * @test
     */
    public function pageWithCancellingElement()
    {
        $this->mockRequest->expects($this->once())->method('isCancelled')->will($this->returnValue(true));
        $this->mockInjector->expects($this->exactly(2))->method('handleInjections');
        $pageElement1 = $this->getMock('stubPageElement');
        $pageElement1->expects($this->any())->method('getName')->will($this->returnValue('foo'));
        $pageElement1->expects($this->once())->method('isAvailable')->will($this->returnValue(true));
        $pageElement1->expects($this->once())->method('isCachable')->will($this->returnValue(true));
        $pageElement1->expects($this->once())->method('getCacheVars')->will($this->returnValue(array('foo' => 'bar')));
        $pageElement1->expects($this->once())->method('getUsedFiles')->will($this->returnValue(array('foo.xml')));
        $pageElement1->expects($this->once())->method('process')->will($this->returnValue('foo'));
        $pageElement2 = $this->getMock('stubPageElement');
        $pageElement2->expects($this->any())->method('getName')->will($this->returnValue('bar'));
        $pageElement2->expects($this->once())->method('isAvailable')->will($this->returnValue(true));
        $pageElement2->expects($this->once())->method('isCachable')->will($this->returnValue(true));
        $pageElement2->expects($this->once())->method('getCacheVars')->will($this->returnValue(array('bar' => 313)));
        $pageElement2->expects($this->once())->method('getUsedFiles')->will($this->returnValue(array()));
        $pageElement2->expects($this->never())->method('process');
        $this->page->addElement($pageElement1);
        $this->page->addElement($pageElement2);
        $this->pageXMLGenerator->setPage($this->page);
        
        $this->mockXMLSerializer->expects($this->never())->method('serialize');
        $this->mockXMLStreamWriter->expects($this->never())->method('writeStartElement');
        $this->mockXMLStreamWriter->expects($this->never())->method('writeEndElement');
        $this->assertTrue($this->pageXMLGenerator->isCachable());
        $this->assertEquals(array('foo' => 'bar', 'bar' => 313), $this->pageXMLGenerator->getCacheVars());
        $this->assertEquals(array('foo.xml'), $this->pageXMLGenerator->getUsedFiles());
        $this->pageXMLGenerator->generate($this->mockXMLStreamWriter, $this->mockXMLSerializer);
    }

    /**
     * page with cancelling elements: stop processing
     *
     * @test
     */
    public function pageWithNonCachableElement()
    {
        $this->mockRequest->expects($this->exactly(2))->method('isCancelled')->will($this->returnValue(false));
        $this->mockInjector->expects($this->exactly(2))->method('handleInjections');
        $pageElement1 = $this->getMock('stubPageElement');
        $pageElement1->expects($this->any())->method('getName')->will($this->returnValue('foo'));
        $pageElement1->expects($this->once())->method('isAvailable')->will($this->returnValue(true));
        $pageElement1->expects($this->once())->method('isCachable')->will($this->returnValue(false));
        $pageElement1->expects($this->never())->method('getCacheVars');
        $pageElement1->expects($this->never())->method('getUsedFiles');
        $pageElement1->expects($this->once())->method('process')->will($this->returnValue('foo'));
        $pageElement2 = $this->getMock('stubPageElement');
        $pageElement2->expects($this->any())->method('getName')->will($this->returnValue('bar'));
        $pageElement2->expects($this->once())->method('isAvailable')->will($this->returnValue(true));
        $pageElement2->expects($this->never())->method('isCachable');
        $pageElement1->expects($this->never())->method('getCacheVars');
        $pageElement1->expects($this->never())->method('getUsedFiles');
        $pageElement2->expects($this->once())->method('process')->will($this->returnValue('bar'));
        $this->page->addElement($pageElement1);
        $this->page->addElement($pageElement2);
        $this->pageXMLGenerator->setPage($this->page);
        
        $this->mockXMLSerializer->expects($this->at(0))
                                ->method('serialize')
                                ->with($this->equalTo('foo'), $this->equalTo($this->mockXMLStreamWriter), $this->equalTo(array(stubXMLSerializer::OPT_ROOT_TAG => 'foo')));
        $this->mockXMLSerializer->expects($this->at(1))
                                ->method('serialize')
                                ->with($this->equalTo('bar'), $this->equalTo($this->mockXMLStreamWriter), $this->equalTo(array(stubXMLSerializer::OPT_ROOT_TAG => 'bar')));
        $this->mockXMLStreamWriter->expects($this->once())->method('writeStartElement');
        $this->mockXMLStreamWriter->expects($this->once())->method('writeEndElement');
        $this->assertFalse($this->pageXMLGenerator->isCachable());
        $this->assertEquals(array(), $this->pageXMLGenerator->getCacheVars());
        $this->assertEquals(array(), $this->pageXMLGenerator->getUsedFiles());
        $this->pageXMLGenerator->generate($this->mockXMLStreamWriter, $this->mockXMLSerializer);
    }

    /**
     * page with resources but no elements: serialize resources
     *
     * @test
     */
    public function pageWithResources()
    {
        $this->page->setResources(array('foo' => 'stdClass'));
        $this->pageXMLGenerator->setPage($this->page);
        
        $this->mockRequest->expects($this->never())->method('isCancelled');
        $this->mockInjector->expects($this->never())->method('handleInjections');
        $resource = new stdClass();
        $this->mockInjector->expects($this->once())->method('getInstance')->with($this->equalTo('stdClass'))->will($this->returnValue($resource));
        $this->mockXMLSerializer->expects($this->at(0))
                                ->method('serialize')
                                ->with($this->equalTo(array()), $this->equalTo($this->mockXMLStreamWriter));
        $this->mockXMLSerializer->expects($this->at(1))
                                ->method('serialize')
                                ->with($this->equalTo($resource), $this->equalTo($this->mockXMLStreamWriter), $this->equalTo(array(stubXMLSerializer::OPT_ROOT_TAG => 'foo')));
        $this->mockXMLStreamWriter->expects($this->once())->method('writeStartElement');
        $this->mockXMLStreamWriter->expects($this->once())->method('writeEndElement');
        $this->assertFalse($this->pageXMLGenerator->isCachable());
        $this->assertEquals(array(), $this->pageXMLGenerator->getCacheVars());
        $this->assertEquals(array(), $this->pageXMLGenerator->getUsedFiles());
        $this->pageXMLGenerator->generate($this->mockXMLStreamWriter, $this->mockXMLSerializer);
    }
}
?>