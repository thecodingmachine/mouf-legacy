<?php
/**
 * Tests for net::stubbles::websites::xml::page::stubAbstractXMLPageElement.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_xml_page_test
 */
stubClassLoader::load('net::stubbles::websites::xml::page::stubAbstractXMLPageElement');
class TeststubAbstractXMLPageElement extends stubAbstractXMLPageElement
{
    /**
     * processes the page element
     *
     * @return  mixed  content for page element
     */
    public function process()
    {
        // intentionally empty
    }
}
/**
 * Tests for net::stubbles::websites::xml::page::stubAbstractXMLPageElement.
 *
 * @package     stubbles
 * @subpackage  websites_xml_page_test
 * @group       websites
 * @group       websites_xml
 */
class stubAbstractXMLPageElementTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  TeststubAbstractXMLPageElement
     */
    protected $abstractXmlPageElement;
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
        $this->abstractXmlPageElement = new TeststubAbstractXMLPageElement();
        $this->mockRequest            = $this->getMock('stubRequest');
        $this->abstractXmlPageElement->init($this->mockRequest, $this->getMock('stubSession'), $this->getMock('stubResponse'));
    }

    /**
     * all form values should be returned
     *
     * @test
     */
    public function getFormValues()
    {
        $this->mockRequest->expects($this->once())
                          ->method('getValueKeys')
                          ->will($this->returnValue(array('foo', 'bar', 'baz')));
        $this->mockRequest->expects($this->at(1))
                          ->method('getValidatedValue')
                          ->with($this->anything(), $this->equalTo('foo'))
                          ->will($this->returnValue('fooValue'));
        $this->mockRequest->expects($this->at(2))
                          ->method('getValidatedValue')
                          ->with($this->anything(), $this->equalTo('bar'))
                          ->will($this->returnValue('barValue'));
        $this->mockRequest->expects($this->at(3))
                          ->method('getValidatedValue')
                          ->with($this->anything(), $this->equalTo('baz'))
                          ->will($this->returnValue('bazValue'));
        $this->assertEquals(array('foo' => 'fooValue',
                                  'bar' => 'barValue',
                                  'baz' => 'bazValue'
                            ),
                            $this->abstractXmlPageElement->getFormValues()
        );
    }
}
?>