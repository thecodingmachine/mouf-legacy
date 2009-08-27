<?php
/**
 * Tests for net::stubbles::ipo::interceptors::stubETagPostInterceptor.
 *
 * @author      Richard Sternagel <richard.sternagel@1und1.de>
 * @package     stubbles
 * @subpackage  ipo_interceptors_test
 */
stubClassLoader::load('net::stubbles::ipo::interceptors::stubETagPostInterceptor');
/**
 * Tests for net::stubbles::ipo::interceptors::stubETagPostInterceptor.
 *
 * @package     stubbles
 * @subpackage  ipo_interceptors_test
 * @group       ipo
 * @group       ipo_interceptors
 */
class stubETagPostInterceptorTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubETagPostInterceptor
     */
    protected $eTagPostInterceptor;
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
     * ETag (e.g. '"h2j3f12jhf33fd89sdf900du3f12"');
     *
     * @var string
     */
    protected $ETag;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->eTagPostInterceptor   = new stubETagPostInterceptor();
        $this->mockRequest           = $this->getMock('stubRequest');
        $this->mockSession           = $this->getMock('stubSession');
        $this->mockResponse          = $this->getMock('stubResponse');
        $this->ETag                  = '"'.md5(serialize('my page content')).'"';

        $this->mockResponse->expects($this->once())
                           ->method('getData')
                           ->will($this->returnValue('my page content'));
    }

    /**
     * postProcess without "If-None-Match" header in request
     *
     * @test
     */
    public function postProcessWithoutIfNoneMatchHeader()
    {
        $this->mockResponse->expects($this->at(1))
                           ->method('addHeader')
                           ->with($this->equalTo('ETag'), $this->equalTo($this->ETag));
        $this->mockResponse->expects($this->at(2))
                           ->method('addHeader')
                           ->with($this->equalTo('Cache-Control'), $this->equalTo('private'));
        $this->mockResponse->expects($this->at(3))
                           ->method('addHeader')
                           ->with($this->equalTo('Pragma'), $this->equalTo(''));

        $this->eTagPostInterceptor->postProcess($this->mockRequest, $this->mockSession, $this->mockResponse);
    }

    /**
     * postProcess with "If-None-Match" header in request
     *
     * @test
     */
    public function postProcessWithIfNoneMatchHeader()
    {
       $this->mockRequest->expects($this->once())
                         ->method('validateValue')
                         ->will($this->returnValue(true));

       $this->mockResponse->expects($this->at(1))
                          ->method('addHeader')
                          ->with($this->equalTo('ETag'), $this->equalTo($this->ETag));
       $this->mockResponse->expects($this->at(2))
                          ->method('addHeader')
                          ->with($this->equalTo('Cache-Control'), $this->equalTo('private'));
       $this->mockResponse->expects($this->at(3))
                          ->method('addHeader')
                          ->with($this->equalTo('Pragma'), $this->equalTo(''));
       $this->mockResponse->expects($this->at(4))
                          ->method('setStatusCode')
                          ->with($this->equalTo('304'), $this->equalTo('Not Modified'));

       $this->eTagPostInterceptor->postProcess($this->mockRequest, $this->mockSession, $this->mockResponse);
    }
}
?>