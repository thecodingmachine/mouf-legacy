<?php
/**
 * Tests for net::stubbles::websites::xml::page::stubXMLPageElementDecorator.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_xml_page_test
 */
stubClassLoader::load('net::stubbles::websites::xml::page::stubXMLPageElementDecorator');
/**
 * Helper class for the test.
 *
 * @package     stubbles
 * @subpackage  websites_xml_page_test
 */
class TeststubXMLPageElementDecorator extends stubXMLPageElementDecorator
{
    // intentionally empty
}
/**
 * Tests for net::stubbles::websites::xml::page::stubXMLPageElementDecorator.
 *
 * @package     stubbles
 * @subpackage  websites_xml_page_test
 * @group       websites
 * @group       websites_xml
 */
class stubXMLPageElementDecoratorTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubXMLPageElementDecorator
     */
    protected $xmlPageElementDecorator;
    /**
     * mocked decorated page element
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockXMLPageElement;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->mockXMLPageElement      = $this->getMock('stubXMLPageElement');
        $this->xmlPageElementDecorator = new TeststubXMLPageElementDecorator($this->mockXMLPageElement);
    }

    /**
     * name should be set and get in decorated page element
     *
     * @test
     */
    public function name()
    {
        $this->mockXMLPageElement->expects($this->once())->method('setName')->with($this->equalTo('foo'));
        $this->mockXMLPageElement->expects($this->once())->method('getName')->will($this->returnValue('foo'));
        $this->xmlPageElementDecorator->setName('foo');
        $this->assertEquals('foo', $this->xmlPageElementDecorator->getName());
    }

    /**
     * decorated class name should be returned as required
     *
     * @test
     */
    public function decoratedClassNameShouldBeReturnedAsRequired()
    {
        $this->mockXMLPageElement->expects($this->once())->method('getClassName')->will($this->returnValue('foo'));
        $this->assertEquals(array('foo'), $this->xmlPageElementDecorator->getRequiredClassNames());
    }

    /**
     * decorated page element should be initialized
     *
     * @test
     */
    public function decoratedPageElementShouldBeInitialized()
    {
        $mockRequest  = $this->getMock('stubRequest');
        $mockSession  = $this->getMock('stubSession');
        $mockResponse = $this->getMock('stubResponse');
        $this->mockXMLPageElement->expects($this->once())
                                 ->method('init')
                                 ->with($this->equalTo($mockRequest), $this->equalTo($mockSession), $this->equalTo($mockResponse));
        $this->xmlPageElementDecorator->init($mockRequest, $mockSession, $mockResponse);
    }

    /**
     * decorated page element decides availability
     *
     * @test
     */
    public function decoratedPageElementDecidesAvailability()
    {
        $this->mockXMLPageElement->expects($this->once())->method('isAvailable')->will($this->returnValue(true));
        $this->assertTrue($this->xmlPageElementDecorator->isAvailable());
    }

    /**
     * decorated page element decides cachability
     *
     * @test
     */
    public function decoratedPageElementDecidesCachability()
    {
        $this->mockXMLPageElement->expects($this->once())->method('isCachable')->will($this->returnValue(true));
        $this->assertTrue($this->xmlPageElementDecorator->isCachable());
    }

    /**
     * decorated page elements' cache variables should be returned
     *
     * @test
     */
    public function decoratedPageElementsCacheVarsShouldBeReturned()
    {
        $this->mockXMLPageElement->expects($this->once())->method('getCacheVars')->will($this->returnValue(array()));
        $this->assertEquals(array(), $this->xmlPageElementDecorator->getCacheVars());
    }

    /**
     * decorated page elements' used files should be returned
     *
     * @test
     */
    public function decoratedPageElementsUsedFilesShouldBeReturned()
    {
        $this->mockXMLPageElement->expects($this->once())->method('getUsedFiles')->will($this->returnValue(array()));
        $this->assertEquals(array(), $this->xmlPageElementDecorator->getUsedFiles());
    }

    /**
     * decorated page elements' process() method should be called
     *
     * @test
     */
    public function decoratedPageElementsProcessMethodShouldBeCalled()
    {
        $this->mockXMLPageElement->expects($this->once())->method('process')->will($this->returnValue(array()));
        $this->assertEquals(array(), $this->xmlPageElementDecorator->process());
    }
    /**
     * decorated page elements' form values should be returned
     *
     * @test
     */
    public function decoratedPageElementsFormValuesShouldBeReturned()
    {
        $this->mockXMLPageElement->expects($this->once())->method('getFormValues')->will($this->returnValue(array()));
        $this->assertEquals(array(), $this->xmlPageElementDecorator->getFormValues());
    }
}
?>