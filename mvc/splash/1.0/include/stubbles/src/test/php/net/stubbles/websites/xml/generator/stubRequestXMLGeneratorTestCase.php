<?php
/**
 * Tests for net::stubbles::websites::xml::generator::stubRequestXMLGenerator.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_xml_generator_test
 */
stubClassLoader::load('net::stubbles::websites::xml::generator::stubRequestXMLGenerator');
/**
 * Tests for net::stubbles::websites::xml::generator::stubRequestXMLGenerator.
 *
 * @package     stubbles
 * @subpackage  websites_xml_generator_test
 * @group       websites
 * @group       websites_xml
 */
class stubRequestXMLGeneratorTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubRequestXMLGenerator
     */
    protected $requestXMLGenerator;
    /**
     * mocked request instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockRequest;
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
        $this->requestXMLGenerator = new stubRequestXMLGenerator($this->mockRequest);
        $this->mockXMLStreamWriter = $this->getMock('stubXMLStreamWriter');
        $this->mockXMLSerializer   = $this->getMock('stubXMLSerializer');
    }

    /**
     * the request data is always cachable - it is important that the relevant
     * page elements decide about cachability and cache variables
     *
     * @test
     */
    public function cachingMethods()
    {
        $this->assertTrue($this->requestXMLGenerator->isCachable());
        $this->assertEquals(array(), $this->requestXMLGenerator->getCacheVars());
        $this->assertEquals(array(), $this->requestXMLGenerator->getUsedFiles());
        
    }

    /**
     * no request value errors: request elements stays empty
     *
     * @test
     */
    public function noRequestValueErrors()
    {
        $this->mockXMLStreamWriter->expects($this->once())->method('writeStartElement')->with($this->equalTo('request'));
        $this->mockXMLStreamWriter->expects($this->once())->method('writeEndElement');
        $this->mockRequest->expects($this->once())->method('getValueErrors')->will($this->returnValue(array()));
        $this->mockXMLSerializer->expects($this->never())->method('serialize');
        $this->requestXMLGenerator->generate($this->mockXMLStreamWriter, $this->mockXMLSerializer);
    }

    /**
     * with request value errors: request element are serialized
     *
     * @test
     */
    public function withRequestValueErrors()
    {
        $this->mockXMLStreamWriter->expects($this->at(0))->method('writeStartElement')->with($this->equalTo('request'));
        $this->mockXMLStreamWriter->expects($this->at(1))->method('writeStartElement')->with($this->equalTo('value'));
        $this->mockXMLStreamWriter->expects($this->once())->method('writeAttribute')->with($this->equalTo('name'), $this->equalTo('foo'));
        $this->mockXMLStreamWriter->expects($this->exactly(2))->method('writeEndElement');
        $error = new stdClass();
        $this->mockRequest->expects($this->once())->method('getValueErrors')->will($this->returnValue(array('foo' => array($error))));
        $this->mockXMLSerializer->expects($this->once())->method('serialize')->with($this->equalTo(array($error)), $this->equalTo($this->mockXMLStreamWriter));
        $this->requestXMLGenerator->generate($this->mockXMLStreamWriter, $this->mockXMLSerializer);
    }
}
?>