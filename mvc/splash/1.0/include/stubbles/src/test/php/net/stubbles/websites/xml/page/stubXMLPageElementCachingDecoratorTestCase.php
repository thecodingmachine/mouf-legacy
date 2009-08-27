<?php
/**
 * Tests for net::stubbles::websites::xml::page::stubXMLPageElementCachingDecorator.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_xml_page_test
 */
stubClassLoader::load('net::stubbles::websites::xml::page::stubXMLPageElementCachingDecorator');
/**
 * Tests for net::stubbles::websites::xml::page::stubXMLPageElementCachingDecorator.
 *
 * @package     stubbles
 * @subpackage  websites_xml_page_test
 * @group       websites
 * @group       websites_xml
 */
class stubXMLPageElementCachingDecoratorTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubXMLPageElementCachingDecorator
     */
    protected $xmlPageElementCachingDecorator;
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
        $this->mockXMLPageElement             = $this->getMock('stubXMLPageElement');
        $this->xmlPageElementCachingDecorator = new stubXMLPageElementCachingDecorator($this->mockXMLPageElement);
    }

    /**
     * caching page element decorator is never cachable in the contect of the whole page
     *
     * @test
     */
    public function isNeverCachable()
    {
        $this->mockXMLPageElement->expects($this->never())->method('isCachable');
        $this->assertFalse($this->xmlPageElementCachingDecorator->isCachable());
    }

    /**
     * make sure that data will be cached and that cached version is the same
     *
     * @test
     */
    public function processPageElement()
    {
        $this->mockXMLPageElement->expects($this->exactly(2))->method('process')->will($this->onConsecutiveCalls('foo', 'bar'));
        $this->assertEquals('foo', $this->xmlPageElementCachingDecorator->process()); // non-cached
        $this->assertEquals('foo', $this->xmlPageElementCachingDecorator->process()); // cached
        $this->xmlPageElementCachingDecorator->setLifeTime(-3600);
        $this->assertEquals('bar', $this->xmlPageElementCachingDecorator->process()); // cache version outdated
    }
}
?>