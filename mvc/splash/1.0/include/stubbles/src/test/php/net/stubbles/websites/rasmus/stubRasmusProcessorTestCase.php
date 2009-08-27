<?php
/**
 * Tests for net::stubbles::websites::rasmus::stubRasmusProcessor.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_rasmus_test
 */
stubClassLoader::load('net::stubbles::websites::rasmus::stubRasmusProcessor');
/**
 * Helper class for the test.
 *
 * @package     stubbles
 * @subpackage  websites_rasmus_test
 */
class TeststubRasmusProcessor extends stubRasmusProcessor
{
    /**
     * access to protected method
     *
     * @param   string  $pageName
     * @return  string
     */
    public function callRender($pageName)
    {
        return $this->render($pageName);
    }
}
/**
 * Tests for net::stubbles::websites::rasmus::stubRasmusProcessor.
 *
 * @package     stubbles
 * @subpackage  websites_rasmus_test
 * @group       websites
 * @group       websites_rasmus
 */
class stubRasmusProcessorTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to be used for tests
     *
     * @var  stubRasmusProcessor
     */
    protected $rasmusProcessor;
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
        $this->mockRequest     = $this->getMock('stubRequest');
        $this->mockSession     = $this->getMock('stubSession');
        $this->mockResponse    = $this->getMock('stubResponse');
        $this->rasmusProcessor = $this->getMock('stubRasmusProcessor',
                                                array('render'), 
                                                array($this->mockRequest, $this->mockSession, $this->mockResponse)
                                 );
        stubRegistry::setConfig(stubRasmusProcessor::PAGEPATH_REGISTRY_KEY, dirname(__FILE__));
    }

    /**
     * no page requested - process index page
     *
     * @test
     */
    public function processIndexPage()
    {
        $this->mockRequest->expects($this->once())
                          ->method('hasValue')
                          ->will($this->returnValue(false));
        $this->rasmusProcessor->expects($this->once())
                              ->method('render')
                              ->with($this->equalTo('index'))
                              ->will($this->returnValue('website content'));
        $this->mockResponse->expects($this->once())
                           ->method('write')
                           ->with($this->equalTo('website content'));
        $this->rasmusProcessor->process();
    }

    /**
     * invalid page requested - process index page
     *
     * @test
     */
    public function processInvalidPage()
    {
        $this->mockRequest->expects($this->once())
                          ->method('hasValue')
                          ->will($this->returnValue(true));
        $this->mockRequest->expects($this->once())
                          ->method('getValidatedValue')
                          ->will($this->returnValue(null));
        $this->rasmusProcessor->expects($this->once())
                              ->method('render')
                              ->with($this->equalTo('index'))
                              ->will($this->returnValue('website content'));
        $this->mockResponse->expects($this->once())
                           ->method('write')
                           ->with($this->equalTo('website content'));
        $this->rasmusProcessor->process();
    }

    /**
     * non existing page requested - process index page
     *
     * @test
     */
    public function processValidNonExistingPage()
    {
        $this->mockRequest->expects($this->once())
                          ->method('hasValue')
                          ->will($this->returnValue(true));
        $this->mockRequest->expects($this->once())
                          ->method('getValidatedValue')
                          ->will($this->returnValue('doesNotExist'));
        $this->rasmusProcessor->expects($this->once())
                              ->method('render')
                              ->with($this->equalTo('index'))
                              ->will($this->returnValue('website content'));
        $this->mockResponse->expects($this->once())
                          ->method('write')
                          ->with($this->equalTo('website content'));
        $this->rasmusProcessor->process();
    }

    /**
     * existing page requested - process requested page
     *
     * @test
     */
    public function processValidExistingPage()
    {
        $this->mockRequest->expects($this->once())
                          ->method('hasValue')
                          ->will($this->returnValue(true));
        $this->mockRequest->expects($this->once())
                          ->method('getValidatedValue')
                          ->will($this->returnValue('testpage'));
        $this->rasmusProcessor->expects($this->once())
                              ->method('render')
                              ->with($this->equalTo('testpage'))
                              ->will($this->returnValue('website content'));
        $this->mockResponse->expects($this->once())
                           ->method('write')
                           ->with($this->equalTo('website content'));
        $this->rasmusProcessor->process();
    }

    /**
     * test the render method
     *
     * @test
     */
    public function rendering()
    {
        
        $this->mockRequest->expects($this->once())
                          ->method('getMethod')
                          ->will($this->returnValue('test'));
        $this->mockSession->expects($this->once())
                          ->method('getValue')
                          ->will($this->returnValue('session'));
        $this->mockResponse->expects($this->once())
                           ->method('setStatusCode')
                           ->with($this->equalTo(304));
        $rasmusProcessor = new TeststubRasmusProcessor($this->mockRequest, $this->mockSession, $this->mockResponse);
        $this->assertEquals('<html><head><title>test</title></head><body>session</body></html>', $rasmusProcessor->callRender('testpage'));
    }
}
?>