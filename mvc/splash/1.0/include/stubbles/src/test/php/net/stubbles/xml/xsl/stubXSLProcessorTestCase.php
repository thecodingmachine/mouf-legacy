<?php
/**
 * Test for net::stubbles::xml::xsl::stubXSLProcessor.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  xml_xsl_test
 */
stubClassLoader::load('net::stubbles::xml::xsl::stubXSLProcessor');
/**
 * Helper class for the test.
 *
 * @package     stubbles
 * @subpackage  xml_xsl_test
 */
class TeststubXSLProcessor extends stubXSLProcessor
{
    /**
     * mocked xslt processor
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    public static $mockXSLTProcessor;

    /**
     * access protected method
     */
    public function doCallbacks()
    {
        parent::registerCallbacks();
    }

    /**
     * return xml document to be transformed
     *
     * @return  DOMDocument
     */
    public function getXMLDocument()
    {
        return $this->document;
    }

    /**
     * overwrite creation method to inject the mock object
     */
    protected function createXSLTProcessor()
    {
        $this->xsltProcessor = self::$mockXSLTProcessor;
    }
}
/**
 * Test for net::stubbles::xml::xsl::stubXSLProcessor.
 *
 * @package     stubbles
 * @subpackage  xml_xsl_test
 * @group       xml
 * @group       xml_xsl
 */
class stubXSLProcessorTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubXSLProcessor
     */
    protected $xslProcessor;
    /**
     * a mock for the XSLTProcessor
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockXSLTProcessor;
    /**
     * a dom document to test
     *
     * @var  DOMDocument
     */
    protected $document;

    /**
     * set up test environment
     */
    public function setUp()
    {
        if (extension_loaded('xsl') === false) {
            $this->markTestSkipped('net::stubbles::xml::xsl::stubXSLProcessor requires PHP-extension "xsl".');
        }
        
        $this->mockXSLTProcessor = $this->getMock('XSLTProcessor');
        TeststubXSLProcessor::$mockXSLTProcessor = $this->mockXSLTProcessor;
        $this->xslProcessor = new TeststubXSLProcessor();
        $this->document     = new DOMDocument();
        $this->xslProcessor->onDocument($this->document);
    }

    /**
     * test creation with applyStylesheet()
     *
     * @test
     */
    public function creationWithApplyStylesheet()
    {
        $stylesheet = new DOMDocument();
        $xslProcessor = stubXSLProcessor::applyStylesheet($stylesheet);
        $this->assertType('stubXSLProcessor', $xslProcessor);
        $this->assertEquals(array($stylesheet), $xslProcessor->getStylesheets());
    }

    /**
     * test creation with applyStylesheetFromFile()
     *
     * @test
     */
    public function creationWithApplyStylesheetFromFile()
    {
        $xslProcessor = stubXSLProcessor::applyStylesheetFromFile(TEST_SRC_PATH . '/resources/xsl/testfile.xsl');
        $this->assertType('stubXSLProcessor', $xslProcessor);
        $this->assertEquals(1, count($xslProcessor->getStylesheets()));
    }

    /**
     * onDocument() should returns its instance
     *
     * @test
     */
    public function onDocumentReturnsItself()
    {
        $this->assertSame($this->xslProcessor, $this->xslProcessor->onDocument($this->document));
    }

    /**
     * onXMLFile() should returns its instance
     *
     * @test
     */
    public function onXMLFileReturnsItself()
    {
        $this->assertSame($this->xslProcessor, $this->xslProcessor->onXMLFile(TEST_SRC_PATH . '/resources/xsl/testfile.xsl'));
        $this->assertType('DOMDocument', $this->xslProcessor->getXMLDocument());
    }

    /**
     * onXMLFile() throws in exception in case file can not be read
     *
     * @test
     * @expectedException  stubIOException
     */
    public function onXMLFileThrowsException()
    {
        $this->xslProcessor->onXMLFile(TEST_SRC_PATH . '/resources/xsl/doesNotExist.xsl');
    }

    /**
     * test importing a stylesheet
     *
     * @test
     */
    public function andApplyStylesheet()
    {
        $stylesheet = new DOMDocument();
        $this->assertSame($this->xslProcessor, $this->xslProcessor->andApplyStylesheet($stylesheet));
        $this->assertEquals(array($stylesheet), $this->xslProcessor->getStylesheets());
    }

    /**
     * test importing a stylesheet from a file
     *
     * @test
     */
    public function andApplyStylesheetFromFile()
    {
        $this->assertSame($this->xslProcessor, $this->xslProcessor->andApplyStylesheetFromFile(TEST_SRC_PATH . '/resources/xsl/testfile.xsl'));
        $this->assertEquals(1, count($this->xslProcessor->getStylesheets()));
    }

    /**
     * importing a stylesheet from a file which can not be read throws an exception
     *
     * @test
     * @expectedException  stubIOException
     */
    public function andApplyStylesheetFromFileFails()
    {
        $this->xslProcessor->andApplyStylesheetFromFile(TEST_SRC_PATH . '/resources/xsl/doesNotExist.xsl');
    }

    /**
     * test setting and removing single parameters
     *
     * @test
     */
    public function singleParameters()
    {
        $this->mockXSLTProcessor->expects($this->at(0))
                                ->method('setParameter')
                                ->with($this->equalTo('foo'), $this->equalTo('bar'), $this->equalTo('baz'))
                                ->will($this->returnValue(true));
        $this->mockXSLTProcessor->expects($this->at(1))
                                ->method('setParameter')
                                ->with($this->equalTo('foo'), $this->equalTo('foo'), $this->equalTo('bar'))
                                ->will($this->returnValue(true));
        $this->assertSame($this->xslProcessor, $this->xslProcessor->withParameter('foo', 'bar', 'baz'));
        $this->assertTrue($this->xslProcessor->hasParameter('foo', 'bar'));
        $this->assertEquals('baz', $this->xslProcessor->getParameter('foo', 'bar'));
        $this->assertFalse($this->xslProcessor->hasParameter('foo', 'baz'));
        $this->assertNull($this->xslProcessor->getParameter('foo', 'baz'));
        $this->assertSame($this->xslProcessor, $this->xslProcessor->withParameter('foo', 'foo', 'bar'));
        $this->assertTrue($this->xslProcessor->hasParameter('foo', 'bar'));
        $this->assertTrue($this->xslProcessor->hasParameter('foo', 'foo'));
        $this->assertEquals('bar', $this->xslProcessor->getParameter('foo', 'foo'));
        $this->assertEquals(array('bar' => 'baz', 'foo' => 'bar'), $this->xslProcessor->getParameters('foo'));
        $this->assertEquals(array(), $this->xslProcessor->getParameters('bar'));
        $this->assertEquals(array('foo'), $this->xslProcessor->getParameterNamespaces());
        
        $this->mockXSLTProcessor->expects($this->at(0))
                                ->method('removeParameter')
                                ->with($this->equalTo('foo'), $this->equalTo('bar'))
                                ->will($this->returnValue(false));
        $this->mockXSLTProcessor->expects($this->at(1))
                                ->method('removeParameter')
                                ->with($this->equalTo('foo'), $this->equalTo('bar'))
                                ->will($this->returnValue(true));
        $this->mockXSLTProcessor->expects($this->at(2))
                                ->method('removeParameter')
                                ->with($this->equalTo('foo'), $this->equalTo('foo'))
                                ->will($this->returnValue(true));
        $this->assertFalse($this->xslProcessor->removeParameter('foo', 'bar'));
        $this->assertTrue($this->xslProcessor->hasParameter('foo', 'bar'));
        $this->assertTrue($this->xslProcessor->removeParameter('foo', 'bar'));
        $this->assertFalse($this->xslProcessor->hasParameter('foo', 'bar'));
        $this->assertNull($this->xslProcessor->getParameter('foo', 'bar'));
        $this->assertTrue($this->xslProcessor->removeParameter('foo', 'baz'));
        $this->assertTrue($this->xslProcessor->removeParameter('foo', 'foo'));
        $this->assertNull($this->xslProcessor->getParameter('foo', 'foo'));
        $this->assertEquals(array(), $this->xslProcessor->getParameters('foo'));
        $this->assertEquals(array(), $this->xslProcessor->getParameterNamespaces());
    }

    /**
     * failing to set a parameter throws an exception
     *
     * @test
     * @expectedException  stubXSLProcessorException
     */
    public function singleParametersFails()
    {
        $this->mockXSLTProcessor->expects($this->once())
                                ->method('setParameter')
                                ->with($this->equalTo('foo'), $this->equalTo('bar'), $this->equalTo('baz'))
                                ->will($this->returnValue(false));

        $this->xslProcessor->withParameter('foo', 'bar', 'baz');
    }

    /**
     * test setting and removing array parameters
     *
     * @test
     */
    public function arrayParameters()
    {
        $this->mockXSLTProcessor->expects($this->at(0))
                                ->method('setParameter')
                                ->with($this->equalTo('baz'), $this->equalTo(array('baz' => 'bar')))
                                ->will($this->returnValue(true));
        $this->mockXSLTProcessor->expects($this->at(1))
                                ->method('setParameter')
                                ->with($this->equalTo('baz'), $this->equalTo(array('foo' => 'bar')))
                                ->will($this->returnValue(true));
        $this->assertSame($this->xslProcessor,$this->xslProcessor->withParameters('baz', array('baz' => 'bar')));
        $this->assertSame($this->xslProcessor,$this->xslProcessor->withParameters('baz', array('foo' => 'bar')));
        $this->assertTrue($this->xslProcessor->hasParameter('baz', 'baz'));
        $this->assertTrue($this->xslProcessor->hasParameter('baz', 'foo'));
        $this->assertFalse($this->xslProcessor->hasParameter('baz', 'bar'));
        $this->assertEquals(array('baz' => 'bar', 'foo' => 'bar'), $this->xslProcessor->getParameters('baz'));
        $this->assertEquals(array(), $this->xslProcessor->getParameters('bar'));
        $this->assertEquals(array('baz'), $this->xslProcessor->getParameterNamespaces());
        
        $this->mockXSLTProcessor->expects($this->at(0))
                                ->method('removeParameter')
                                ->with($this->equalTo('baz'), $this->equalTo('foo'))
                                ->will($this->returnValue(false));
        $this->mockXSLTProcessor->expects($this->at(1))
                                ->method('removeParameter')
                                ->with($this->equalTo('baz'), $this->equalTo('baz'))
                                ->will($this->returnValue(true));
        $this->mockXSLTProcessor->expects($this->at(2))
                                ->method('removeParameter')
                                ->with($this->equalTo('baz'), $this->equalTo('foo'))
                                ->will($this->returnValue(true));
        $this->assertEquals(array('foo' => false), $this->xslProcessor->removeParameters('baz', array('foo')));
        $this->assertTrue($this->xslProcessor->hasParameter('baz', 'foo'));
        $this->assertEquals(array('baz' => true), $this->xslProcessor->removeParameters('baz', array('baz')));
        $this->assertFalse($this->xslProcessor->hasParameter('baz', 'bar'));
        $this->assertEquals(array('baz' => true), $this->xslProcessor->removeParameters('baz', array('baz')));
        $this->xslProcessor->removeParameter('baz', 'foo');
        $this->assertEquals(array('baz' => true), $this->xslProcessor->removeParameters('baz', array('baz')));
        $this->assertEquals(array(), $this->xslProcessor->getParameters('baz'));
        $this->assertEquals(array(), $this->xslProcessor->getParameterNamespaces());
    }

    /**
     * failing to set a list of parameters throws an exception
     *
     * @test
     * @expectedException  stubXSLProcessorException
     */
    public function arrayParametersFails()
    {
        $this->mockXSLTProcessor->expects($this->once())
                                ->method('setParameter')
                                ->with($this->equalTo('baz'), $this->equalTo(array('bar' => 'baz')))
                                ->will($this->returnValue(false));
        $this->xslProcessor->withParameters('baz', array('bar' => 'baz'));
    }

    /**
     * test that a registered callback is transfered to the stubXSLCallback class
     *
     * @test
     */
    public function registerCallback()
    {
        $class = $this->getMock('stubObject');
        $this->assertSame($this->xslProcessor, $this->xslProcessor->usingCallback('foo', $class));
        $this->xslProcessor->doCallbacks();
        $xslCallback = stubXSLCallback::getInstance();
        $this->assertTrue($xslCallback->hasCallback('foo'));
        $this->assertEquals($class, $xslCallback->getCallback('foo'));
    }

    /**
     * test that cloning works as expected
     *
     * @test
     */
    public function cloneInstance()
    {
        $anotherMockXSLTProcessor                = $this->getMock('XSLTProcessor');
        TeststubXSLProcessor::$mockXSLTProcessor = $anotherMockXSLTProcessor;
        $this->xslProcessor->withParameter('foo', 'bar', 'baz');
        $stylesheet = new DOMDocument();
        $this->xslProcessor->andApplyStylesheet($stylesheet);
        $this->mockXSLTProcessor->expects($this->never())->method('setParameter');
        $anotherMockXSLTProcessor->expects($this->once())
                                 ->method('setParameter')
                                 ->with($this->equalTo('foo'), $this->equalTo(array('bar' => 'baz')));
        $this->mockXSLTProcessor->expects($this->never())->method('importStylesheet');
        $anotherMockXSLTProcessor->expects($this->once())
                                 ->method('importStylesheet')
                                 ->with($this->equalTo($stylesheet));
        $clonedXSLProcessor = clone $this->xslProcessor;
        $this->assertSame($this->document, $this->xslProcessor->getXMLDocument());
        $this->assertNull($clonedXSLProcessor->getXMLDocument());
    }

    /**
     * test transforming a document
     *
     * @test
     */
    public function transformToDoc()
    {
        $this->mockXSLTProcessor->expects($this->once())
                                ->method('transformToDoc')
                                ->with($this->equalTo($this->document))
                                ->will($this->returnValue(new DOMDocument()));
        $this->assertType('DOMDocument', $this->xslProcessor->toDoc());
    }

    /**
     * test transforming a document
     *
     * @test
     * @expectedException  stubXSLProcessorException
     */
    public function transformToDocFails()
    {
        $this->mockXSLTProcessor->expects($this->once())
                                ->method('transformToDoc')
                                ->will($this->returnValue(false));
        $this->xslProcessor->toDoc();
    }

    /**
     * test transforming a document
     *
     * @test
     */
    public function transformToURI()
    {
        $this->mockXSLTProcessor->expects($this->exactly(2))
                                ->method('transformToUri')
                                ->with($this->equalTo($this->document))
                                ->will($this->onConsecutiveCalls(4555, 0));
        $this->assertEquals(4555, $this->xslProcessor->toURI('foo'));
        $this->assertEquals(0, $this->xslProcessor->toURI('foo'));
    }

    /**
     * test transforming a document
     *
     * @test
     * @expectedException  stubXSLProcessorException
     */
    public function transformToURIFails()
    {
        $this->mockXSLTProcessor->expects($this->once())
                                ->method('transformToUri')
                                ->with($this->equalTo($this->document))
                                ->will($this->returnValue(false));
        $this->xslProcessor->toURI('foo');
    }

    /**
     * test transforming a document
     *
     * @test
     */
    public function transformToXML()
    {
        $this->mockXSLTProcessor->expects($this->exactly(2))
                                ->method('transformToXml')
                                ->with($this->equalTo($this->document))
                                ->will($this->onConsecutiveCalls('<foo>', ''));
        $this->assertEquals('<foo>', $this->xslProcessor->toXML());
        $this->assertEquals('', $this->xslProcessor->toXML());
    }

    /**
     * test transforming a document
     *
     * @test
     * @expectedException  stubXSLProcessorException
     */
    public function transformToXMLFails()
    {
        $this->mockXSLTProcessor->expects($this->any())
                                ->method('transformToXml')
                                ->with($this->equalTo($this->document))
                                ->will($this->returnValue(false));
        $this->xslProcessor->toXML();
    }
}
?>