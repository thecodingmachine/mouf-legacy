<?php
/**
 * Tests for net::stubbles::websites::memphis::stubMemphisAbstractExtension.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_memphis_test
 */
stubClassLoader::load('net::stubbles::websites::memphis::stubMemphisAbstractExtension',
                      'net::stubbles::ioc::annotations::stubInjectAnnotation',
                      'net::stubbles::ioc::annotations::stubNamedAnnotation'
);
/**
 * Class to get a non-abstract class to test.
 *
 * @package     stubbles
 * @subpackage  websites_memphis_test
 */
class TeststubMemphisAbstractExtension extends stubMemphisAbstractExtension
{
    /**
     * processes the page element
     *
     * @return  mixed
     */
    public function process()
    {
        // intentionally empty
    }

    /**
     * returns the request
     *
     * @return  stubRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * returns the session
     *
     * @return  stubSession
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * returns the response
     *
     * @return  stubResponse
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * returns the context
     *
     * @return  array<string,mixed>
     */
    public function getContext()
    {
        return $this->context;
    }
}
/**
 * Tests for net::stubbles::websites::memphis::stubMemphisAbstractExtension.
 *
 * @package     stubbles
 * @subpackage  websites_memphis_test
 * @group       websites
 * @group       websites_memphis
 */
class stubMemphisAbstractExtensionTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to be used for tests
     *
     * @var  TeststubMemphisAbstractExtension
     */
    protected $memphisAbstractExtension;
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
        $this->mockRequest              = $this->getMock('stubRequest');
        $this->mockSession              = $this->getMock('stubSession');
        $this->mockResponse             = $this->getMock('stubResponse');
        $this->memphisAbstractExtension = new TeststubMemphisAbstractExtension($this->mockRequest, $this->mockSession, $this->mockResponse);
    }

    /**
     * base instances should be set
     *
     * @test
     */
    public function baseInstancesSet()
    {
        $this->assertSame($this->mockRequest, $this->memphisAbstractExtension->getRequest());
        $this->assertSame($this->mockSession, $this->memphisAbstractExtension->getSession());
        $this->assertSame($this->mockResponse, $this->memphisAbstractExtension->getResponse());
        $this->assertTrue($this->memphisAbstractExtension->getClass()->getConstructor()->hasAnnotation('Inject'));
    }

    /**
     * context should be handled correct
     *
     * @test
     */
    public function contextHandling()
    {
        $context = array('foo' => 313);
        $this->memphisAbstractExtension->setContext($context);
        $this->assertEquals($context, $this->memphisAbstractExtension->getContext());
        $method = $this->memphisAbstractExtension->getClass()->getMethod('setContext');
        $this->assertTrue($method->hasAnnotation('Inject'));
        $this->assertTrue($method->hasAnnotation('Named'));
    }

    /**
     * basic caching handling
     *
     * @test
     */
    public function caching()
    {
        $this->assertTrue($this->memphisAbstractExtension->isCachable());
        $this->assertEquals(array(), $this->memphisAbstractExtension->getCacheVars());
        $this->assertEquals(array(), $this->memphisAbstractExtension->getUsedFiles());
    }
}
?>