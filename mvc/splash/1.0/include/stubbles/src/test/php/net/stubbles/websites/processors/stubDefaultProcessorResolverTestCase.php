<?php
/**
 * Tests for net::stubbles::websites::processors::stubDefaultProcessorResolver.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_processors_test
 */
stubClassLoader::load('net::stubbles::websites::processors::stubDefaultProcessorResolver');
/**
 * Helper class to access the doResolve() method directly and circumvent the
 * net::stubbles::websites::processors::stubAbstractProcessorResolver.
 *
 * @package     stubbles
 * @subpackage  websites_processors_test
 */
class TeststubDefaultProcessorResolver extends stubDefaultProcessorResolver
{
    /**
     * direct access to doResolve()
     *
     * @param   stubRequest   $request
     * @param   stubSession   $session
     * @param   stubResponse  $response
     * @return  string
     */
    public function getDoResolveReturnValue(stubRequest $request, stubSession $session, stubResponse $response)
    {
        return $this->doResolve($request, $session, $response);
    }

    /**
     * direct access to configure()
     *
     * @param  stubProcessor  $processor
     */
    public function callConfigure(stubProcessor $processor)
    {
        $this->configure($processor);
    }

    /**
     * direct access to getPageFactoryClass()
     *
     * @param   stubProcessor  $processor
     * @return  string
     */
    public function callGetPageFactoryClass(stubProcessor $processor)
    {
        return $this->getPageFactoryClass($processor);
    }
}
/**
 * Tests for net::stubbles::websites::processors::stubDefaultProcessorResolver.
 *
 * @package     stubbles
 * @subpackage  websites_processors_test
 * @group       websites
 * @group       websites_processors
 */
class stubDefaultProcessorResolverTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to be used for tests
     *
     * @var  TeststubDefaultProcessorResolver
     */
    protected $defaultProcessorResolver;
    /**
     * mocked request to use
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockRequest;
    /**
     * mocked response instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockResponse;
    /**
     * mocked session to use
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockSession;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->defaultProcessorResolver = new TeststubDefaultProcessorResolver();
        $this->mockRequest              = $this->getMock('stubRequest');
        $this->mockSession              = $this->getMock('stubSession');
        $this->mockResponse             = $this->getMock('stubResponse');
    }

    /**
     * helper method to add the processors to the resolver
     */
    protected function addProcessors()
    {
        $this->defaultProcessorResolver->addProcessor('foo', 'org::stubbles::test::FooProcessor', 'interceptors-foo', 'TestPageFactoryClassName1');
        $this->defaultProcessorResolver->addProcessor('bar', 'org::stubbles::test::BarProcessor', null, 'TestPageFactoryClassName2');
        $this->defaultProcessorResolver->addProcessor('baz', 'org::stubbles::test::BazProcessor');
        $this->defaultProcessorResolver->setDefaultProcessor('foo');
    }

    /**
     * assure that the default processor is returned and it has all required classes
     *
     * @test
     */
    public function defaultProcessor()
    {
        $this->addProcessors();
        $this->mockRequest->expects($this->once())->method('hasValue')->will($this->returnValue(false));
        $this->mockSession->expects($this->once())
                          ->method('putValue')
                          ->with($this->equalTo('net.stubbles.websites.lastProcessor'), $this->equalTo('foo'));
        $processor = $this->defaultProcessorResolver->getDoResolveReturnValue($this->mockRequest, $this->mockSession, $this->mockResponse);
        $this->assertEquals('org::stubbles::test::FooProcessor', $processor);
    }

    /**
     * assure that the selected processor is returned and it has all required classes
     *
     * @test
     */
    public function selectedProcessor()
    {
        $this->addProcessors();
        $this->mockRequest->expects($this->once())->method('hasValue')->will($this->returnValue(true));
        $this->mockRequest->expects($this->once())->method('getValidatedValue')->will($this->returnValue('bar'));
        $this->mockSession->expects($this->once())
                          ->method('putValue')
                          ->with($this->equalTo('net.stubbles.websites.lastProcessor'), $this->equalTo('bar'));
        $processor = $this->defaultProcessorResolver->getDoResolveReturnValue($this->mockRequest, $this->mockSession, $this->mockResponse);
        $this->assertEquals('org::stubbles::test::BarProcessor', $processor);
    }

    /**
     * assure that the default processor is returned if selected does not exist
     *
     * @test
     */
    public function defaultFallbackProcessor()
    {
        $this->addProcessors();
        $this->mockRequest->expects($this->once())->method('hasValue')->will($this->returnValue(true));
        $this->mockRequest->expects($this->once())->method('getValidatedValue')->will($this->returnValue(null));
        $this->mockSession->expects($this->once())
                          ->method('putValue')
                          ->with($this->equalTo('net.stubbles.websites.lastProcessor'), $this->equalTo('foo'));
        $processor = $this->defaultProcessorResolver->getDoResolveReturnValue($this->mockRequest, $this->mockSession, $this->mockResponse);
        $this->assertEquals($processor, 'org::stubbles::test::FooProcessor');
    }

    /**
     * assure that no added processors triggers an exception
     *
     * @test
     * @expectedException  stubConfigurationException
     */
    public function noProcessors()
    {
        $this->mockSession->expects($this->never())->method('putValue');
        $this->defaultProcessorResolver->getDoResolveReturnValue($this->mockRequest, $this->mockSession, $this->mockResponse);
    }

    /**
     * assure that a wrong default processor triggers an exception
     *
     * @test
     * @expectedException  stubConfigurationException
     */
    public function wrongDefaultProcessors()
    {
        $this->defaultProcessorResolver->addProcessor('foo', 'org::stubbles::test::FooProcessor', 'interceptors-foo');
        $this->defaultProcessorResolver->setDefaultProcessor('bar');
        $this->mockSession->expects($this->never())->method('putValue');
        $this->defaultProcessorResolver->getDoResolveReturnValue($this->mockRequest, $this->mockSession, $this->mockResponse);
    }

    /**
     * processor should be condigured correctly
     *
     * @test
     */
    public function processorIsConfiguredCorrectly()
    {
        $this->addProcessors();
        $mockProcessor = $this->getMock('stubProcessor');
        $mockProcessor->expects($this->once())
                      ->method('getClassName')
                      ->will($this->returnValue('org::stubbles::test::FooProcessor'));
        $mockProcessor->expects($this->once())->method('setInterceptorDescriptor')->with($this->equalTo('interceptors-foo'));
        $this->defaultProcessorResolver->callConfigure($mockProcessor);
        $mockProcessor = $this->getMock('stubProcessor');
        $mockProcessor->expects($this->once())
                      ->method('getClassName')
                      ->will($this->returnValue('org::stubbles::test::BarProcessor'));
        $mockProcessor->expects($this->once())->method('setInterceptorDescriptor')->with($this->equalTo('interceptors'));
        $this->defaultProcessorResolver->callConfigure($mockProcessor);
        $mockProcessor = $this->getMock('stubProcessor');
        $mockProcessor->expects($this->once())
                      ->method('getClassName')
                      ->will($this->returnValue('org::stubbles::test::BazProcessor'));
        $mockProcessor->expects($this->once())->method('setInterceptorDescriptor')->with($this->equalTo('interceptors'));
        $this->defaultProcessorResolver->callConfigure($mockProcessor);
    }

    /**
     * page factory class should be handled correct
     *
     * @test
     */
    public function pageFactoryClass()
    {
        $this->addProcessors();
        $mockProcessor = $this->getMock('stubProcessor');
        $mockProcessor->expects($this->exactly(3))
                      ->method('getClassName')
                      ->will($this->onConsecutiveCalls('org::stubbles::test::FooProcessor',
                                                       'org::stubbles::test::BarProcessor',
                                                       'org::stubbles::test::BazProcessor'
                             )
                        );
        $this->assertEquals('TestPageFactoryClassName1', $this->defaultProcessorResolver->callGetPageFactoryClass($mockProcessor));
        $this->assertEquals('TestPageFactoryClassName2', $this->defaultProcessorResolver->callGetPageFactoryClass($mockProcessor));
        $this->assertNull($this->defaultProcessorResolver->callGetPageFactoryClass($mockProcessor));
    }
}
?>