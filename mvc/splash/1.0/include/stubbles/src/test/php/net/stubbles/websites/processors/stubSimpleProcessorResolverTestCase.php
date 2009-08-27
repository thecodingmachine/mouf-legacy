<?php
/**
 * Tests for net::stubbles::websites::processors::stubSimpleProcessorResolver
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_processors_test
 */
stubClassLoader::load('net::stubbles::websites::processors::stubSimpleProcessorResolver');
/**
 * Helper class to access the doResolve() method directly and circumvent the
 * net::stubbles::websites::processors::stubAbstractProcessorResolver.
 *
 * @package     stubbles
 * @subpackage  websites_processors_test
 */
class TeststubSimpleProcessorResolver extends stubSimpleProcessorResolver
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
 * Tests for net::stubbles::websites::processors::stubSimpleProcessorResolver
 *
 * @package     stubbles
 * @subpackage  websites_processors_test
 * @group       websites
 * @group       websites_processors
 */
class stubSimpleProcessorResolverTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to be used for tests
     *
     * @var  TeststubSimpleProcessorResolver
     */
    protected $simpleProcessorResolver;
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
        $this->simpleProcessorResolver = new TeststubSimpleProcessorResolver();
        $this->mockRequest             = $this->getMock('stubRequest');
        $this->mockSession             = $this->getMock('stubSession');
        $this->mockResponse            = $this->getMock('stubResponse');
    }

    /**
     * assure that the default processor is returned and it has all required classes
     *
     * @test
     */
    public function withProcessor()
    {
        $this->simpleProcessorResolver->setProcessor('org::stubbles::test::FooProcessor');
        $processor = $this->simpleProcessorResolver->getDoResolveReturnValue($this->mockRequest, $this->mockSession, $this->mockResponse);
        $this->assertEquals('org::stubbles::test::FooProcessor', $processor);
    }

    /**
     * processor should be condigured correctly
     *
     * @test
     */
    public function processorIsConfiguredCorrectly()
    {
        $mockProcessor = $this->getMock('stubProcessor');
        $mockProcessor->expects($this->once())->method('setInterceptorDescriptor')->with($this->equalTo('interceptors'));
        $this->simpleProcessorResolver->callConfigure($mockProcessor);
    }

    /**
     * page factory class should be handled correct
     *
     * @test
     */
    public function pageFactoryClass()
    {
        $this->simpleProcessorResolver->setPageFactoryClass('TestPageFactoryClassName');
        $this->assertEquals('TestPageFactoryClassName', $this->simpleProcessorResolver->callGetPageFactoryClass($this->getMock('stubProcessor')));
    }
}
?>