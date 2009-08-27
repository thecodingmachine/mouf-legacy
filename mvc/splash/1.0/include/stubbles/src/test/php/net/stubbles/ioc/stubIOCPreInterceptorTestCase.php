<?php
/**
 * Test for net::stubbles::ioc::stubIOCPreInterceptor.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ioc_test
 */
stubClassLoader::load('net::stubbles::ioc::stubIOCPreInterceptor');
/**
 * extended session binding scope for the test
 *
 * @package     stubbles
 * @subpackage  ioc_test
 */
class TeststubBindingScopeSession extends stubBindingScopeSession
{
    /**
     * returns the session instance
     *
     * @return  stubSession
     */
    public function returnSession()
    {
        return $this->session;
    }
}
/**
 * Test for net::stubbles::ioc::stubIOCPreInterceptor.
 *
 * @package     stubbles
 * @subpackage  ioc_test
 * @group       ioc
 */
class stubIOCPreInterceptorTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubIOCPreInterceptor
     */
    protected $iocPreInterceptor;
    /**
     * mocked request instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockRequest;
    /**
     * mocked session instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockSession;
    /**
     * mocked response instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockResponse;
    /**
     * saved session scope
     *
     * @var  stubBindingScopeSession
     */
    protected $sessionScope;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->iocPreInterceptor = new stubIOCPreInterceptor();
        $this->mockRequest       = $this->getMock('stubRequest');
        $this->mockSession       = $this->getMock('stubSession');
        $this->mockResponse      = $this->getMock('stubResponse');
        stubRegistry::remove(stubBinder::REGISTRY_KEY);
        $this->sessionScope = stubBindingScopes::$SESSION;
        stubBindingScopes::$SESSION = new TeststubBindingScopeSession();
    }

    /**
     * clear test environment
     */
    public function tearDown()
    {
        stubRegistry::remove(stubBinder::REGISTRY_KEY);
        stubBindingScopes::$SESSION = $this->sessionScope;
    }

    /**
     * test the preProcess() method
     */
    public function testPreProcess()
    {
        $this->iocPreInterceptor->preProcess($this->mockRequest, $this->mockSession, $this->mockResponse);
        $binder = stubRegistry::get(stubBinder::REGISTRY_KEY);
        $this->assertType('stubBinder', $binder);
        $injector = $binder->getInjector();
        $request  = $injector->getInstance('stubRequest');
        $this->assertSame($this->mockRequest, $request);
        $session  = $injector->getInstance('stubSession');
        $this->assertSame($this->mockSession, $session);
        $response = $injector->getInstance('stubResponse');
        $this->assertSame($this->mockResponse, $response);
        $this->assertSame($injector, $injector->getInstance('stubInjector'));
        $this->assertSame($this->mockSession, stubBindingScopes::$SESSION->returnSession());
    }
}
?>