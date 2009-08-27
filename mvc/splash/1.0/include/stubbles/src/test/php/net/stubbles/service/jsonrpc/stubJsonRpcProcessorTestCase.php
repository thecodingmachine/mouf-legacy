<?php
/**
 * Test for net::stubbles::service::jsonrpc::stubJsonRpcProcessor.
 *
 * @author      Richard Sternagel
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  service_jsonrpc_test
 */
stubClassLoader::load('net::stubbles::service::jsonrpc::stubJsonRpcProcessor',
                      'net::stubbles::service::jsonrpc::subprocessors::stubJsonRpcSubProcessor'
);
@include_once 'vfsStream/vfsStream.php';
/**
 * Helper class for the test.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  service_jsonrpc_test
 */
class TeststubJsonRpcProcessor extends stubJsonRpcProcessor
{
    /**
     * sets the config file to be used
     *
     * @param  string  $configFile
     */
    public function setConfigFile($configFile)
    {
        $this->configFile = $configFile;
    }

    /**
     * access to protected config loading
     *
     * @return  array<string,string>
     */
    public function callLoadConfig()
    {
        return $this->loadConfig();
    }

    /**
     * access to protected subprocessor dispatcher
     *
     * @return  string
     */
    public function callGetSubProcessorClassName()
    {
        return $this->getSubProcessorClassName();
    }
}
/**
 * Tests for net::stubbles::service::jsonrpc::stubJsonRpcProcessor.
 *
 * @package     stubbles
 * @subpackage  service_jsonrpc_test
 * @group       service_jsonrpc
 */
class stubJsonRpcProcessorTestCase extends PHPUnit_Framework_TestCase
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
     * set up test environment
     */
    public function setUp()
    {
        $this->mockRequest     = $this->getMock('stubRequest');
        $this->mockSession     = $this->getMock('stubSession');
        $this->mockResponse    = $this->getMock('stubResponse');
    }

    /**
     * test processing the request
     *
     * @test
     */
    public function process()
    {
        $subProcessorClass = get_class($this->getMock('stubJsonRpcSubProcessor'));
        $jsonRpcProcessor  = $this->getMock('stubJsonRpcProcessor',
                                            array('loadConfig',
                                                  'getSubProcessorClassName'
                                            ),
                                            array($this->mockRequest,
                                                  $this->mockSession,
                                                  $this->mockResponse
                                            )
                             );
        $jsonRpcProcessor->expects($this->once())->method('getSubProcessorClassName')->will($this->returnValue($subProcessorClass));
        $jsonRpcProcessor->expects($this->once())
                         ->method('loadConfig')
                         ->will($this->returnValue(array('config'   => array('namespace' => 'foo'),
                                                         'classmap' => array()
                                                   )
                                )
                           );
        $jsonRpcProcessor->process();
    }

    /**
     * failing to load the class map throws an exception
     *
     * @test
     * @expectedException  stubFileNotFoundException
     */
    public function loadClassMapFails()
    {
        if (class_exists('vfsStream', false) === false) {
            $this->markTestSkipped('Test for class map loading required vfsStream, see http://vfs.bovigo.org/.');
        }
        
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('config'));
        $jsonRpcProcessor = new TeststubJsonRpcProcessor($this->mockRequest,
                                                         $this->mockSession,
                                                         $this->mockResponse
                                );
        $jsonRpcProcessor->setConfigFile(vfsStream::url('config/doesNotExist.ini'));
        $jsonRpcProcessor->callLoadConfig();
    }

    /**
     * loading the config
     *
     * @test
     */
    public function loadConfig()
    {
        if (class_exists('vfsStream', false) === false) {
            $this->markTestSkipped('Test for class map loading required vfsStream, see http://vfs.bovigo.org/.');
        }
        
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('config'));
        vfsStream::newFile('json-rpc-service.ini.ini')->at(vfsStreamWrapper::getRoot())->withContent("[config]\nnamespace = \"stubbles.json.proxy\"\ngenjsdir = \"javascript/genjs\"\n[classmap]\nMathService = \"org::stubbles::examples::service::MathService\"\nNameService = \"org::stubbles::examples::service::RememberNameService\"");
        $jsonRpcProcessor = new TeststubJsonRpcProcessor($this->mockRequest,
                                                         $this->mockSession,
                                                         $this->mockResponse
                                );
        $jsonRpcProcessor->setConfigFile(vfsStream::url('config/json-rpc-service.ini.ini'));
        $this->assertEquals(array('config'   => array('namespace' => 'stubbles.json.proxy',
                                                      'genjsdir'  => 'javascript/genjs'
                                                ),
                                  'classmap' => array('MathService' => 'org::stubbles::examples::service::MathService',
                                                      'NameService' => 'org::stubbles::examples::service::RememberNameService'
                                                )
                            ),
                            $jsonRpcProcessor->callLoadConfig()
        );
    }

    /**
     * test processing post request
     *
     * @test
     */
    public function postRequest()
    {
        $this->mockRequest->expects($this->once())->method('getMethod')->will($this->returnValue('post'));
        $jsonRpcProcessor = new TeststubJsonRpcProcessor($this->mockRequest,
                                                         $this->mockSession,
                                                         $this->mockResponse
                                );
        $this->assertEquals('net::stubbles::service::jsonrpc::subprocessors::stubJsonRpcPostSubProcessor', $jsonRpcProcessor->callGetSubProcessorClassName());
    }

    /**
     * test processing generateProxy request
     *
     * @test
     */
    public function generateProxyRequest()
    {
        $this->mockRequest->expects($this->once())->method('getMethod')->will($this->returnValue('get'));
        $this->mockRequest->expects($this->once())->method('hasValue')->will($this->returnValue(true));
        $jsonRpcProcessor = new TeststubJsonRpcProcessor($this->mockRequest,
                                                         $this->mockSession,
                                                         $this->mockResponse
                                );
        $this->assertEquals('net::stubbles::service::jsonrpc::subprocessors::stubJsonRpcGenerateProxiesSubProcessor', $jsonRpcProcessor->callGetSubProcessorClassName());
    }

    /**
     * test processing smd request
     *
     * @test
     */
    public function smdRequest()
    {
        $this->mockRequest->expects($this->once())->method('getMethod')->will($this->returnValue('get'));
        $this->mockRequest->expects($this->exactly(2))->method('hasValue')->will($this->onConsecutiveCalls(false, true));
        $jsonRpcProcessor = new TeststubJsonRpcProcessor($this->mockRequest,
                                                         $this->mockSession,
                                                         $this->mockResponse
                                );
        $this->assertEquals('net::stubbles::service::jsonrpc::subprocessors::stubJsonRpcGenerateSmdSubProcessor', $jsonRpcProcessor->callGetSubProcessorClassName());
    }

    /**
     * test processing get request
     *
     * @test
     */
    public function getRequest()
    {
        $this->mockRequest->expects($this->once())->method('getMethod')->will($this->returnValue('get'));
        $this->mockRequest->expects($this->exactly(2))->method('hasValue')->will($this->returnValue(false));
        $jsonRpcProcessor = new TeststubJsonRpcProcessor($this->mockRequest,
                                                         $this->mockSession,
                                                         $this->mockResponse
                                );
        $this->assertEquals('net::stubbles::service::jsonrpc::subprocessors::stubJsonRpcGetSubProcessor', $jsonRpcProcessor->callGetSubProcessorClassName());
    }
}
?>