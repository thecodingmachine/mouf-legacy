<?php
/**
 * Tests for net::stubbles::websites::xml::stubShowLastXMLInterceptor.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_xml_test
 */
stubClassLoader::load('net::stubbles::websites::xml::stubShowLastXMLInterceptor');
/**
 * Tests for net::stubbles::websites::xml::stubShowLastXMLInterceptor.
 *
 * @package     stubbles
 * @subpackage  websites_xml_test
 * @group       websites
 * @group       websites_xml
 */
class stubShowLastXMLInterceptorTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubShowLastXMLInterceptor
     */
    protected $showLastXMLPreInterceptor;
    /**
     * mocked response instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockResponse;
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
     * set up test environment
     */
    public function setUp()
    {
        $this->showLastXMLPreInterceptor = new stubShowLastXMLInterceptor();
        $this->mockRequest               = $this->getMock('stubRequest');
        $this->mockResponse              = $this->getMock('stubResponse');
        $this->mockSession               = $this->getMock('stubSession');
    }

    /**
     * assure that a new session does not trigger the interceptor
     *
     * @test
     */
    public function newSessionDoesNotTriggerInterceptor()
    {
        $this->mockRequest->expects($this->once())->method('hasValue')->will($this->returnValue(true));
        $this->mockSession->expects($this->once())->method('isNew')->will($this->returnValue(true));
        $this->mockRequest->expects($this->never())->method('cancel');
        $this->mockResponse->expects($this->never())->method('write');
        $this->showLastXMLPreInterceptor->preProcess($this->mockRequest, $this->mockSession, $this->mockResponse);
    }

    /**
     * assure that a request that does not force to show the last request xml does not trigger the interceptor
     *
     * @test
     */
    public function requestDoesNotHaveValueThatRequestsLastXML()
    {
        $this->mockRequest->expects($this->once())->method('hasValue')->will($this->returnValue(false));
        $this->mockSession->expects($this->never())->method('isNew');
        $this->mockRequest->expects($this->never())->method('cancel');
        $this->mockResponse->expects($this->never())->method('write');
        $this->showLastXMLPreInterceptor->preProcess($this->mockRequest, $this->mockSession, $this->mockResponse);
    }

    /**
     * assure that if all requirements are met the processor is executed
     *
     * @test
     */
    public function allOk()
    {
        $this->mockRequest->expects($this->once())->method('hasValue')->will($this->returnValue(true));
        $this->mockSession->expects($this->once())->method('isNew')->will($this->returnValue(false));
        $this->mockSession->expects($this->once())->method('getValue')->will($this->returnValue('foo'));
        $this->mockRequest->expects($this->once())->method('cancel');
        $this->mockResponse->expects($this->once())->method('write')->with($this->equalTo('foo'));
        $this->showLastXMLPreInterceptor->preProcess($this->mockRequest, $this->mockSession, $this->mockResponse);
    }
}
?>