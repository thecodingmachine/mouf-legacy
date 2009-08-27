<?php
/**
 * Test for net::stubbles::service::jsonrpc::subprocessors::stubJsonRpcGenerateProxiesSubProcessor.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  service_jsonrpc_subprocessors_test
 */
stubClassLoader::load('net::stubbles::service::jsonrpc::subprocessors::stubJsonRpcGenerateProxiesSubProcessor');
/**
 * Tests for net::stubbles::service::jsonrpc::subprocessors::stubJsonRpcGenerateProxiesSubProcessor.
 *
 * @package     stubbles
 * @subpackage  service_jsonrpc_test
 * @group       service_jsonrpc
 */
class stubJsonRpcGenerateProxiesSubProcessorTestCase extends PHPUnit_Framework_TestCase
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
     * @var  stubJsonRpcGenerateProxiesSubProcessor
     */
    protected $jsonRpcGenerateProxiesSubProcessor;
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
        $this->mockRequest                        = $this->getMock('stubRequest');
        $this->mockSession                        = $this->getMock('stubSession');
        $this->mockResponse                       = $this->getMock('stubResponse');
        $this->jsonRpcGenerateProxiesSubProcessor = $this->getMock('stubJsonRpcGenerateProxiesSubProcessor',
                                                                   array('getServiceURL',
                                                                         'handleException',
                                                                         'getProxyGenerator'
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
        $this->mockRequest->expects($this->once())->method('getValidatedValue')->will($this->returnValue('__all'));
        $this->jsonRpcGenerateProxiesSubProcessor->expects($this->never())->method('getServiceURL');
        $this->mockProxyGenerator = $this->getMock('stubJsonRpcProxyGenerator');
        $this->mockProxyGenerator->expects($this->at(0))
                                 ->method('generateJavascriptProxy')
                                 ->with($this->equalTo('TestService'), $this->equalTo('Test'))
                                 ->will($this->returnValue('javascript proxy1'));
        $this->mockProxyGenerator->expects($this->at(1))
                                 ->method('generateJavascriptProxy')
                                 ->with($this->equalTo('DoesNotExist'), $this->equalTo('Nope'))
                                 ->will($this->returnValue('javascript proxy2'));
        $this->jsonRpcGenerateProxiesSubProcessor->expects($this->once())
                                                 ->method('getProxyGenerator')
                                                 ->will($this->returnValue($this->mockProxyGenerator));
        $this->mockResponse->expects($this->at(0))->method('write')->with($this->equalTo("stubbles.json.proxy = {};\n\n"));
        $this->mockResponse->expects($this->at(1))->method('write')->with($this->equalTo('javascript proxy1'));
        $this->mockResponse->expects($this->at(2))->method('write')->with($this->equalTo('javascript proxy2'));
        $this->jsonRpcGenerateProxiesSubProcessor->expects($this->never())
                                                 ->method('handleException');
        $this->jsonRpcGenerateProxiesSubProcessor->process($this->mockRequest, $this->mockSession, $this->mockResponse, $this->classMap, array());
    }

    /**
     * retrieve service url
     *
     * @test
     */
    public function successfulWithConfiguredNamespace()
    {
        $this->mockRequest->expects($this->once())->method('getValidatedValue')->will($this->returnValue('__all'));
        $this->jsonRpcGenerateProxiesSubProcessor->expects($this->never())->method('getServiceURL');
        $this->mockProxyGenerator = $this->getMock('stubJsonRpcProxyGenerator');
        $this->mockProxyGenerator->expects($this->at(0))
                                 ->method('generateJavascriptProxy')
                                 ->with($this->equalTo('TestService'), $this->equalTo('Test'))
                                 ->will($this->returnValue('javascript proxy1'));
        $this->mockProxyGenerator->expects($this->at(1))
                                 ->method('generateJavascriptProxy')
                                 ->with($this->equalTo('DoesNotExist'), $this->equalTo('Nope'))
                                 ->will($this->returnValue('javascript proxy2'));
        $this->jsonRpcGenerateProxiesSubProcessor->expects($this->once())
                                                 ->method('getProxyGenerator')
                                                 ->will($this->returnValue($this->mockProxyGenerator));
        $this->mockResponse->expects($this->at(0))->method('write')->with($this->equalTo("foo.bar = {};\n\n"));
        $this->mockResponse->expects($this->at(1))->method('write')->with($this->equalTo('javascript proxy1'));
        $this->mockResponse->expects($this->at(2))->method('write')->with($this->equalTo('javascript proxy2'));
        $this->jsonRpcGenerateProxiesSubProcessor->expects($this->never())
                                                 ->method('handleException');
        $this->jsonRpcGenerateProxiesSubProcessor->process($this->mockRequest, $this->mockSession, $this->mockResponse, $this->classMap, array('namespace' => 'foo.bar'));
    }

    /**
     * retrieve service url
     *
     * @test
     */
    public function notSuccesful()
    {
        $this->mockRequest->expects($this->once())->method('getValidatedValue')->will($this->returnValue('Nope'));
        $this->jsonRpcGenerateProxiesSubProcessor->expects($this->never())->method('getServiceURL');
        $this->mockProxyGenerator = $this->getMock('stubJsonRpcProxyGenerator');
        $exception = new Exception('exceptionMessage');
        $this->mockProxyGenerator->expects($this->once())
                                 ->method('generateJavascriptProxy')
                                 ->will($this->throwException($exception));
        $this->jsonRpcGenerateProxiesSubProcessor->expects($this->once())
                                                 ->method('getProxyGenerator')
                                                 ->will($this->returnValue($this->mockProxyGenerator));
        $this->mockResponse->expects($this->once())->method('write')->with($this->equalTo("stubbles.json.proxy = {};\n\n"));
        $this->jsonRpcGenerateProxiesSubProcessor->expects($this->once())
                                                 ->method('handleException')
                                                 ->with($this->equalTo($exception, $this->mockResponse, 'Generation of proxy for TestService failed.'));
        $this->jsonRpcGenerateProxiesSubProcessor->process($this->mockRequest, $this->mockSession, $this->mockResponse, $this->classMap, array());
    }
}
?>