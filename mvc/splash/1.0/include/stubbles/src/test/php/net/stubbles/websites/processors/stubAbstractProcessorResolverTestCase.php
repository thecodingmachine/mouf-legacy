<?php
/**
 * Tests for net::stubbles::websites::processors::stubAbstractProcessorResolver.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_processors_test
 */
stubClassLoader::load('net::stubbles::websites::processors::stubAbstractProcessorResolver');
/**
 * Tests for net::stubbles::websites::processors::stubAbstractProcessorResolver.
 *
 * @package     stubbles
 * @subpackage  websites_processors_test
 * @group       websites
 * @group       websites_processors
 */
class stubAbstractProcessorResolverTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to be used for tests
     *
     * @var  stubAbstractProcessorResolver
     */
    protected $abstractProcessorResolver;
    /**
     * mocked request to use
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockRequest;
    /**
     * mocked session to use
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
        $this->abstractProcessorResolver = $this->getMock('stubAbstractProcessorResolver',
                                                          array('doResolve',
                                                                'configure',
                                                                'getPageFactoryClass'
                                                          )
                                           );
        $this->mockRequest  = $this->getMock('stubRequest');
        $this->mockSession  = $this->getMock('stubSession');
        $this->mockResponse = $this->getMock('stubResponse');
    }

    /**
     * test that a missing return value of doResolve() triggers an exception
     *
     * @test
     * @expectedException  stubConfigurationException
     */
    public function noProcessorThrowsException()
    {
        $this->abstractProcessorResolver->expects($this->once())
                                        ->method('doResolve')
                                        ->with($this->equalTo($this->mockRequest), $this->equalTo($this->mockSession), $this->equalTo($this->mockResponse))
                                        ->will($this->returnValue(null));
        $this->abstractProcessorResolver->resolve($this->mockRequest, $this->mockSession, $this->mockResponse);
    }

    /**
     * assure that a wrong class as processor triggers an exception
     *
     * @test
     * @expectedException  stubRuntimeException
     */
    public function falseProcessorThrowsException()
    {
        $this->abstractProcessorResolver->expects($this->once())
                                        ->method('doResolve')
                                        ->with($this->equalTo($this->mockRequest), $this->equalTo($this->mockSession), $this->equalTo($this->mockResponse))
                                        ->will($this->returnValue('org::stubbles::test::BazProcessor'));
        $this->abstractProcessorResolver->resolve($this->mockRequest, $this->mockSession, $this->mockResponse);
    }

    /**
     * assure that the default processor is returned and it has all required classes
     *
     * @test
     */
    public function correctNonPageBasedProcessor()
    {
        $this->abstractProcessorResolver->expects($this->once())
                                        ->method('doResolve')
                                        ->with($this->equalTo($this->mockRequest), $this->equalTo($this->mockSession), $this->equalTo($this->mockResponse))
                                        ->will($this->returnValue('org::stubbles::test::FooProcessor'));
        $processor = $this->abstractProcessorResolver->resolve($this->mockRequest, $this->mockSession, $this->mockResponse);
        $this->assertType('FooProcessor', $processor);
        $this->assertSame($this->mockRequest, $processor->getRequest());
        $this->assertSame($this->mockSession, $processor->getSession());
        $this->assertSame($this->mockResponse, $processor->getResponse());
    }

    /**
     * if a page factory class is required and none is returned an exception will be thrown
     *
     * @test
     * @expectedException  stubConfigurationException
     */
    public function pageBasedProcessorNoPageFactoryClass()
    {
        $this->abstractProcessorResolver->expects($this->once())
                                        ->method('doResolve')
                                        ->with($this->equalTo($this->mockRequest), $this->equalTo($this->mockSession), $this->equalTo($this->mockResponse))
                                        ->will($this->returnValue('org::stubbles::test::FooPageBasedProcessor'));
        $this->abstractProcessorResolver->expects($this->once())
                                        ->method('getPageFactoryClass')
                                        ->will($this->returnValue(null));
        $processor = $this->abstractProcessorResolver->resolve($this->mockRequest, $this->mockSession, $this->mockResponse);
        $this->abstractProcessorResolver->selectPage($processor);
    }

    /**
     * if a page factory class is required and a wrong class is returned an exception will be thrown
     *
     * @test
     * @expectedException  stubRuntimeException
     */
    public function pageBasedProcessorNoPageFactoryInstance()
    {
        $this->abstractProcessorResolver->expects($this->once())
                                        ->method('doResolve')
                                        ->with($this->equalTo($this->mockRequest), $this->equalTo($this->mockSession), $this->equalTo($this->mockResponse))
                                        ->will($this->returnValue('org::stubbles::test::FooPageBasedProcessor'));
        $this->abstractProcessorResolver->expects($this->once())
                                        ->method('getPageFactoryClass')
                                        ->will($this->returnValue('stdClass'));
        $processor = $this->abstractProcessorResolver->resolve($this->mockRequest, $this->mockSession, $this->mockResponse);
        $this->abstractProcessorResolver->selectPage($processor);
    }

    /**
     * assure that the default processor is returned and it has all required classes
     *
     * @test
     */
    public function correctPageBasedProcessor()
    {
        $pageFactoryClass = get_class($this->getMock('stubPageFactory'));
        $this->abstractProcessorResolver->expects($this->once())
                                        ->method('doResolve')
                                        ->with($this->equalTo($this->mockRequest), $this->equalTo($this->mockSession), $this->equalTo($this->mockResponse))
                                        ->will($this->returnValue('org::stubbles::test::FooPageBasedProcessor'));
        $this->abstractProcessorResolver->expects($this->once())
                                        ->method('getPageFactoryClass')
                                        ->will($this->returnValue($pageFactoryClass));
        $processor = $this->abstractProcessorResolver->resolve($this->mockRequest, $this->mockSession, $this->mockResponse);
        $this->abstractProcessorResolver->selectPage($processor);
        $this->assertType('FooPageBasedProcessor', $processor);
        $this->assertSame($this->mockRequest, $processor->getRequest());
        $this->assertSame($this->mockSession, $processor->getSession());
        $this->assertSame($this->mockResponse, $processor->getResponse());
        $this->assertType($pageFactoryClass, $processor->getPageFactory());
    }
}
?>