<?php
/**
 * Test for net::stubbles::util::log::stubLogDataFactory.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_log_test
 * @version     $Id: stubLogDataFactoryTestCase.php 1931 2008-11-13 22:25:24Z mikey $
 */
stubClassLoader::load('net::stubbles::util::log::stubLogDataFactory');
/**
 * Test for net::stubbles::util::log::stubLogDataFactory.
 *
 * @package     stubbles
 * @subpackage  util_log_test
 * @group       util_log
 */
class stubLogDataFactoryTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * set up test environment
     */
    public function setUp()
    {
        $binder = new stubBinder();
        stubRegistry::set(stubBinder::REGISTRY_KEY, $binder);
    }

    /**
     * clean up test environment
     */
    public function tearDown()
    {
        stubRegistry::remove(stubBinder::REGISTRY_KEY);
    }

    /**
     * test creating a stubLogData without a binder
     *
     * @test
     */
    public function withoutBinder()
    {
        stubRegistry::remove(stubBinder::REGISTRY_KEY);
        $class = get_class($this->getMock('stubLogData'));
        stubRegistry::setConfig(stubLogData::CLASS_REGISTRY_KEY, $class);
        $logData = stubLogDataFactory::create('foo');
        $this->assertType($class, $logData);
    }

    /**
     * test creating a stubLogData object from a class that is already loaded
     *
     * @test
     */
    public function withoutLoading()
    {
        $class = get_class($this->getMock('stubLogData'));
        stubRegistry::setConfig(stubLogData::CLASS_REGISTRY_KEY, $class);
        $logData = stubLogDataFactory::create('foo');
        $this->assertType($class, $logData);
    }

    /**
     * test creating a stubLogData object from a class that has to be loaded
     *
     * @test
     */
    public function withLoading()
    {
        stubRegistry::setConfig(stubLogData::CLASS_REGISTRY_KEY, 'org::stubbles::test::TestLogData');
        $logData = stubLogDataFactory::create('foo');
        $this->assertType('TestLogData', $logData);
    }

    /**
     * test creating a object which is no instance of stubLogData fails
     *
     * @test
     * @expectedException  stubRuntimeException
     */
    public function wrongInstance()
    {
        stubRegistry::setConfig(stubLogData::CLASS_REGISTRY_KEY, 'stdClass');
        stubLogDataFactory::create('foo');
    }
}
?>