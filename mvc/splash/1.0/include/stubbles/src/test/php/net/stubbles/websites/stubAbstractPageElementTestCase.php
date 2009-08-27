<?php
/**
 * Tests for net::stubbles::websites::stubAbstractPageElement.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_test
 * @version     $Id: stubAbstractPageElementTestCase.php 1909 2008-10-28 15:51:19Z mikey $
 */
stubClassLoader::load('net::stubbles::websites::stubAbstractPageElement');
/**
 * Helper class for the test.
 *
 * @package     stubbles
 * @subpackage  websites_test
 */
class TeststubAbstractPageElement extends stubAbstractPageElement
{
    /**
     * counter for calls of doInit()
     *
     * @var  int
     */
    protected $initCount = 0;

    /**
     * returns the request instance
     *
     * @return  stubRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * returns the session instance
     *
     * @return  stubSession
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * returns the response instance
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

    /**
     * method for additional initialisation
     */
    protected function doInit()
    {
        $this->initCount++;
    }

    /**
     * returns number of calls to doInit()
     *
     * @return  int
     */
    public function getInitCount()
    {
        return $this->initCount;
    }

    /**
     * required to be implemented
     */
    public function process()
    {
        // intentionally empty
    }
}
/**
 * Tests for net::stubbles::websites::stubAbstractPageElement.
 *
 * @package     stubbles
 * @subpackage  websites_test
 * @group       websites
 */
class stubAbstractPageElementTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to be used for tests
     *
     * @var  TeststubAbstractPageElement
     */
    protected $abstractPageElement;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->abstractPageElement = new TeststubAbstractPageElement();
    }

    /**
     * name can be set and get
     *
     * @test
     */
    public function name()
    {
        $this->assertNull($this->abstractPageElement->getName());
        $this->abstractPageElement->setName('foo');
        $this->assertEquals('foo', $this->abstractPageElement->getName());
    }

    /**
     * page elements by default do not require any additional class names
     *
     * @test
     */
    public function requiredClassNames()
    {
        $this->assertEquals(array(), $this->abstractPageElement->getRequiredClassNames());
    }

    /**
     * instances set via init() should be kept
     *
     * @test
     */
    public function initAndKeepInstances()
    {
        $mockRequest  = $this->getMock('stubRequest');
        $mockSession  = $this->getMock('stubSession');
        $mockResponse = $this->getMock('stubResponse');
        $context      = array('foo' => 313);
        $this->abstractPageElement->init($mockRequest, $mockSession, $mockResponse, $context);
        $this->assertSame($mockRequest, $this->abstractPageElement->getRequest());
        $this->assertSame($mockSession, $this->abstractPageElement->getSession());
        $this->assertSame($mockResponse, $this->abstractPageElement->getResponse());
        $this->assertEquals($context, $this->abstractPageElement->getContext());
    }

    /**
     * doInit() should be called only once regardless of calls to init()
     *
     * @test
     */
    public function doInitIsOnlyCalledOnce()
    {
        $mockRequest  = $this->getMock('stubRequest');
        $mockSession  = $this->getMock('stubSession');
        $mockResponse = $this->getMock('stubResponse');
        $this->assertEquals(0, $this->abstractPageElement->getInitCount());
        $this->abstractPageElement->init($mockRequest, $mockSession, $mockResponse, array());
        $this->assertEquals(1, $this->abstractPageElement->getInitCount());
        $this->abstractPageElement->init($mockRequest, $mockSession, $mockResponse, array());
        $this->assertEquals(1, $this->abstractPageElement->getInitCount());
    }

    /**
     * by default page elements are available, cachable and do not have any cache vars
     *
     * @test
     */
    public function cachingSupport()
    {
        $this->assertTrue($this->abstractPageElement->isAvailable());
        $this->assertTrue($this->abstractPageElement->isCachable());
        $this->assertEquals(array(), $this->abstractPageElement->getCacheVars());
        $this->assertEquals(array(), $this->abstractPageElement->getUsedFiles());
    }
}
?>