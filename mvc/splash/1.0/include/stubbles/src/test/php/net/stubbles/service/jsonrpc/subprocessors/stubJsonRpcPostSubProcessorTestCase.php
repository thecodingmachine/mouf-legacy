<?php
/**
 * Test for net::stubbles::service::jsonrpc::subprocessors::stubJsonRpcPostSubProcessor.
 *
 * @author      Richard Sternagel
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  service_jsonrpc_subprocessors_test
 */
stubClassLoader::load('net::stubbles::service::jsonrpc::subprocessors::stubJsonRpcPostSubProcessor');
require_once dirname(__FILE__) . '/TestService.php';
/**
 * Tests for net::stubbles::service::jsonrpc::subprocessors::stubJsonRpcPostSubProcessor.
 *
 * @package     stubbles
 * @subpackage  service_jsonrpc_test
 * @group       service_jsonrpc
 */
class stubJsonRpcPostSubProcessorTestCase extends PHPUnit_Framework_TestCase
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
     * @var  stubJsonRpcPostSubProcessor
     */
    protected $jsonRpcPostSubProcessor;
    /**
     * class map to be used in tests
     *
     * @var  array<string,string>
     */
    protected $classMap                = array('Test' => 'TestService',
                                               'Nope' => 'DoesNotExist'
                                         );

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->mockRequest             = $this->getMock('stubRequest');
        $this->mockSession             = $this->getMock('stubSession');
        $this->mockResponse            = $this->getMock('stubResponse');
        $this->jsonRpcPostSubProcessor = new stubJsonRpcPostSubProcessor();
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
     * test processing invalid post request
     *
     * @test
     */
    public function processPostRequestInvalidRequest()
    {
        $this->mockRequest->expects($this->once())->method('getValidatedRawData')->will($this->returnValue('invalid'));
        $this->mockResponse->expects($this->once())->method('write')->with($this->equalTo('{"id":null,"result":null,"error":"Invalid request."}'));
        $this->jsonRpcPostSubProcessor->process($this->mockRequest,
                                                $this->mockSession,
                                                $this->mockResponse,
                                                $this->classMap,
                                               array()
        );
    }

    /**
     * test processing invalid post request
     *
     * @test
     */
    public function processPostRequestRequestWithoutId()
    {
        $this->mockRequest->expects($this->once())->method('getValidatedRawData')->will($this->returnValue('{"method":"Dummy.add","params":["2","2"]}'));
        $this->mockResponse->expects($this->once())->method('write')->with($this->equalTo('{"id":null,"result":null,"error":"Invalid request: No id given."}'));
        $this->jsonRpcPostSubProcessor->process($this->mockRequest,
                                                $this->mockSession,
                                                $this->mockResponse,
                                                $this->classMap,
                                               array()
        );
    }

    /**
     * test processing invalid post request
     *
     * @test
     */
    public function processPostRequestRequestWithoutMethod()
    {
        $this->mockRequest->expects($this->once())->method('getValidatedRawData')->will($this->returnValue('{"params":["2","2"],"id":"1"}'));
        $this->mockResponse->expects($this->once())->method('write')->with($this->equalTo('{"id":"1","result":null,"error":"Invalid request: No method given."}'));
        $this->jsonRpcPostSubProcessor->process($this->mockRequest,
                                                $this->mockSession,
                                                $this->mockResponse,
                                                $this->classMap,
                                               array()
        );
    }

    /**
     * test processing invalid post request
     *
     * @test
     */
    public function processPostRequestRequestWithoutParams()
    {
        $this->mockRequest->expects($this->once())->method('getValidatedRawData')->will($this->returnValue('{"method":"Dummy.add","id":"1"}'));
        $this->mockResponse->expects($this->once())->method('write')->with($this->equalTo('{"id":"1","result":null,"error":"Invalid request: No params given."}'));
        $this->jsonRpcPostSubProcessor->process($this->mockRequest,
                                                $this->mockSession,
                                                $this->mockResponse,
                                                $this->classMap,
                                               array()
        );
    }

    /**
     * test processing invalid post request
     *
     * @test
     */
    public function processPostRequestRequestWithInvalidMethod()
    {
        $this->mockRequest->expects($this->once())->method('getValidatedRawData')->will($this->returnValue('{"method":"invalid","params":["2","2"],"id":"1"}'));
        $this->mockResponse->expects($this->once())->method('write')->with($this->equalTo('{"id":"1","result":null,"error":"Invalid request: method-Pattern has to be <className>.<methodName>."}'));
        $this->jsonRpcPostSubProcessor->process($this->mockRequest,
                                                $this->mockSession,
                                                $this->mockResponse,
                                                $this->classMap,
                                               array()
        );
    }

    /**
     * test processing invalid post request
     *
     * @test
     */
    public function processPostRequestRequestWithNonExistingClass()
    {
        $this->mockRequest->expects($this->once())->method('getValidatedRawData')->will($this->returnValue('{"method":"DoesNotExist.add","params":["2","2"],"id":"1"}'));
        $this->mockResponse->expects($this->once())->method('write')->with($this->equalTo('{"id":"1","result":null,"error":"Unknown class DoesNotExist."}'));
        $this->jsonRpcPostSubProcessor->process($this->mockRequest,
                                                $this->mockSession,
                                                $this->mockResponse,
                                                $this->classMap,
                                               array()
        );
    }

    /**
     * test processing invalid post request
     *
     * @test
     */
    public function processPostRequestRequestWithMissingClass()
    {
        $this->mockRequest->expects($this->once())->method('getValidatedRawData')->will($this->returnValue('{"method":"Nope.add","params":["2","2"],"id":"1"}'));
        $this->mockResponse->expects($this->once())->method('write')->with($this->equalTo('{"id":"1","result":null,"error":"Class DoesNotExist does not exist"}'));
        $this->jsonRpcPostSubProcessor->process($this->mockRequest,
                                                $this->mockSession,
                                                $this->mockResponse,
                                                $this->classMap,
                                               array()
        );
    }

    /**
     * test processing invalid post request
     *
     * @test
     */
    public function processPostRequestRequestWithUnknownMethod()
    {
        $this->mockRequest->expects($this->once())->method('getValidatedRawData')->will($this->returnValue('{"method":"Test.sub","params":["2","2"],"id":"1"}'));
        $this->mockResponse->expects($this->once())->method('write')->with($this->equalTo('{"id":"1","result":null,"error":"Unknown method Test.sub."}'));
        $this->jsonRpcPostSubProcessor->process($this->mockRequest,
                                                $this->mockSession,
                                                $this->mockResponse,
                                                $this->classMap,
                                               array()
        );
    }

    /**
     * test processing invalid post request
     *
     * @test
     */
    public function processPostRequestRequestWithNoWebMethod()
    {
        $this->mockRequest->expects($this->once())->method('getValidatedRawData')->will($this->returnValue('{"method":"Test.mod","params":["2","2"],"id":"1"}'));
        $this->mockResponse->expects($this->once())->method('write')->with($this->equalTo('{"id":"1","result":null,"error":"Method Test.mod is not a WebMethod."}'));
        $this->jsonRpcPostSubProcessor->process($this->mockRequest,
                                                $this->mockSession,
                                                $this->mockResponse,
                                                $this->classMap,
                                               array()
        );
    }

    /**
     * test processing invalid post request: to less params triggers error, too much params are ignored
     *
     * @test
     */
    public function processPostRequestRequestWithInvalidAmountOfParams()
    {
        $this->mockRequest->expects($this->exactly(2))
                          ->method('getValidatedRawData')
                          ->will($this->onConsecutiveCalls('{"method":"Test.add","params":["2"],"id":"1"}',
                                                           '{"method":"Test.add","params":["2","2","2"],"id":"1"}'
                                 )
                            );
        $this->mockResponse->expects($this->at(0))->method('write')->with($this->equalTo('{"id":"1","result":null,"error":"Invalid amount of parameters passed."}'));
        $this->mockResponse->expects($this->at(1))->method('write')->with($this->equalTo('{"id":"1","result":4,"error":null}'));
        $this->jsonRpcPostSubProcessor->process($this->mockRequest,
                                                $this->mockSession,
                                                $this->mockResponse,
                                                $this->classMap,
                                               array()
        );
        $this->jsonRpcPostSubProcessor->process($this->mockRequest,
                                                $this->mockSession,
                                                $this->mockResponse,
                                                $this->classMap,
                                               array()
        );
    }

    /**
     * test processing correct post request
     *
     * @test
     */
    public function processPostRequestRequest()
    {
        $this->mockRequest->expects($this->once())->method('getValidatedRawData')->will($this->returnValue('{"method":"Test.add","params":["2","2"],"id":"1"}'));
        $this->mockResponse->expects($this->once())->method('write')->with($this->equalTo('{"id":"1","result":4,"error":null}'));
        $this->jsonRpcPostSubProcessor->process($this->mockRequest,
                                                $this->mockSession,
                                                $this->mockResponse,
                                                $this->classMap,
                                               array()
        );
    }

    /**
     * test processing correct post request with separate classname
     *
     * @test
     */
    public function processPostRequestRequestWithSeparateClassName()
    {
        $this->mockRequest->expects($this->once())->method('getValidatedRawData')->will($this->returnValue('{"method":"add","params":["2","2"],"id":"1"}'));
        $this->mockRequest->expects($this->once())->method('hasValue')->will($this->returnValue(true));
        $this->mockRequest->expects($this->once())->method('getValidatedValue')->will($this->returnValue('Test'));
        $this->mockResponse->expects($this->once())->method('write')->with($this->equalTo('{"id":"1","result":4,"error":null}'));
        $this->jsonRpcPostSubProcessor->process($this->mockRequest,
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
        $this->mockRequest->expects($this->once())->method('getValidatedRawData')->will($this->returnValue('{"method":"Test.add","params":["2","2"],"id":"1"}'));
        $this->mockResponse->expects($this->once())->method('write')->with($this->equalTo('{"id":"1","result":null,"error":"No instance of net::stubbles::ioc::stubBinder in registry."}'));
        $this->jsonRpcPostSubProcessor->process($this->mockRequest,
                                                $this->mockSession,
                                                $this->mockResponse,
                                                $this->classMap,
                                               array()
        );
    }
}
?>