<?php
/**
 * Test for net::stubbles::xml::xsl::util::stubXSLRequestParams.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  xml_xsl_util_test
 */
stubClassLoader::load('net::stubbles::ioc::annotations::stubInjectAnnotation',
                      'net::stubbles::xml::xsl::util::stubXSLRequestParams',
                      'net::stubbles::xml::xsl::stubXSLMethodAnnotation',
                      'net::stubbles::xml::stubDomXMLStreamWriter'
);
/**
 * Test for net::stubbles::xml::xsl::util::stubXSLRequestParams.
 *
 * @package     stubbles
 * @subpackage  xml_xsl_util_test
 * @group       xml
 * @group       xml_xsl
 */
class stubXSLRequestParamsTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubXSLRequestParams
     */
    protected $xslRequestParams;
    /**
     * instance to test
     *
     * @var  stubDomXMLStreamWriter
     */
    protected $mockXMLStreamWriter;
    /**
     * mocked request instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockRequest;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->mockXMLStreamWriter = new stubDomXMLStreamWriter();
        $this->mockRequest         = $this->getMock('stubRequest');
        $this->xslRequestParams    = new stubXSLRequestParams($this->mockXMLStreamWriter);
        $this->xslRequestParams->setRequest($this->mockRequest);
    }

    /**
     * make sure annotations are present
     *
     * @test
     */
    public function annotationsPresent()
    {
        $this->assertTrue($this->xslRequestParams
                               ->getClass()
                               ->getConstructor()
                               ->hasAnnotation('Inject')
        );
        $this->assertTrue($this->xslRequestParams
                               ->getClass()
                               ->getMethod('setRequest')
                               ->hasAnnotation('Inject')
        );
        $this->assertTrue($this->xslRequestParams
                               ->getClass()
                               ->getMethod('getQueryString')
                               ->hasAnnotation('XSLMethod')
        );
    }

    /**
     * query string should be returned, but without processor and page params
     *
     * @test
     */
    public function correctQueryString()
    {
        $this->mockRequest->expects($this->once())
                          ->method('getValidatedValue')
                          ->with($this->anything(), $this->equalTo('QUERY_STRING'), $this->equalTo(stubRequest::SOURCE_HEADER))
                          ->will($this->returnValue('processor=xml&page=article&article_id=89&=&test=foo'));
        $doc =  $this->xslRequestParams->getQueryString();
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n<requestParams>&amp;article_id=89&amp;test=foo</requestParams>\n", $doc->saveXML());
    }

    /**
     * query string should be returned, arrays should stay consistent
     *
     * @test
     */
    public function correctQueryStringArray()
    {
        $this->mockRequest->expects($this->once())
                          ->method('getValidatedValue')
                          ->with($this->anything(), $this->equalTo('QUERY_STRING'), $this->equalTo(stubRequest::SOURCE_HEADER))
                          ->will($this->returnValue('test[foo]=bar&test[bar]=baz'));
        $doc =  $this->xslRequestParams->getQueryString();
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n<requestParams>test[foo]=bar&amp;test[bar]=baz</requestParams>\n", $doc->saveXML());
    }

    /**
     * query string should be returned, arrays should stay consistent
     *
     * @test
     */
    public function correctQueryStringArrayWithProcessorAndPage()
    {
        $this->mockRequest->expects($this->once())
                          ->method('getValidatedValue')
                          ->with($this->anything(), $this->equalTo('QUERY_STRING'), $this->equalTo(stubRequest::SOURCE_HEADER))
                          ->will($this->returnValue('processor=xml&page=article&test[foo]=bar&test[bar]=baz'));
        $doc =  $this->xslRequestParams->getQueryString();
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n<requestParams>&amp;test[foo]=bar&amp;test[bar]=baz</requestParams>\n", $doc->saveXML());
    }
}
?>