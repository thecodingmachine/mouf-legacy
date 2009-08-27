<?php
/**
 * Test for net::stubbles::ioc::stubInjector with the singleton scope.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ioc_test
 */
stubClassLoader::load('net::stubbles::ioc::stubBinder');
/**
 * Interface to be used in the test.
 *
 * @package     stubbles
 * @subpackage  ioc_test
 */
interface stubInjectorSessionTestCase_Number
{
    public function display();
}
/**
 * Class to be used in the test.
 *
 * @package     stubbles
 * @subpackage  ioc_test
 */
class stubInjectorSessionTestCase_Answer implements stubInjectorSessionTestCase_Number
{
    public function display()
    {
        echo 42;
    }
}
/**
 * Test for net::stubbles::ioc::stubInjector with the session scope.
 *
 * @package     stubbles
 * @subpackage  ioc_test
 * @group       ioc
 */
class stubInjectorSessionTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * binder instance to be used in tests
     *
     * @var  stubBinder
     */
    protected $binder;
    /**
     * mocked session instance
     *
     * @var  
     */
    protected $nonDurableSession;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->binder            = new stubBinder();
        $this->nonDurableSession = new stubNoneDurableSession($this->getMock('stubRequest'), $this->getMock('stubResponse'), 'sessionName');
        $this->binder->bind('stubSession')->toInstance($this->nonDurableSession);
        stubRegistry::set(stubBinder::REGISTRY_KEY, $this->binder);
    }

    /**
     * clean up test environment
     */
    public function tearDown()
    {
        stubRegistry::remove(stubBinder::REGISTRY_KEY);
    }

    /**
     * Test using the SessionScope
     *
     * @test
     */
    public function withScope()
    {
        $this->binder->bind('stubInjectorSessionTestCase_Number')->to('stubInjectorSessionTestCase_Answer')->in(stubBindingScopes::$SESSION);
        $injector = $this->binder->getInjector();

        $this->assertTrue($injector->hasBinding('stubInjectorSessionTestCase_Number'));

        $number = $injector->getInstance('stubInjectorSessionTestCase_Number');
        $this->assertType('stubInjectorSessionTestCase_Number', $number);
        $this->assertType('stubInjectorSessionTestCase_Answer', $number);
        $this->assertTrue($this->nonDurableSession->hasValue(stubBindingScopeSession::SESSION_KEY . 'stubInjectorSessionTestCase_Answer'));
        $this->assertSame($number, $this->nonDurableSession->getValue(stubBindingScopeSession::SESSION_KEY . 'stubInjectorSessionTestCase_Answer'));
        $this->assertSame($number, $injector->getInstance('stubInjectorSessionTestCase_Number'));
    }


    /**
     * Test using the SessionScope
     *
     * @test
     */
    public function withScopeFromSession()
    {
        stubBindingScopes::$SESSION->clearInstances();
        $this->binder->bind('stubInjectorSessionTestCase_Number')->to('stubInjectorSessionTestCase_Answer')->in(stubBindingScopes::$SESSION);
        $injector = $this->binder->getInjector();
        $number   = new stubInjectorSessionTestCase_Answer();
        $this->nonDurableSession->putValue(stubBindingScopeSession::SESSION_KEY . 'stubInjectorSessionTestCase_Answer', $number);
        stubBindingScopes::$SESSION->setSession($this->nonDurableSession);
        $this->assertTrue($injector->hasBinding('stubInjectorSessionTestCase_Number'));
        $this->assertSame($number, $injector->getInstance('stubInjectorSessionTestCase_Number'));
    }

    /**
     * if session is not available it is not possible to use the session scope
     *
     * @test
     * @expectedException  stubRuntimeException
     */
    public function noSessionAvailable()
    {
        stubRegistry::remove(stubBinder::REGISTRY_KEY);
        $scope = new stubBindingScopeSession();
        $scope->getProvider($this->getMock('stubBaseReflectionClass'), $this->getMock('stubBaseReflectionClass'), $this->getMock('stubInjectionProvider'));
    }
}
?>