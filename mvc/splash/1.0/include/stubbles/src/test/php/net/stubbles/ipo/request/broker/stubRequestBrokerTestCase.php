<?php
/**
 * Tests for net::stubbles::ipo::request::broker::stubRequestBroker.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_broker_test
 */
stubClassLoader::load('net::stubbles::ipo::request::broker::stubRequestBroker');
require_once dirname(__FILE__) . '/TestBrokerClasses.php';
/**
 * Tests for net::stubbles::ipo::request::broker::stubRequestBroker.
 *
 * @package     stubbles
 * @subpackage  ipo_request_broker_test
 * @group       ipo
 * @group       ipo_request
 * @group       ipo_request_broker
 */
class stubRequestBrokerTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubRequestBroker
     */
    protected $requestBroker;
    /**
     * mocked request instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockRequest;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->requestBroker = new stubRequestBroker();
        $this->mockRequest   = $this->getMock('stubRequest');
    }

    /**
     * test that an illegal object throws an stubIllegalArgumentException
     *
     * @test
     * @expectedException  stubIllegalArgumentException
     */
    public function illegalObject()
    {
        $this->requestBroker->process($this->mockRequest, 'foo');
    }

    /**
     * test broking with a class that is not an instance of net::stubbles::lang::stubObject
     *
     * @test
     */
    public function withClassThatIsNotInstanceOfStubObject()
    {
        $this->mockRequest->expects($this->at(0))
                          ->method('getFilteredValue')
                          ->with($this->isInstanceOf('stubAbstractFilterDecorator'), $this->equalTo('foo'))
                          ->will($this->returnValue('foo'));
        $this->mockRequest->expects($this->at(2))
                          ->method('getFilteredValue')
                          ->with($this->isInstanceOf('stubAbstractFilterDecorator'), $this->equalTo('bar'))
                          ->will($this->returnValue('bar'));
        $this->mockRequest->expects($this->exactly(2))
                          ->method('hasValueError')
                          ->will($this->returnValue(false));
        $testClass = new TestBrokerClass();
        $this->requestBroker->process($this->mockRequest, $testClass);
        $this->assertEquals('foo', $testClass->foo);
        $this->assertEquals('bar', $testClass->getBar());
        $this->assertNull($testClass->getBaz());
        $this->assertNull(TestBrokerClass::$dummy);
    }

    /**
     * test broking with a class that is an instance of net::stubbles::lang::stubObject
     *
     * @test
     */
    public function withClassThatIsInstanceOfStubObject()
    {
        $this->mockRequest->expects($this->at(0))
                          ->method('getFilteredValue')
                          ->with($this->isInstanceOf('stubAbstractFilterDecorator'), $this->equalTo('prefix_foo'))
                          ->will($this->returnValue('foo'));
        $this->mockRequest->expects($this->at(2))
                          ->method('getFilteredValue')
                          ->with($this->isInstanceOf('stubAbstractFilterDecorator'), $this->equalTo('prefix_bar'))
                          ->will($this->returnValue('bar'));
        $this->mockRequest->expects($this->exactly(2))
                          ->method('hasValueError')
                          ->will($this->returnValue(false));
        $testClass = new TestBrokerObject();
        $this->requestBroker->process($this->mockRequest, $testClass, 'prefix_');
        $this->assertEquals('foo', $testClass->foo);
        $this->assertEquals('bar', $testClass->getBar());
        $this->assertNull($testClass->getBaz());
        $this->assertNull(TestBrokerObject::$dummy);
    }

    /**
     * test broking with a class that is not an instance of net::stubbles::lang::stubObject
     *
     * @test
     */
    public function withClassThatIsNotInstanceOfStubObjectAndFilterOverruling()
    {
        $overrules = array('foo' => $this->getMock('stubFilter'),
                           'bar' => $this->getMock('stubFilter')
                     );
        $this->mockRequest->expects($this->at(0))
                          ->method('getFilteredValue')
                          ->with($this->isInstanceOf(get_class($overrules['foo'])), $this->equalTo('foo'))
                          ->will($this->returnValue('foo'));
        $this->mockRequest->expects($this->at(2))
                          ->method('getFilteredValue')
                          ->with($this->isInstanceOf(get_class($overrules['bar'])), $this->equalTo('bar'))
                          ->will($this->returnValue('bar'));
        $this->mockRequest->expects($this->exactly(2))
                          ->method('hasValueError')
                          ->will($this->returnValue(false));
        $testClass = new TestBrokerClass();
        $this->requestBroker->process($this->mockRequest, $testClass, '', $overrules);
        $this->assertEquals('foo', $testClass->foo);
        $this->assertEquals('bar', $testClass->getBar());
        $this->assertNull($testClass->getBaz());
        $this->assertNull(TestBrokerClass::$dummy);
    }

    /**
     * test broking with a class that is an instance of net::stubbles::lang::stubObject
     *
     * @test
     */
    public function withClassThatIsInstanceOfStubObjectAndFilterOverruling()
    {
        $overrules = array('prefix_foo' => $this->getMock('stubFilter'),
                           'prefix_bar' => $this->getMock('stubFilter')
                     );
        $this->mockRequest->expects($this->at(0))
                          ->method('getFilteredValue')
                          ->with($this->isInstanceOf(get_class($overrules['prefix_foo'])), $this->equalTo('prefix_foo'))
                          ->will($this->returnValue('foo'));
        $this->mockRequest->expects($this->at(2))
                          ->method('getFilteredValue')
                          ->with($this->isInstanceOf(get_class($overrules['prefix_bar'])), $this->equalTo('prefix_bar'))
                          ->will($this->returnValue('bar'));
        $this->mockRequest->expects($this->exactly(2))
                          ->method('hasValueError')
                          ->will($this->returnValue(false));
        $testClass = new TestBrokerObject();
        $this->requestBroker->process($this->mockRequest, $testClass, 'prefix_', $overrules);
        $this->assertEquals('foo', $testClass->foo);
        $this->assertEquals('bar', $testClass->getBar());
        $this->assertNull($testClass->getBaz());
        $this->assertNull(TestBrokerObject::$dummy);
    }
}
?>