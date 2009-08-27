<?php
/**
 * Test for net::stubbles::service::jsonrpc::subprocessors::stubJsonRpcAbstractGenerateSubProcessor.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  service_jsonrpc_subprocessors_test
 */
stubClassLoader::load('net::stubbles::service::jsonrpc::subprocessors::stubJsonRpcAbstractGenerateSubProcessor');
/**
 * Helper class for the test.
 *
 * @package     stubbles
 * @subpackage  service_jsonrpc_subprocessors_test
 */
class TeststubJsonRpcAbstractGenerateSubProcessor extends stubJsonRpcAbstractGenerateSubProcessor
{
    /**
     * does the processing of the subtask
     *
     * @param  stubRequest                         $request   the current request
     * @param  stubSession                         $session   the current session
     * @param  stubResponse                        $response  the current response
     * @param  array<string,array<string,string>>  $classMap  list of available webservice classes
     * @param  array<string,string>                $config    json-rpc config
     */
    public function process(stubRequest $request, stubSession $session, stubResponse $response, array $classMap, array $config)
    {
        // intentionally empty
    }

    /**
     * access to protected method
     *
     * @param   stubRequest  $request
     * @return  string
     */
    public function callGetServiceURL(stubRequest $request)
    {
        return $this->getServiceURL($request);
    }

    /**
     * access to protected method
     *
     * @param  Exception     $exception
     * @param  stubResponse  $response
     * @param  string        $introduction
     */
    public function callHandleException(Exception $exception, stubResponse $response, $introduction)
    {
        $this->handleException($exception, $response, $introduction);
    }
}
/**
 * Tests for net::stubbles::service::jsonrpc::subprocessors::stubJsonRpcAbstractGenerateSubProcessor.
 *
 * @package     stubbles
 * @subpackage  service_jsonrpc_test
 * @group       service_jsonrpc
 */
class stubJsonRpcAbstractGenerateSubProcessorTestCase extends PHPUnit_Framework_TestCase
{
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
     * instance to test
     *
     * @var  TeststubJsonRpcAbstractGenerateSubProcessor
     */
    protected $jsonRpcAbstractGenerateSubProcessor;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->mockRequest                         = $this->getMock('stubRequest');
        $this->mockResponse                        = $this->getMock('stubResponse');
        $this->jsonRpcAbstractGenerateSubProcessor = new TeststubJsonRpcAbstractGenerateSubProcessor();
    }

    /**
     * retrieve service url
     *
     * @test
     */
    public function serviceUrlWithoutProcessor()
    {
        $this->mockRequest->expects($this->once())->method('getURI')->will($this->returnValue('example.com/foo.php'));
        $this->mockRequest->expects($this->once())->method('hasValue')->will($this->returnValue(false));
        $this->assertEquals('//example.com/foo.php', $this->jsonRpcAbstractGenerateSubProcessor->callGetServiceURL($this->mockRequest));
    }

    /**
     * retrieve service url
     *
     * @test
     */
    public function serviceUrlWithProcessor()
    {
        $this->mockRequest->expects($this->once())->method('getURI')->will($this->returnValue('example.com/foo.php'));
        $this->mockRequest->expects($this->once())->method('hasValue')->will($this->returnValue(true));
        $this->mockRequest->expects($this->once())->method('getValidatedValue')->will($this->returnValue('jsonrpc'));
        $this->assertEquals('//example.com/foo.php?processor=jsonrpc', $this->jsonRpcAbstractGenerateSubProcessor->callGetServiceURL($this->mockRequest));
    }

    /**
     * test exception handling in prod mode
     *
     * @test
     */
    public function handleExceptionInProdMode()
    {
        stubMode::setCurrent(stubMode::$PROD);
        $exception = new Exception('exceptionMessage');
        $this->mockResponse->expects($this->never())->method('write');
        $this->jsonRpcAbstractGenerateSubProcessor->callHandleException($exception, $this->mockResponse, 'introduction');
    }

    /**
     * test exception handling in non-prod mode
     *
     * @test
     */
    public function handleExceptionInOtherMode()
    {
        stubMode::setCurrent(stubMode::$DEV);
        $exception = new Exception('exceptionMessage');
        $this->mockResponse->expects($this->exactly(2))->method('write');
        $this->jsonRpcAbstractGenerateSubProcessor->callHandleException($exception, $this->mockResponse, 'introduction');
    }
}
?>