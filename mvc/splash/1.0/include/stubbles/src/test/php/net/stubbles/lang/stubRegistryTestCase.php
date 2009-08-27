<?php
/**
 * Tests for net::stubbles::lang::stubRegistry.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  lang_test
 */
stubClassLoader::load('net::stubbles::lang::stubRegistry');
/**
 * Tests for net::stubbles::lang::stubRegistry.
 *
 * @package     stubbles
 * @subpackage  lang_test
 * @group       lang
 */
class stubRegistryTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * assure that values are returned as they are put into the registry
     *
     * @test
     */
    public function normal()
    {
        $this->assertNull(stubRegistry::get('test'));
        $this->assertEquals('foo', stubRegistry::get('test', 'foo'));
        $this->assertFalse(stubRegistry::has('test'));
        $this->assertEquals(array(), stubRegistry::getKeys());
        stubRegistry::set('test', 'test');
        $this->assertTrue(stubRegistry::has('test'));
        $this->assertEquals('test', stubRegistry::get('test'));
        $test = new stdClass();
        stubRegistry::set('test', $test);
        $this->assertTrue(stubRegistry::has('test'));
        $regTest = stubRegistry::get('test');
        $this->assertSame($test, $regTest);
        $this->assertEquals(array('test'), stubRegistry::getKeys());
        stubRegistry::remove('test');
        $this->assertNull(stubRegistry::get('test'));
        $this->assertFalse(stubRegistry::has('test'));
        $this->assertEquals(array(), stubRegistry::getKeys());
    }

    /**
     * assure that values are returned as they are put into the config registry
     *
     * @test
     */
    public function config()
    {
        $this->assertNull(stubRegistry::getConfig('test'));
        $this->assertEquals('foo', stubRegistry::getConfig('test', 'foo'));
        $this->assertFalse(stubRegistry::hasConfig('test'));
        $this->assertEquals(array(), stubRegistry::getConfigKeys());
        stubRegistry::setConfig('test', 'test');
        $this->assertTrue(stubRegistry::hasConfig('test'));
        $this->assertEquals('test', stubRegistry::getConfig('test'));
        $test = new stdClass();
        stubRegistry::setConfig('test', $test);
        $this->assertTrue(stubRegistry::hasConfig('test'));
        $regTest = stubRegistry::getConfig('test');
        $this->assertSame($test, $regTest);
        $this->assertEquals(array('test'), stubRegistry::getConfigKeys());
        stubRegistry::removeConfig('test');
        $this->assertNull(stubRegistry::getConfig('test'));
        $this->assertFalse(stubRegistry::hasConfig('test'));
        $this->assertEquals(array(), stubRegistry::getConfigKeys());
    }
}
?>