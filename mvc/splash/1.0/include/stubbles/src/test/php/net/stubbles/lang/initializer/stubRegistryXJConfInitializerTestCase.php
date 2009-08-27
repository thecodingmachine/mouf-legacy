<?php
/**
 * Tests for net::stubbles::lang::initializer::stubRegistryXJConfInitializer.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  lang_initializer_test
 */
stubClassLoader::load('net::stubbles::lang::initializer::stubRegistryXJConfInitializer');
/**
 * Tests for net::stubbles::lang::initializer::stubRegistryXJConfInitializer.
 *
 * @package     stubbles
 * @subpackage  lang_initializer_test
 * @group       lang
 * @group       lang_initializer
 */
class stubRegistryXJConfInitializerTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubRegistryXJConfInitializer
     */
    protected $registryXJConfInitializer;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->registryXJConfInitializer = new stubRegistryXJConfInitializer();
    }

    /**
     * clear up test environment
     */
    public function tearDown()
    {
        foreach (stubRegistry::getConfigKeys() as $key) {
            stubRegistry::removeConfig($key);
        }
    }

    /**
     * test that the descriptor is correct
     *
     * @test
     */
    public function descriptor()
    {
        $this->assertEquals('config', $this->registryXJConfInitializer->getDescriptor(stubXJConfInitializer::DESCRIPTOR_CONFIG));
        $this->assertEquals('config', $this->registryXJConfInitializer->getDescriptor(stubXJConfInitializer::DESCRIPTOR_DEFINITION));
        $this->assertEquals('config', $this->registryXJConfInitializer->getConfigSource());
        $this->registryXJConfInitializer->setConfigSource('test');
        $this->assertEquals('test', $this->registryXJConfInitializer->getDescriptor(stubXJConfInitializer::DESCRIPTOR_CONFIG));
        $this->assertEquals('config', $this->registryXJConfInitializer->getDescriptor(stubXJConfInitializer::DESCRIPTOR_DEFINITION));
        $this->assertEquals('test', $this->registryXJConfInitializer->getConfigSource());
    }

    /**
     * test that the descriptor is correct
     *
     * @test
     * @expectedException  stubIllegalArgumentException
     */
    public function descriptorWithWrongArgument()
    {
        $this->registryXJConfInitializer->getDescriptor('bar');
    }

    /**
     * assure that values are returned correct
     *
     * @test
     */
    public function getCacheData()
    {
        stubRegistry::setConfig('foo', 'bar');
        stubRegistry::setConfig('baz', 313);
        $this->assertEquals(array('foo' => 'bar', 'baz' => 313), $this->registryXJConfInitializer->getCacheData());
    }

    /**
     * assure that values are set correct
     *
     * @test
     */
    public function setCacheData()
    {
        $this->registryXJConfInitializer->setCacheData(array('foo' => 'bar', 'baz' => 313));
        $this->assertEquals(array('foo', 'baz'), stubRegistry::getConfigKeys());
        $this->assertEquals('bar', stubRegistry::getConfig('foo'));
        $this->assertEquals(313, stubRegistry::getConfig('baz'));
    }
}
?>