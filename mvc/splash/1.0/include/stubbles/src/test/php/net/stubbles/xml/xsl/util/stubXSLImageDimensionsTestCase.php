<?php
/**
 * Test for net::stubbles::xml::xsl::util::stubXSLImageDimensions.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  xml_xsl_util_test
 */
stubClassLoader::load('net::stubbles::ioc::annotations::stubInjectAnnotation',
                      'net::stubbles::xml::xsl::util::stubXSLImageDimensions',
                      'net::stubbles::xml::xsl::stubXSLMethodAnnotation',
                      'net::stubbles::xml::stubDomXMLStreamWriter'
);
/**
 * Test for net::stubbles::xml::xsl::util::stubXSLImageDimensions.
 *
 * @package     stubbles
 * @subpackage  xml_xsl_util_test
 * @group       xml
 * @group       xml_xsl
 */
class stubXSLImageDimensionsTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubXSLImageDimensions
     */
    protected $xslImageDimensions;
    /**
     * instance to test
     *
     * @var  stubDomXMLStreamWriter
     */
    protected $mockXMLStreamWriter;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->mockXMLStreamWriter = new stubDomXMLStreamWriter();
        $this->xslImageDimensions  = new stubXSLImageDimensions($this->mockXMLStreamWriter);
    }

    /**
     * make sure annotations are present
     *
     * @test
     */
    public function annotationsPresent()
    {
        $this->assertTrue($this->xslImageDimensions
                               ->getClass()
                               ->getConstructor()
                               ->hasAnnotation('Inject')
        );
        $this->assertTrue($this->xslImageDimensions
                               ->getClass()
                               ->getMethod('getImageDimensions')
                               ->hasAnnotation('XSLMethod')
        );
    }

    /**
     * test that a non-existing file throws a stubXSLCallbackException
     *
     * @test
     * @expectedException  stubXSLCallbackException
     */
    public function nonExistingImage()
    {
        $this->xslImageDimensions->getImageDimensions(TEST_SRC_PATH . '/resources/img/doesNotExist.jpg');
    }

    /**
     * test that an invalid file throws a stubXSLCallbackException
     *
     * @test
     * @expectedException  stubXSLCallbackException
     */
    public function invalidImage()
    {
        $this->xslImageDimensions->getImageDimensions(TEST_SRC_PATH . '/resources/img/invalid.gif');
    }

    /**
     * test that a correct image delivers correct data
     *
     * @test
     */
    public function correctImage()
    {
        $doc =  $this->xslImageDimensions->getImageDimensions(TEST_SRC_PATH . '/resources/img/stubbles.png');
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n<image><width>132</width><height>113</height><type>PNG</type><mime>image/png</mime></image>\n", $doc->saveXML());
    }
}
?>