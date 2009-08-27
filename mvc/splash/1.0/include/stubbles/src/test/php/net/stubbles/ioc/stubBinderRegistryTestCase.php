<?php
/**
 * Test for net::stubbles::ioc::stubBinderRegistry
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ioc_test
 * @version     $Id: stubBinderRegistryTestCase.php 1930 2008-11-13 22:14:41Z mikey $
 */
stubClassLoader::load('net::stubbles::ioc::stubBinderRegistry');
/**
 * Test for net::stubbles::ioc::stubBinderRegistry
 *
 * @package     stubbles
 * @subpackage  ioc_test
 * @group       ioc
 */
class stubBinderRegistryTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * set up test environment
     */
    public function setUp()
    {
        stubRegistry::set(stubBinder::REGISTRY_KEY, null);
    }

    /**
     * clean up test environment
     */
    public function tearDown()
    {
        stubRegistry::set(stubBinder::REGISTRY_KEY, null);
    }

    /**
     * hasInstance() returns false if registry does not contain a binder
     *
     * @test
     */
    public function hasInstanceWithoutInstanceInRegistryReturnsFalse()
    {
        $this->assertFalse(stubBinderRegistry::hasInstance());
    }

    /**
     * hasInstance() returns true if registry contains a binder
     *
     * @test
     */
    public function hasInstanceWithInstanceInRegistryReturnsTrue()
    {
        stubRegistry::set(stubBinder::REGISTRY_KEY, new stubBinder());
        $this->assertTrue(stubBinderRegistry::hasInstance());
    }

    /**
     * get() throws runtime exception if no binder instance is in registry
     *
     * @test
     * @expectedException  stubRuntimeException
     */
    public function getWithoutInstanceInRegistry()
    {
        stubBinderRegistry::get();
    }

    /**
     * get() returns binder instance from registry
     *
     * @test
     */
    public function getWithInstanceInRegistry()
    {
        $binder = new stubBinder();
        stubRegistry::set(stubBinder::REGISTRY_KEY, $binder);
        $this->assertSame($binder, stubBinderRegistry::get());
    }

    /**
     * create() returns binder instance from registry, but creates one first if none exists
     *
     * @test
     */
    public function createWithoutInstanceInRegistry()
    {
        $this->assertNull(stubRegistry::get(stubBinder::REGISTRY_KEY));
        $binder = stubBinderRegistry::create();
        $this->assertSame($binder, stubRegistry::get(stubBinder::REGISTRY_KEY));
    }

    /**
     * create() returns binder instance from registry
     *
     * @test
     */
    public function createWithInstanceInRegistry()
    {
        $binder = new stubBinder();
        stubRegistry::set(stubBinder::REGISTRY_KEY, $binder);
        $this->assertSame($binder, stubBinderRegistry::create());
    }
}
?>