<?php
/**
 * Test for net::stubbles::xml::xsl::util::stubXSLDateFormatter.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  xml_xsl_util_test
 */
stubClassLoader::load('net::stubbles::ioc::annotations::stubInjectAnnotation',
                      'net::stubbles::xml::xsl::util::stubXSLDateFormatter',
                      'net::stubbles::xml::xsl::stubXSLMethodAnnotation',
                      'net::stubbles::xml::stubDomXMLStreamWriter'
);
/**
 * Test for net::stubbles::xml::xsl::util::stubXSLDateFormatter.
 *
 * @package     stubbles
 * @subpackage  xml_xsl_util_test
 * @group       xml
 * @group       xml_xsl
 */
class stubXSLDateFormatterTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubXSLDateFormatter
     */
    protected $xslDateFormatter;
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
        $this->xslDateFormatter    = new stubXSLDateFormatter($this->mockXMLStreamWriter);
    }

    /**
     * make sure annotations are present
     *
     * @test
     */
    public function annotationsPresent()
    {
        $this->assertTrue($this->xslDateFormatter
                               ->getClass()
                               ->getConstructor()
                               ->hasAnnotation('Inject')
        );
        $this->assertTrue($this->xslDateFormatter
                               ->getClass()
                               ->getMethod('formatDate')
                               ->hasAnnotation('XSLMethod')
        );
        $this->assertTrue($this->xslDateFormatter
                               ->getClass()
                               ->getMethod('formatLocaleDate')
                               ->hasAnnotation('XSLMethod')
        );
    }

    /**
     * test that given timestamp will be used
     *
     * @test
     */
    public function formatDateWithTimestamp()
    {
        $doc = $this->xslDateFormatter->formatDate('Y-m-d', '1216222717');
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n<date timestamp=\"1216222717\">2008-07-16</date>\n", $doc->saveXML());
    }

    /**
     * test that current timestamp will be used
     *
     * @test
     */
    public function formatDateWithoutTimestamp()
    {
        $dateNode = $this->xslDateFormatter->formatDate('Y-m-d')->getElementsByTagName('date')->item(0);
        $this->assertLessThanOrEqual(time(), $dateNode->attributes->getNamedItem('timestamp')->textContent);
        $this->assertEquals(date('Y-m-d'), $dateNode->textContent);
    }

    /**
     * test that given timestamp will be used
     *
     * @test
     */
    public function formatLocaleDateWithTimestamp()
    {
        $doc = $this->xslDateFormatter->formatLocaleDate('%d %b %Y', '1216222717');
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n<date timestamp=\"1216222717\">16 Jul 2008</date>\n", $doc->saveXML());
    }

    /**
     * test that current timestamp will be used
     *
     * @test
     */
    public function formatLocaleDateWithoutTimestamp()
    {
        $dateNode = $this->xslDateFormatter->formatLocaleDate('%d %b %Y')->getElementsByTagName('date')->item(0);
        $this->assertLessThanOrEqual(time(), $dateNode->attributes->getNamedItem('timestamp')->textContent);
        $this->assertEquals(strftime('%d %b %Y'), $dateNode->textContent);
    }
}
?>