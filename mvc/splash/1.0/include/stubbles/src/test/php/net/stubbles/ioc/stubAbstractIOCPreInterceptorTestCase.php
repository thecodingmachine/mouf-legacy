<?php
/**
 * Test for net::stubbles::ioc::stubAbstractIOCPreInterceptor
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ioc_test
 */
stubClassLoader::load('net::stubbles::ioc::stubAbstractIOCPreInterceptor');
/**
 * Test for net::stubbles::ioc::stubAbstractIOCPreInterceptor
 *
 * @package     stubbles
 * @subpackage  ioc_test
 * @group       ioc
 */
class stubAbstractIOCPreInterceptorTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubAbstractIOCPreInterceptor
     */
    protected $preInterceptor;
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
     * set up test environment
     */
    public function setUp()
    {
        $this->preInterceptor = $this->getMock('stubAbstractIOCPreInterceptor',
                                               array('configure')
                                );
        $this->mockRequest    = $this->getMock('stubRequest');
        $this->mockSession    = $this->getMock('stubSession');
        $this->mockResponse   = $this->getMock('stubResponse');
        stubRegistry::remove(stubBinder::REGISTRY_KEY);
    }

    /**
     * clean up test environment
     */
    public function tearDown()
    {
        stubRegistry::remove(stubBinder::REGISTRY_KEY);
    }

    /**
     * if no binder is in registry one is created
     *
     * @test
     */
    public function noBinderInRegistry()
    {
        $this->preInterceptor->expects($this->once())->method('configure');
        $this->preInterceptor->preProcess($this->mockRequest, $this->mockSession, $this->mockResponse);
        $binder = stubRegistry::get(stubBinder::REGISTRY_KEY);
        $this->assertType('stubBinder', $binder);
    }

    /**
     * a binder already in the registry should be used and not replaced
     *
     * @test
     */
    public function binderAlreadyInRegistry()
    {
        $binder = new stubBinder();
        stubRegistry::set(stubBinder::REGISTRY_KEY, $binder);
        $this->preInterceptor->expects($this->once())->method('configure')->with($this->equalTo($binder));
        $this->preInterceptor->preProcess($this->mockRequest, $this->mockSession, $this->mockResponse);
        $this->assertSame($binder, stubRegistry::get(stubBinder::REGISTRY_KEY));
    }
}
?>