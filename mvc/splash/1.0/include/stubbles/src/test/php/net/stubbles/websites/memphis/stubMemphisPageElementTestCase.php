<?php
/**
 * Tests for net::stubbles::websites::memphis::stubMemphisPageElement.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_memphis_test
 */
stubClassLoader::load('net::stubbles::websites::memphis::stubMemphisPageElement');
/**
 * Class to get a non-abstract class to test.
 *
 * @package     stubbles
 * @subpackage  websites_memphis_test
 */
class TeststubMemphisPageElement extends stubMemphisPageElement
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
}
/**
 * Tests for net::stubbles::websites::memphis::stubMemphisPageElement.
 *
 * @package     stubbles
 * @subpackage  websites_memphis_test
 * @group       websites
 * @group       websites_memphis
 */
class stubMemphisPageElementTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to be used for tests
     *
     * @var  TeststubMemphisPageElement
     */
    protected $memphisPageElement;
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
        $this->mockRequest        = $this->getMock('stubRequest');
        $this->mockSession        = $this->getMock('stubSession');
        $this->mockResponse       = $this->getMock('stubResponse');
        $this->memphisPageElement = new TeststubMemphisPageElement();
    }

    /**
     * assure that a memphis page element is always available for the correct part
     *
     * @test
     */
    public function isAvailable()
    {
        $context = array('part' => 'dummy');
        $this->memphisPageElement->init($this->mockRequest, $this->mockSession, $this->mockResponse);
        $this->assertFalse($this->memphisPageElement->isAvailable());
        $this->memphisPageElement->init($this->mockRequest, $this->mockSession, $this->mockResponse, $context);
        $this->assertTrue($this->memphisPageElement->isAvailable());
        $this->memphisPageElement->setParts('foo,bar, baz');
        $this->memphisPageElement->init($this->mockRequest, $this->mockSession, $this->mockResponse, $context);
        $this->assertFalse($this->memphisPageElement->isAvailable());
        $context = array('part' => 'foo');
        $this->memphisPageElement->init($this->mockRequest, $this->mockSession, $this->mockResponse, $context);
        $this->assertTrue($this->memphisPageElement->isAvailable());
        $context = array('part' => 'bar');
        $this->memphisPageElement->init($this->mockRequest, $this->mockSession, $this->mockResponse, $context);
        $this->assertTrue($this->memphisPageElement->isAvailable());
        $context = array('part' => 'baz');
        $this->memphisPageElement->init($this->mockRequest, $this->mockSession, $this->mockResponse, $context);
        $this->assertTrue($this->memphisPageElement->isAvailable());
    }
}
?>