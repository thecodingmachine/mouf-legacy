<?php
/**
 * Tests for net::stubbles::ipo::request::stubRequestPrefixDecorator.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_test
 */
stubClassLoader::load('net::stubbles::ipo::request::stubRequestPrefixDecorator');
/**
 * Tests for net::stubbles::ipo::request::stubRequestPrefixDecorator.
 *
 * @package     stubbles
 * @subpackage  ipo_request_test
 * @group       ipo
 * @group       ipo_request
 */
class stubRequestPrefixDecoratorTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubRequestPrefixDecorator
     */
    protected $request;
    /**
     * a mock to use for the checks
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockRequest;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->mockRequest = $this->getMock('stubRequest');
        $this->request     = new stubRequestPrefixDecorator($this->mockRequest, 'test');
    }

    /**
     * test that acceptCookies() returns correct answer
     *
     * @test
     */
    public function acceptsCookies()
    {
        $this->mockRequest->expects($this->exactly(2))
                          ->method('acceptsCookies')
                          ->will($this->onConsecutiveCalls(true, false));
        $this->assertTrue($this->request->acceptsCookies());
        $this->assertFalse($this->request->acceptsCookies());
    }

    /**
     * test that values are prefixed as expected
     *
     * @test
     */
    public function value()
    {
        $this->mockRequest->expects($this->once())
                          ->method('hasValue')
                          ->with($this->equalTo('test_foo'), $this->equalTo(stubRequest::SOURCE_PARAM))
                          ->will($this->returnValue(true));
        $this->assertTrue($this->request->hasValue('foo'));
        
        $mockValidator = $this->getMock('stubValidator');
        $this->mockRequest->expects($this->once())
                          ->method('validateValue')
                          ->with($this->equalTo($mockValidator), $this->equalTo('test_foo'), $this->equalTo(stubRequest::SOURCE_PARAM))
                          ->will($this->returnValue('blub'));
        $this->assertEquals('blub', $this->request->validateValue($mockValidator, 'foo'));
        
        $this->mockRequest->expects($this->once())
                          ->method('getValidatedValue')
                          ->with($this->equalTo($mockValidator), $this->equalTo('test_foo'), $this->equalTo(stubRequest::SOURCE_PARAM))
                          ->will($this->returnValue('blub'));
        $this->assertEquals('blub', $this->request->getValidatedValue($mockValidator, 'foo'));
        
        $mockFilter = $this->getMock('stubFilter');
        $this->mockRequest->expects($this->once())
                          ->method('getFilteredValue')
                          ->with($this->equalTo($mockFilter), $this->equalTo('test_foo'), $this->equalTo(stubRequest::SOURCE_PARAM))
                          ->will($this->returnValue('blub'));
        $this->assertEquals('blub', $this->request->getFilteredValue($mockFilter, 'foo'));
    }

    /**
     * test that values are prefixed as expected
     *
     * @test
     */
    public function changedValue()
    {
        $this->request->setPrefix('bar');
        $this->mockRequest->expects($this->once())
                          ->method('hasValue')
                          ->with($this->equalTo('bar_foo'), $this->equalTo(stubRequest::SOURCE_PARAM))
                          ->will($this->returnValue(true));
        $this->assertTrue($this->request->hasValue('foo'));
        
        $mockValidator = $this->getMock('stubValidator');
        $this->mockRequest->expects($this->once())
                          ->method('validateValue')
                          ->with($this->equalTo($mockValidator), $this->equalTo('bar_foo'), $this->equalTo(stubRequest::SOURCE_PARAM))
                          ->will($this->returnValue('blub'));
        $this->assertEquals('blub', $this->request->validateValue($mockValidator, 'foo'));
        
        $this->mockRequest->expects($this->once())
                          ->method('getValidatedValue')
                          ->with($this->equalTo($mockValidator), $this->equalTo('bar_foo'), $this->equalTo(stubRequest::SOURCE_PARAM))
                          ->will($this->returnValue('blub'));
        $this->assertEquals('blub', $this->request->getValidatedValue($mockValidator, 'foo'));
        
        $mockFilter = $this->getMock('stubFilter');
        $this->mockRequest->expects($this->once())
                          ->method('getFilteredValue')
                          ->with($this->equalTo($mockFilter), $this->equalTo('bar_foo'), $this->equalTo(stubRequest::SOURCE_PARAM))
                          ->will($this->returnValue('blub'));
        $this->assertEquals('blub', $this->request->getFilteredValue($mockFilter, 'foo'));
    }

    /**
     * test that headers are handles as expected
     *
     * @test
     */
    public function header()
    {
        $this->mockRequest->expects($this->once())
                          ->method('hasValue')
                          ->with($this->equalTo('foo'), $this->equalTo(stubRequest::SOURCE_HEADER))
                          ->will($this->returnValue(true));
        $this->assertTrue($this->request->hasValue('foo', stubRequest::SOURCE_HEADER));
        
        $mockValidator = $this->getMock('stubValidator');
        $this->mockRequest->expects($this->once())
                          ->method('validateValue')
                          ->with($this->equalTo($mockValidator), $this->equalTo('foo'), $this->equalTo(stubRequest::SOURCE_HEADER))
                          ->will($this->returnValue('blub'));
        $this->assertEquals('blub', $this->request->validateValue($mockValidator, 'foo', stubRequest::SOURCE_HEADER));
        
        $this->mockRequest->expects($this->once())
                          ->method('getValidatedValue')
                          ->with($this->equalTo($mockValidator), $this->equalTo('foo'), $this->equalTo(stubRequest::SOURCE_HEADER))
                          ->will($this->returnValue('blub'));
        $this->assertEquals('blub', $this->request->getValidatedValue($mockValidator, 'foo', stubRequest::SOURCE_HEADER));
        
        $mockFilter = $this->getMock('stubFilter');
        $this->mockRequest->expects($this->once())
                          ->method('getFilteredValue')
                          ->with($this->equalTo($mockFilter), $this->equalTo('foo'), $this->equalTo(stubRequest::SOURCE_HEADER))
                          ->will($this->returnValue('blub'));
        $this->assertEquals('blub', $this->request->getFilteredValue($mockFilter, 'foo', stubRequest::SOURCE_HEADER));
    }

    /**
     * test that headers are handles as expected
     *
     * @test
     */
    public function cookie()
    {
        $this->mockRequest->expects($this->once())
                          ->method('hasValue')
                          ->with($this->equalTo('foo'), $this->equalTo(stubRequest::SOURCE_COOKIE))
                          ->will($this->returnValue(true));
        $this->assertTrue($this->request->hasValue('foo', stubRequest::SOURCE_COOKIE));
        
        $mockValidator = $this->getMock('stubValidator');
        $this->mockRequest->expects($this->once())
                          ->method('validateValue')
                          ->with($this->equalTo($mockValidator), $this->equalTo('foo'), $this->equalTo(stubRequest::SOURCE_COOKIE))
                          ->will($this->returnValue('blub'));
        $this->assertEquals('blub', $this->request->validateValue($mockValidator, 'foo', stubRequest::SOURCE_COOKIE));
        
        $this->mockRequest->expects($this->once())
                          ->method('getValidatedValue')
                          ->with($this->equalTo($mockValidator), $this->equalTo('foo'), $this->equalTo(stubRequest::SOURCE_COOKIE))
                          ->will($this->returnValue('blub'));
        $this->assertEquals('blub', $this->request->getValidatedValue($mockValidator, 'foo', stubRequest::SOURCE_COOKIE));
        
        $mockFilter = $this->getMock('stubFilter');
        $this->mockRequest->expects($this->once())
                          ->method('getFilteredValue')
                          ->with($this->equalTo($mockFilter), $this->equalTo('foo'), $this->equalTo(stubRequest::SOURCE_COOKIE))
                          ->will($this->returnValue('blub'));
        $this->assertEquals('blub', $this->request->getFilteredValue($mockFilter, 'foo', stubRequest::SOURCE_COOKIE));
    }

    /**
     * assure that value error handling works correct
     *
     * @test
     */
    public function valueError()
    {
        $errorValue = new stubRequestValueError('bar', array());
        $this->mockRequest->expects($this->once())
                          ->method('addValueError')
                          ->with($this->equalTo($errorValue), $this->equalTo('test_foo'), $this->equalTo(stubRequest::SOURCE_PARAM));
        $this->request->addValueError($errorValue, 'foo');
        
        $this->mockRequest->expects($this->once())
                          ->method('hasValueError')
                          ->with($this->equalTo('test_foo'), $this->equalTo(stubRequest::SOURCE_PARAM))
                          ->will($this->returnValue(true));
        $this->assertTrue($this->request->hasValueError('foo'));
        
        $this->mockRequest->expects($this->once())
                          ->method('hasValueErrorWithId')
                          ->with($this->equalTo('test_foo'), $this->equalTo('bar'), $this->equalTo(stubRequest::SOURCE_PARAM))
                          ->will($this->returnValue(true));
        $this->assertTrue($this->request->hasValueErrorWithId('foo', 'bar'));
        
        $this->mockRequest->expects($this->once())
                          ->method('getValueErrorWithId')
                          ->with($this->equalTo('test_foo'), $this->equalTo('bar'), $this->equalTo(stubRequest::SOURCE_PARAM))
                          ->will($this->returnValue($errorValue));
        $this->assertSame($errorValue, $this->request->getValueErrorWithId('foo', 'bar'));
        
        $this->mockRequest->expects($this->once())
                          ->method('getValueError')
                          ->with($this->equalTo('test_foo'), $this->equalTo(stubRequest::SOURCE_PARAM))
                          ->will($this->returnValue($errorValue));
        $this->assertSame($errorValue, $this->request->getValueError('foo'));
        
        $this->mockRequest->expects($this->exactly(2))
                          ->method('getValueErrors')
                          ->with($this->equalTo(stubRequest::SOURCE_PARAM))
                          ->will($this->returnValue(array('test_foo' => array(), 'bar' => array())));
        $this->assertTrue($this->request->hasValueErrors());
        $this->assertEquals(array('foo' => array()), $this->request->getValueErrors());
    }

    /**
     * assure that value error handling works correct
     *
     * @test
     */
    public function headerError()
    {
        $errorValue = new stubRequestValueError('bar', array());
        $this->mockRequest->expects($this->once())
                          ->method('addValueError')
                          ->with($this->equalTo($errorValue), $this->equalTo('foo'), $this->equalTo(stubRequest::SOURCE_HEADER));
        $this->request->addValueError($errorValue, 'foo', stubRequest::SOURCE_HEADER);
        
        $this->mockRequest->expects($this->once())
                          ->method('hasValueError')
                          ->with($this->equalTo('foo'), $this->equalTo(stubRequest::SOURCE_HEADER))
                          ->will($this->returnValue(true));
        $this->assertTrue($this->request->hasValueError('foo', stubRequest::SOURCE_HEADER));
        
        $this->mockRequest->expects($this->once())
                          ->method('hasValueErrorWithId')
                          ->with($this->equalTo('foo'), $this->equalTo('bar'), $this->equalTo(stubRequest::SOURCE_HEADER))
                          ->will($this->returnValue(true));
        $this->assertTrue($this->request->hasValueErrorWithId('foo', 'bar', stubRequest::SOURCE_HEADER));
        
        $this->mockRequest->expects($this->once())
                          ->method('getValueErrorWithId')
                          ->with($this->equalTo('foo'), $this->equalTo('bar'), $this->equalTo(stubRequest::SOURCE_HEADER))
                          ->will($this->returnValue($errorValue));
        $this->assertSame($errorValue, $this->request->getValueErrorWithId('foo', 'bar', stubRequest::SOURCE_HEADER));
        
        $this->mockRequest->expects($this->once())
                          ->method('getValueError')
                          ->with($this->equalTo('foo'), $this->equalTo(stubRequest::SOURCE_HEADER))
                          ->will($this->returnValue($errorValue));
        $this->assertSame($errorValue, $this->request->getValueError('foo', stubRequest::SOURCE_HEADER));
        
        $this->mockRequest->expects($this->exactly(2))
                          ->method('getValueErrors')
                          ->with($this->equalTo(stubRequest::SOURCE_HEADER))
                          ->will($this->returnValue(array('test_foo' => array(), 'bar' => array())));
        $this->assertTrue($this->request->hasValueErrors(stubRequest::SOURCE_HEADER));
        $this->assertEquals(array('test_foo' => array(), 'bar' => array()), $this->request->getValueErrors(stubRequest::SOURCE_HEADER));
    }

    /**
     * assure that value error handling works correct
     *
     * @test
     */
    public function cookieError()
    {
        $errorValue = new stubRequestValueError('bar', array());
        $this->mockRequest->expects($this->once())
                          ->method('addValueError')
                          ->with($this->equalTo($errorValue), $this->equalTo('foo'), $this->equalTo(stubRequest::SOURCE_COOKIE));
        $this->request->addValueError($errorValue, 'foo', stubRequest::SOURCE_COOKIE);
        
        $this->mockRequest->expects($this->once())
                          ->method('hasValueError')
                          ->with($this->equalTo('foo'), $this->equalTo(stubRequest::SOURCE_COOKIE))
                          ->will($this->returnValue(true));
        $this->assertTrue($this->request->hasValueError('foo', stubRequest::SOURCE_COOKIE));
        
        $this->mockRequest->expects($this->once())
                          ->method('hasValueErrorWithId')
                          ->with($this->equalTo('foo'), $this->equalTo('bar'), $this->equalTo(stubRequest::SOURCE_COOKIE))
                          ->will($this->returnValue(true));
        $this->assertTrue($this->request->hasValueErrorWithId('foo', 'bar', stubRequest::SOURCE_COOKIE));
        
        $this->mockRequest->expects($this->once())
                          ->method('getValueErrorWithId')
                          ->with($this->equalTo('foo'), $this->equalTo('bar'), $this->equalTo(stubRequest::SOURCE_COOKIE))
                          ->will($this->returnValue($errorValue));
        $this->assertSame($errorValue, $this->request->getValueErrorWithId('foo', 'bar', stubRequest::SOURCE_COOKIE));
        
        $this->mockRequest->expects($this->once())
                          ->method('getValueError')
                          ->with($this->equalTo('foo'), $this->equalTo(stubRequest::SOURCE_COOKIE))
                          ->will($this->returnValue($errorValue));
        $this->assertSame($errorValue, $this->request->getValueError('foo', stubRequest::SOURCE_COOKIE));
        
        $this->mockRequest->expects($this->exactly(2))
                          ->method('getValueErrors')
                          ->with($this->equalTo(stubRequest::SOURCE_COOKIE))
                          ->will($this->returnValue(array('test_foo' => array(), 'bar' => array())));
        $this->assertTrue($this->request->hasValueErrors(stubRequest::SOURCE_COOKIE));
        $this->assertEquals(array('test_foo' => array(), 'bar' => array()), $this->request->getValueErrors(stubRequest::SOURCE_COOKIE));
    }

    /**
     * assure that the cancel methods are called
     *
     * @test
     */
    public function cancel()
    {
        $this->mockRequest->expects($this->once())
                          ->method('cancel');
        $this->mockRequest->expects($this->once())
                          ->method('isCancelled')
                          ->will($this->returnValue(true));
        $this->request->cancel();
        $this->assertTrue($this->request->isCancelled());
    }

    /**
     * assure that getMethod() is called
     *
     * @test
     */
    public function getMethod()
    {
        $this->mockRequest->expects($this->once())
                          ->method('getMethod')
                          ->will($this->returnValue('test'));
        $this->assertEquals('test', $this->request->getMethod());
    }

    /**
     * assure that getURI() is called
     *
     * @test
     */
    public function getURI()
    {
        $this->mockRequest->expects($this->once())
                          ->method('getURI')
                          ->will($this->returnValue('test'));
        $this->assertEquals('test', $this->request->getURI());
    }

    /**
     * assure that raw data is handles correct
     *
     * @test
     */
    public function validateRawData()
    {
        $mockValidator = $this->getMock('stubValidator');
        $this->mockRequest->expects($this->once())
                          ->method('validateRawData')
                          ->with($this->equalTo($mockValidator))
                          ->will($this->returnValue(false));
        $this->assertFalse($this->request->validateRawData($mockValidator));
    }

    /**
     * assure that raw data is handles correct
     *
     * @test
     */
    public function getValidatedRawData()
    {
        $mockValidator = $this->getMock('stubValidator');
        $this->mockRequest->expects($this->once())
                          ->method('getValidatedRawData')
                          ->with($this->equalTo($mockValidator))
                          ->will($this->returnValue('foo'));
        $this->assertEquals('foo', $this->request->getValidatedRawData($mockValidator));
    }

    /**
     * assure that raw data is handles correct
     *
     * @test
     */
    public function getFilteredRawData()
    {
        $mockFilter = $this->getMock('stubFilter');
        $this->mockRequest->expects($this->once())
                          ->method('getFilteredRawData')
                          ->with($this->equalTo($mockFilter))
                          ->will($this->returnValue('foo'));
        $this->assertEquals('foo', $this->request->getFilteredRawData($mockFilter));
    }

    /**
     * assure that value keys are delivered correct
     *
     * @test
     */
    public function valueKeys()
    {
        $this->mockRequest->expects($this->any())
                          ->method('getValueKeys')
                          ->will($this->returnValue(array('test_foo', 'bar_foo')));
        $this->assertEquals(array('foo'), $this->request->getValueKeys());
        $this->assertEquals(array('foo'), $this->request->getValueKeys(stubRequest::SOURCE_PARAM));
        $this->assertEquals(array('test_foo', 'bar_foo'), $this->request->getValueKeys(stubRequest::SOURCE_HEADER));
        $this->assertEquals(array('test_foo', 'bar_foo'), $this->request->getValueKeys(stubRequest::SOURCE_COOKIE));
    }
}
?>