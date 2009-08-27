<?php
/**
 * Test for net::stubbles::service::jsonrpc::subprocessors::stubJsonRpcGetSubProcessor.
 *
 * @author      Richard Sternagel
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  service_jsonrpc_subprocessors_test
 */
stubClassLoader::load('net::stubbles::service::jsonrpc::subprocessors::stubJsonRpcGetSubProcessor');
require_once dirname(__FILE__) . '/TestService.php';
/**
 * Tests for net::stubbles::service::jsonrpc::subprocessors::stubJsonRpcGetSubProcessor.
 *
 * @package     stubbles
 * @subpackage  service_jsonrpc_test
 * @group       service_jsonrpc
 */
class stubJsonRpcGetSubProcessorTestCase extends PHPUnit_Framework_TestCase
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
     * @var  stubJsonRpcGetSubProcessor
     */
    protected $jsonRpcGetSubProcessor;
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
        $this->mockRequest            = $this->getMock('stubRequest');
        $this->mockSession            = $this->getMock('stubSession');
        $this->mockResponse           = $this->getMock('stubResponse');
        $this->jsonRpcGetSubProcessor = new stubJsonRpcGetSubProcessor();
        stubRegistry::set(stubBinder::REGISTRY_KEY, new stubBinder());
    }

    /**
     * clear test environment
     */
    public function tearDown()
    {
        stubRegistry::remove(stubBinder::REGISTRY_KEY);
    }

    /**
     * test processing invalid get request
     *
     * @test
     */
    public function processGetRequestRequestWithoutId()
    {
        $this->mockRequest->expects($this->once())->method('getValidatedValue')->will($this->returnValue(null));
        $this->mockResponse->expects($this->once())->method('write')->with($this->equalTo('{"id":null,"result":null,"error":"Invalid request: No id given."}'));
        $this->jsonRpcGetSubProcessor->process($this->mockRequest,
                                               $this->mockSession,
                                               $this->mockResponse,
                                               $this->classMap,
                                               array()
        );
    }

    /**
     * test processing invalid get request
     *
     * @test
     */
    public function processGetRequestRequestWithoutMethod()
    {
        $this->mockRequest->expects($this->exactly(2))->method('getValidatedValue')->will($this->onConsecutiveCalls('1', null));
        $this->mockResponse->expects($this->once())->method('write')->with($this->equalTo('{"id":"1","result":null,"error":"Invalid request: No method given."}'));
        $this->jsonRpcGetSubProcessor->process($this->mockRequest,
                                               $this->mockSession,
                                               $this->mockResponse,
                                               $this->classMap,
                                               array()
        );
    }

    /**
     * test processing invalid get request
     *
     * @test
     */
    public function processGetRequestRequestWithInvalidMethod()
    {
        $this->mockRequest->expects($this->exactly(2))->method('getValidatedValue')->will($this->onConsecutiveCalls('1', 'invalid'));
        $this->mockResponse->expects($this->once())->method('write')->with($this->equalTo('{"id":"1","result":null,"error":"Invalid request: method-Pattern has to be <className>.<methodName>."}'));
        $this->jsonRpcGetSubProcessor->process($this->mockRequest,
                                               $this->mockSession,
                                               $this->mockResponse,
                                               $this->classMap,
                                               array()
        );
    }

    /**
     * test processing invalid get request
     *
     * @test
     */
    public function processGetRequestRequestWithNonExistingClass()
    {
        $this->mockRequest->expects($this->exactly(2))->method('getValidatedValue')->will($this->onConsecutiveCalls('1', 'DoesNotExist.add'));
        $this->mockResponse->expects($this->once())->method('write')->with($this->equalTo('{"id":"1","result":null,"error":"Unknown class DoesNotExist."}'));
        $this->jsonRpcGetSubProcessor->process($this->mockRequest,
                                               $this->mockSession,
                                               $this->mockResponse,
                                               $this->classMap,
                                               array()
        );
    }

    /**
     * test processing invalid get request
     *
     * @test
     */
    public function processGetRequestRequestWithMissingClass()
    {
        $this->mockRequest->expects($this->exactly(2))->method('getValidatedValue')->will($this->onConsecutiveCalls('1', 'Nope.add'));
        $this->mockResponse->expects($this->once())->method('write')->with($this->equalTo('{"id":"1","result":null,"error":"Class DoesNotExist does not exist"}'));
        $this->jsonRpcGetSubProcessor->process($this->mockRequest,
                                               $this->mockSession,
                                               $this->mockResponse,
                                               $this->classMap,
                                               array()
        );
    }

    /**
     * test processing invalid get request
     *
     * @test
     */
    public function processGetRequestRequestWithUnknownMethod()
    {
        $this->mockRequest->expects($this->exactly(2))->method('getValidatedValue')->will($this->onConsecutiveCalls('1', 'Test.sub'));
        $this->mockResponse->expects($this->once())->method('write')->with($this->equalTo('{"id":"1","result":null,"error":"Unknown method Test.sub."}'));
        $this->jsonRpcGetSubProcessor->process($this->mockRequest,
                                               $this->mockSession,
                                               $this->mockResponse,
                                               $this->classMap,
                                               array()
        );
    }

    /**
     * test processing invalid get request
     *
     * @test
     */
    public function processGetRequestRequestWithNoWebMethod()
    {
        $this->mockRequest->expects($this->exactly(2))->method('getValidatedValue')->will($this->onConsecutiveCalls('1', 'Test.mod'));
        $this->mockResponse->expects($this->once())->method('write')->with($this->equalTo('{"id":"1","result":null,"error":"Method Test.mod is not a WebMethod."}'));
        $this->jsonRpcGetSubProcessor->process($this->mockRequest,
                                               $this->mockSession,
                                               $this->mockResponse,
                                               $this->classMap,
                                               array()
        );
    }

    /**
     * test processing invalid get request: to less params triggers error
     *
     * @test
     */
    public function processGetRequestRequestWithTooLessParams()
    {
        $this->mockRequest->expects($this->exactly(4))->method('getValidatedValue')->will($this->onConsecutiveCalls('1', 'Test.add', 2, null));
        $this->mockResponse->expects($this->once())->method('write')->with($this->equalTo('{"id":"1","result":null,"error":"Param b is missing."}'));
        $this->jsonRpcGetSubProcessor->process($this->mockRequest,
                                               $this->mockSession,
                                               $this->mockResponse,
                                               $this->classMap,
                                               array()
        );
    }

    /**
     * test processing correct get request
     *
     * @test
     */
    public function processGetRequestRequest()
    {
        $this->mockRequest->expects($this->exactly(4))->method('getValidatedValue')->will($this->onConsecutiveCalls('1', 'Test.add', 2, 2));
        $this->mockResponse->expects($this->once())->method('write')->with($this->equalTo('{"id":"1","result":4,"error":null}'));
        $this->jsonRpcGetSubProcessor->process($this->mockRequest,
                                               $this->mockSession,
                                               $this->mockResponse,
                                               $this->classMap,
                                               array()
        );
    }

    /**
     * assure that missing binder triggers an exception
     *
     * @test
     */
    public function withoutBinderInRegistry()
    {
        stubRegistry::remove(stubBinder::REGISTRY_KEY);
        $this->mockRequest->expects($this->exactly(4))->method('getValidatedValue')->will($this->onConsecutiveCalls('1', 'Test.add', 2, 2));
        $this->mockResponse->expects($this->once())->method('write')->with($this->equalTo('{"id":"1","result":null,"error":"No instance of net::stubbles::ioc::stubBinder in registry."}'));
        $this->jsonRpcGetSubProcessor->process($this->mockRequest,
                                               $this->mockSession,
                                               $this->mockResponse,
                                               $this->classMap,
                                               array()
        );
    }
}
?>