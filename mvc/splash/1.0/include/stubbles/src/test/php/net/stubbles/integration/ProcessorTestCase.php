<?php
/**
 * Integration test for processors.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  test_integration
 */
stubClassLoader::load('net::stubbles::websites::processors::stubProcessorResolverXJConfFactory');
/**
 * Integration test for processors.
 *
 * @package     stubbles
 * @subpackage  test_integration
 * @group       integration
 */
class ProcessorTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * helper method
     *
     * @return  stubProcessorResolver
     */
    protected function getProcessorResolver()
    {
        $processorResolverFactory = new stubProcessorResolverXJConfFactory();
        $processorResolverFactory->init();
        return $processorResolverFactory->getResolver();
    }

    /**
     * assure that creating a processor resolver returns correct classes
     *
     * @test
     */
    public function processorResolverXJConfFactory()
    {
        $processorResolver = $this->getProcessorResolver();
        $this->assertType('stubProcessorResolver', $processorResolver);
        $processor = $processorResolver->resolve($this->getMock('stubRequest'), $this->getMock('stubSession'), $this->getMock('stubResponse'));
        $this->assertType('stubProcessor', $processor);
        
        // cached
        $processorResolver = $this->getProcessorResolver();
        $this->assertType('stubProcessorResolver', $processorResolver);
        $processor = $processorResolver->resolve($this->getMock('stubRequest'), $this->getMock('stubSession'), $this->getMock('stubResponse'));
        $this->assertType('stubProcessor', $processor);
    }
}
?>