<?php
/**
 * Tests for net::stubbles::websites::processors::stubProcessorResolverXJConfFactory.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_processors_test
 */
stubClassLoader::load('net::stubbles::websites::processors::stubProcessorResolverXJConfFactory');
/**
 * Tests for net::stubbles::websites::processors::stubProcessorResolverXJConfFactory.
 *
 * @package     stubbles
 * @subpackage  websites_processors_test
 * @group       websites
 * @group       websites_processors
 */
class stubProcessorResolverXJConfFactoryTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubProcessorResolverXJConfFactory
     */
    protected $processorResolverXJConfFactory;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->processorResolverXJConfFactory = new stubProcessorResolverXJConfFactory();
    }

    /**
     * test descriptor
     *
     * @test
     */
    public function descriptor()
    {
        $this->assertEquals('processors', $this->processorResolverXJConfFactory->getDescriptor(stubXJConfInitializer::DESCRIPTOR_CONFIG));
        $this->assertEquals('processors', $this->processorResolverXJConfFactory->getDescriptor(stubXJConfInitializer::DESCRIPTOR_DEFINITION));
    }

    /**
     * check that the cache data is correct
     *
     * @test
     */
    public function getCacheData()
    {
        $mockProcessorResolver = $this->getMock('stubProcessorResolver');
        $mockProcessorResolver->expects($this->once())->method('getSerialized')->will($this->returnValue('foo'));
        $this->processorResolverXJConfFactory->setResolver($mockProcessorResolver);
        $cacheData = $this->processorResolverXJConfFactory->getCacheData();
        $this->assertEquals(array('resolver' => 'foo'), $cacheData);
    }

    /**
     * check that cache data is used correct
     *
     * @test
     */
    public function setCacheData()
    {
        $mockProcessorResolver = $this->getMock('stubProcessorResolver');
        $cacheData = array('resolver' => $this->getMock('stubSerializedObject', array(), array($this->getMock('stubSerializable'))));
        $cacheData['resolver']->expects($this->once())->method('getUnserialized')->will($this->returnValue($mockProcessorResolver));
        $this->processorResolverXJConfFactory->setCacheData($cacheData);
        $testProcessorResolver = $this->processorResolverXJConfFactory->getResolver();
        $this->assertType(get_class($mockProcessorResolver), $testProcessorResolver);
    }
}
?>