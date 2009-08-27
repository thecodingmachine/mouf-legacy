<?php
/**
 * Tests for net::stubbles::lang::errorhandler::stubDisplayExceptionHandler.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  lang_errorhandler_test
 */
stubClassLoader::load('net::stubbles::lang::errorhandler::stubDisplayExceptionHandler');
/**
 * Helper class for the test.
 *
 * @package     stubbles
 * @subpackage  lang_errorhandler_test
 */
class TeststubDisplayExceptionHandler extends stubDisplayExceptionHandler
{
    /**
     * direct access to protected method
     *
     * @param  stubResponse  $response   response to be send
     * @param  Exception     $exception  the uncatched exception
     */
    public function callFillResponse(stubResponse $response, Exception $exception)
    {
        $this->fillResponse($response, $exception);
    }
}
/**
 * Tests for net::stubbles::lang::errorhandler::stubDisplayExceptionHandler.
 *
 * @package     stubbles
 * @subpackage  lang_errorhandler_test
 * @group       lang
 * @group       lang_errorhandler
 */
class stubDisplayExceptionHandlerTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  TeststubDisplayExceptionHandler
     */
    protected $displayExceptionHandler;
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
        $this->displayExceptionHandler = new TeststubDisplayExceptionHandler();
        $this->mockResponse            = $this->getMock('stubResponse');
    }

    /**
     * assure that build-in exceptions are handled
     *
     * @test
     */
    public function defaultException()
    {
        $exception = new Exception('message');
        $this->mockResponse->expects($this->at(0))->method('write')->with($this->equalTo('message'));
        $this->mockResponse->expects($this->at(1))->method('write');
        $this->displayExceptionHandler->callFillResponse($this->mockResponse, $exception);
    }

    /**
     * assure that stubbles exceptions are handled
     *
     * @test
     */
    public function stubException()
    {
        $exception = new stubException('message');
        $this->mockResponse->expects($this->at(0))->method('write')->with($this->equalTo("net::stubbles::lang::exceptions::stubException {\n    message(string): message\n    file(string): " . __FILE__ . "\n    line(integer): 81\n    code(integer): 0\n}\n"));
        $this->mockResponse->expects($this->at(1))->method('write');
        $this->displayExceptionHandler->callFillResponse($this->mockResponse, $exception);
    }
}
?>