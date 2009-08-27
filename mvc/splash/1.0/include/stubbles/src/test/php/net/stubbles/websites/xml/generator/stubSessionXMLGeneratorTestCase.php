<?php
/**
 * Tests for net::stubbles::websites::xml::generator::stubSessionXMLGenerator.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_xml_generator_test
 */
stubClassLoader::load('net::stubbles::websites::xml::generator::stubSessionXMLGenerator',
                      'net::stubbles::xml::stubXMLStreamWriterFactory'
);
/**
 * Tests for net::stubbles::websites::xml::generator::stubSessionXMLGenerator.
 *
 * @package     stubbles
 * @subpackage  websites_xml_generator_test
 * @group       websites
 * @group       websites_xml
 */
class stubSessionXMLGeneratorTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubSessionXMLGenerator
     */
    protected $sessionXMLGenerator;
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
        $this->mockRequest         = $this->getMock('stubRequest');
        $this->mockSession         = $this->getMock('stubSession');
        $this->sessionXMLGenerator = new stubSessionXMLGenerator($this->mockRequest, $this->mockSession);
        $this->xmlStreamWriter     = stubXMLStreamWriterFactory::createAsAvailable();
        $this->mockXMLSerializer   = $this->getMock('stubXMLSerializer');
    }

    /**
     * cache variables should be whether session is new, the name of the variant
     * and if the requestor accepts cookies or not
     *
     * @test
     */
    public function isCachable()
    {
        $this->assertTrue($this->sessionXMLGenerator->isCachable());
    }

    /**
     * cache variables should be whether session is new, the name of the variant
     * and if the requestor accepts cookies or not
     *
     * @test
     */
    public function cacheVars()
    {
        $this->mockRequest->expects($this->once())->method('acceptsCookies')->will($this->returnValue(true));
        $this->mockSession->expects($this->once())->method('isNew')->will($this->returnValue(true));
        $this->mockSession->expects($this->once())->method('getValue')->will($this->returnValue('variant_name'));
        $this->assertEquals(array('isNew'          => true,
                                  'variant'        => 'variant_name',
                                  'acceptsCookies' => true
                            ),
                            $this->sessionXMLGenerator->getCacheVars()
        );
    }

    /**
     * no files used for generating the cache content
     *
     * @test
     */
    public function getUsedFiles()
    {
        $this->assertEquals(array(), $this->sessionXMLGenerator->getUsedFiles());
    }

    /**
     * requestor accepts cookies
     *
     * @test
     */
    public function acceptsCookies()
    {
        $this->mockRequest->expects($this->once())->method('acceptsCookies')->will($this->returnValue(true));
        $this->mockSession->expects($this->once())->method('isNew')->will($this->returnValue(true));
        $this->mockSession->expects($this->exactly(2))->method('getValue')->will($this->onConsecutiveCalls('variant_name', 'variant_alias'));
        $this->sessionXMLGenerator->generate($this->xmlStreamWriter, $this->mockXMLSerializer);
        $doc = $this->xmlStreamWriter->asXML();
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n" .
'<session>' . 
  '<acceptsCookies>true</acceptsCookies>' . 
  '<id>$SESSION_ID</id>' . 
  '<name>$SESSION_NAME</name>' . 
  '<isNew>true</isNew>' . 
  '<variant>' . 
    '<name>variant_name</name>' . 
    '<alias>variant_alias</alias>' . 
  '</variant>' . 
'</session>', $doc);
    }

    /**
     * requestor does not accept cookies
     *
     * @test
     */
    public function doesNotAcceptCookies()
    {
        $this->mockRequest->expects($this->once())->method('acceptsCookies')->will($this->returnValue(false));
        $this->mockSession->expects($this->once())->method('isNew')->will($this->returnValue(false));
        $this->mockSession->expects($this->exactly(2))->method('getValue')->will($this->returnValue(null));
        $this->sessionXMLGenerator->generate($this->xmlStreamWriter, $this->mockXMLSerializer);
        $doc = $this->xmlStreamWriter->asXML();
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n" .
'<session>' . 
  '<acceptsCookies>false</acceptsCookies>' . 
  '<id>$SESSION_ID</id>' . 
  '<name>$SESSION_NAME</name>' . 
  '<isNew>false</isNew>' . 
  '<variant>' . 
    '<name></name>' . 
    '<alias></alias>' . 
  '</variant>' . 
'</session>', $doc);
    }
}
?>