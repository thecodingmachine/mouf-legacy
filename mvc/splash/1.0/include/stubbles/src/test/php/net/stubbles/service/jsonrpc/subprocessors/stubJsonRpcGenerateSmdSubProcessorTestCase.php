<?php
/**
 * Test for net::stubbles::service::jsonrpc::subprocessors::stubJsonRpcGenerateSmdSubProcessor.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  service_jsonrpc_subprocessors_test
 */
stubClassLoader::load('net::stubbles::service::jsonrpc::subprocessors::stubJsonRpcGenerateSmdSubProcessor');
/**
 * Tests for net::stubbles::service::jsonrpc::subprocessors::stubJsonRpcGenerateSmdSubProcessor.
 *
 * @package     stubbles
 * @subpackage  service_jsonrpc_test
 * @group       service_jsonrpc
 */
class stubJsonRpcGenerateSmdSubProcessorTestCase extends PHPUnit_Framework_TestCase
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
     * mocked session to use
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockSession;
    /**
     * instance to test
     *
     * @var  stubJsonRpcGenerateSmdSubProcessor
     */
    protected $jsonRpcGenerateSmdSubProcessor;
    /**
     * class map to be used in tests
     *
     * @var  array<string,string>
     */
    protected $classMap               = array('Test' => 'TestService',
                                              'Nope' => 'DoesNotExist'
                                        );

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->mockRequest                    = $this->getMock('stubRequest');
        $this->mockSession                    = $this->getMock('stubSession');
        $this->mockResponse                   = $this->getMock('stubResponse');
        $this->jsonRpcGenerateSmdSubProcessor = $this->getMock('stubJsonRpcGenerateSmdSubProcessor',
                                                               array('getServiceURL',
                                                                     'handleException',
                                                                     'getSmdGenerator'
                                                               )
                                                );
    }

    /**
     * retrieve service url
     *
     * @test
     */
    public function successful()
    {
        $this->mockRequest->expects($this->once())->method('getValidatedValue')->will($this->returnValue('stubbles.json.proxy.Test'));
        $this->jsonRpcGenerateSmdSubProcessor->expects($this->once())
                                             ->method('getServiceURL')
                                             ->with($this->equalTo($this->mockRequest))
                                             ->will($this->returnValue('serviceUrl'));
        $this->mockSmdGenerator = $this->getMock('stubSmdGenerator', array(), array('serviceUrl'));
        $this->mockSmdGenerator->expects($this->once())
                               ->method('generateSmd')
                               ->with($this->equalTo('TestService'), $this->equalTo('Test'))
                               ->will($this->returnValue('smdDescription'));
        $this->jsonRpcGenerateSmdSubProcessor->expects($this->once())
                                             ->method('getSmdGenerator')
                                             ->with($this->equalTo('serviceUrl&__class=stubbles.json.proxy.Test'))
                                             ->will($this->returnValue($this->mockSmdGenerator));
        $this->mockResponse->expects($this->once())->method('write')->with($this->equalTo('smdDescription'));
        $this->jsonRpcGenerateSmdSubProcessor->expects($this->never())
                                             ->method('handleException');
        $this->jsonRpcGenerateSmdSubProcessor->process($this->mockRequest, $this->mockSession, $this->mockResponse, $this->classMap, array());
    }

    /**
     * retrieve service url
     *
     * @test
     */
    public function successfulWithConfiguredNamespace()
    {
        $this->mockRequest->expects($this->once())->method('getValidatedValue')->will($this->returnValue('foo.bar.Test'));
        $this->jsonRpcGenerateSmdSubProcessor->expects($this->once())
                                             ->method('getServiceURL')
                                             ->with($this->equalTo($this->mockRequest))
                                             ->will($this->returnValue('serviceUrl'));
        $this->mockSmdGenerator = $this->getMock('stubSmdGenerator', array(), array('serviceUrl'));
        $this->mockSmdGenerator->expects($this->once())
                               ->method('generateSmd')
                               ->with($this->equalTo('TestService'), $this->equalTo('Test'))
                               ->will($this->returnValue('smdDescription'));
        $this->jsonRpcGenerateSmdSubProcessor->expects($this->once())
                                             ->method('getSmdGenerator')
                                             ->with($this->equalTo('serviceUrl&__class=foo.bar.Test'))
                                             ->will($this->returnValue($this->mockSmdGenerator));
        $this->mockResponse->expects($this->once())->method('write')->with($this->equalTo('smdDescription'));
        $this->jsonRpcGenerateSmdSubProcessor->expects($this->never())
                                             ->method('handleException');
        $this->jsonRpcGenerateSmdSubProcessor->process($this->mockRequest, $this->mockSession, $this->mockResponse, $this->classMap, array('namespace' => 'foo.bar'));
    }

    /**
     * retrieve service url
     *
     * @test
     */
    public function notSuccesful()
    {
        $this->mockRequest->expects($this->once())->method('getValidatedValue')->will($this->returnValue('stubbles.json.proxy.Test'));
        $this->jsonRpcGenerateSmdSubProcessor->expects($this->once())
                                             ->method('getServiceURL')
                                             ->with($this->equalTo($this->mockRequest))
                                             ->will($this->returnValue('serviceUrl'));
        $this->mockSmdGenerator = $this->getMock('stubSmdGenerator', array(), array('serviceUrl'));
        $exception = new Exception('exceptionMessage');
        $this->mockSmdGenerator->expects($this->once())
                               ->method('generateSmd')
                               ->with($this->equalTo('TestService'), $this->equalTo('Test'))
                               ->will($this->throwException($exception));
        $this->jsonRpcGenerateSmdSubProcessor->expects($this->once())
                                             ->method('getSmdGenerator')
                                             ->with($this->equalTo('serviceUrl&__class=stubbles.json.proxy.Test'))
                                             ->will($this->returnValue($this->mockSmdGenerator));
        $this->mockResponse->expects($this->never())->method('write');
        $this->jsonRpcGenerateSmdSubProcessor->expects($this->once())
                                             ->method('handleException')
                                             ->with($this->equalTo($exception, $this->mockResponse, 'Generation of SMD for TestService failed.'));
        $this->jsonRpcGenerateSmdSubProcessor->process($this->mockRequest, $this->mockSession, $this->mockResponse, $this->classMap, array());
    }
}
?>