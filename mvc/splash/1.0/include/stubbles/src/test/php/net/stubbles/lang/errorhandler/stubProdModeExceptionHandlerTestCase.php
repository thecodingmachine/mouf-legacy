<?php
/**
 * Tests for net::stubbles::lang::errorhandler::stubProdModeExceptionHandler.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  lang_errorhandler_test
 */
stubClassLoader::load('net::stubbles::lang::errorhandler::stubProdModeExceptionHandler');
/**
 * Helper class for the test.
 *
 * @package     stubbles
 * @subpackage  lang_errorhandler_test
 */
class TeststubProdModeExceptionHandler extends stubProdModeExceptionHandler
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
 * Tests for net::stubbles::lang::errorhandler::stubProdModeExceptionHandler.
 *
 * @package     stubbles
 * @subpackage  lang_errorhandler_test
 * @group       lang
 * @group       lang_errorhandler
 */
class stubProdModeExceptionHandlerTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  TeststubProdModeExceptionHandler
     */
    protected $prodModeExceptionHandler;
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
        $this->prodModeExceptionHandler = new TeststubProdModeExceptionHandler();
        $this->mockResponse             = $this->getMock('stubResponse');
    }

    /**
     * prod mode handler only displays a 500 http error
     *
     * @test
     */
    public function createsStatusCode500()
    {
        $this->mockResponse->expects($this->once())->method('setStatusCode')->with($this->equalTo(500), $this->equalTo('Internal Server Error'));
        $this->mockResponse->expects($this->once())->method('write');
        $this->prodModeExceptionHandler->callFillResponse($this->mockResponse, new Exception('message'));
    }
}
?>