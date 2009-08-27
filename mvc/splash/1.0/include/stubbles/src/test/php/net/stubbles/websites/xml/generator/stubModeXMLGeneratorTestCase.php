<?php
/**
 * Tests for net::stubbles::websites::xml::generator::stubModeXMLGenerator.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_xml_generator_test
 */
stubClassLoader::load('net::stubbles::websites::xml::generator::stubModeXMLGenerator',
                      'net::stubbles::xml::stubXMLStreamWriterFactory'
);
/**
 * Tests for net::stubbles::websites::xml::generator::stubModeXMLGenerator.
 *
 * @package     stubbles
 * @subpackage  websites_xml_generator_test
 * @group       websites
 * @group       websites_xml
 */
class stubModeXMLGeneratorTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubModeXMLGenerator
     */
    protected $modeXMLGenerator;
    /**
     * xml stream writer instance
     *
     * @var  stubXMLStreamWrite
     */
    protected $xmlStreamWriter;
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
        $this->modeXMLGenerator  = new stubModeXMLGenerator();
        $this->xmlStreamWriter   = stubXMLStreamWriterFactory::createAsAvailable();
        $this->mockXMLSerializer = $this->getMock('stubXMLSerializer');
    }

    /**
     * cache variables should be whether session is new, the name of the variant
     * and if the requestor accepts cookies or not
     *
     * @test
     */
    public function cacheVars()
    {
        $this->assertEquals(array(), $this->modeXMLGenerator->getCacheVars());
    }

    /**
     * no files used for generating the cache content
     *
     * @test
     */
    public function getUsedFiles()
    {
        $this->assertEquals(array(), $this->modeXMLGenerator->getUsedFiles());
    }

    /**
     * requestor accepts cookies
     *
     * @test
     */
    public function prodMode()
    {
        stubMode::setCurrent(stubMode::$PROD);
        $this->assertTrue($this->modeXMLGenerator->isCachable());
        $this->modeXMLGenerator->generate($this->xmlStreamWriter, $this->mockXMLSerializer);
        $doc = $this->xmlStreamWriter->asXML();
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n" .
'<mode>' . 
  '<name>PROD</name>' . 
  '<isCacheEnabled>true</isCacheEnabled>' . 
'</mode>', $doc);
    }

    /**
     * requestor does not accept cookies
     *
     * @test
     */
    public function devMode()
    {
        stubMode::setCurrent(stubMode::$DEV);
        $this->assertFalse($this->modeXMLGenerator->isCachable());
        $this->modeXMLGenerator->generate($this->xmlStreamWriter, $this->mockXMLSerializer);
        $doc = $this->xmlStreamWriter->asXML();
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n" .
'<mode>' . 
  '<name>DEV</name>' . 
  '<isCacheEnabled>false</isCacheEnabled>' . 
'</mode>', $doc);
    }
}
?>