<?php
/**
 * Tests for net::stubbles::websites::processors::stubAbstractProcessor.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_processors_test
 */
stubClassLoader::load('net::stubbles::websites::processors::stubAbstractProcessor');
/**
 * Helper class for the test.
 *
 * @package     stubbles
 * @subpackage  websites_processors_test
 */
class TeststubAbstractProcessor extends stubAbstractProcessor
{
    /**
     * processes the request
     * 
     * @return  stubProcessor
     */
    public function process() { }
}
/**
 * Tests for net::stubbles::websites::processors::stubAbstractProcessor.
 *
 * @package     stubbles
 * @subpackage  websites_processors_test
 * @group       websites
 * @group       websites_processors
 */
class stubAbstractProcessorTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to be used for tests
     *
     * @var  TeststubAbstractProcessor
     */
    protected $abstractProcessor;
    /**
     * mocked request to use
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockRequest;
    /**
     * mocked session to use
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
        $this->mockRequest       = $this->getMock('stubRequest');
        $this->mockSession       = $this->getMock('stubSession');
        $this->mockResponse      = $this->getMock('stubResponse');
        $this->abstractProcessor = new TeststubAbstractProcessor($this->mockRequest,
                                                                 $this->mockSession,
                                                                 $this->mockResponse
                                   );
    }

    /**
     * same instances should be returned
     *
     * @test
     */
    public function sameInstances()
    {
        $this->assertSame($this->mockRequest, $this->abstractProcessor->getRequest());
        $this->assertSame($this->mockSession, $this->abstractProcessor->getSession());
        $this->assertSame($this->mockResponse, $this->abstractProcessor->getResponse());
    }

    /**
     * interceptor descriptor should be set and returned correct
     *
     * @test
     */
    public function interceptorDescriptor()
    {
        $this->assertEquals('interceptors', $this->abstractProcessor->getInterceptorDescriptor());
        $this->abstractProcessor->setInterceptorDescriptor('equals');
        $this->assertEquals('equals', $this->abstractProcessor->getInterceptorDescriptor());
    }

    /**
     * a processor never forces ssl by default
     *
     * @test
     */
    public function neverForcesSSLByDefault()
    {
        $this->assertFalse($this->abstractProcessor->forceSSL());
    }

    /**
     * ssl evaluates to true if validation returns true, should only be evaluated once
     *
     * @test
     */
    public function isSSLShouldBeTrueAndOnlyEvaluatedOnce()
    {
        $this->mockRequest->expects($this->once())
                          ->method('validateValue')
                          ->will($this->returnValue(true));
        $this->assertTrue($this->abstractProcessor->isSSL());
        $this->assertTrue($this->abstractProcessor->isSSL());
    }

    /**
     * ssl evaluates to false if validation returns true, should only be evaluated once
     *
     * @test
     */
    public function isSSLShouldBeFalseAndOnlyEvaluatedOnce()
    {
        $this->mockRequest->expects($this->once())
                          ->method('validateValue')
                          ->will($this->returnValue(false));
        $this->assertFalse($this->abstractProcessor->isSSL());
        $this->assertFalse($this->abstractProcessor->isSSL());
    }
}
?>