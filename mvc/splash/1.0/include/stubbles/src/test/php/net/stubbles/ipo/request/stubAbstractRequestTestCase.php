<?php
/**
 * Tests for net::stubbles::ipo::request::stubAbstractRequest.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_test
 */
stubClassLoader::load('net::stubbles::ipo::request::stubAbstractRequest',
                      'net::stubbles::ipo::request::filter::stubFilter'
);
class stubTestExceptionFilter extends stubBaseObject implements stubFilter
{
    public function execute($value)
    {
        throw new stubFilterException(new stubRequestValueError('foo', array()));
    }
}
class stubTestRequest extends stubAbstractRequest
{
    protected $_rawData = 'This is the raw request data.';
    
    protected function doConstuct()
    {
        $this->unsecureParams  = array('foo' => 'bar');
        $this->unsecureHeaders = array('bar' => 'baz');
        $this->unsecureCookies = array('baz' => 'foo');
    }

    public function removeCookieValues()
    {
        $this->unsecureCookies = array();
    }

    public function getMethod()
    {
        return 'test';
    }
    
    public function getURI()
    {
        return 'test://' . __FILE__;
    }
    
    public function setRawData($rawData)
    {
        $this->_rawData = $rawData;
    }
    
    protected function getRawData()
    {
        return $this->_rawData;
    }
}
/**
 * Tests for net::stubbles::ipo::request::stubAbstractRequest.
 *
 * @package     stubbles
 * @subpackage  ipo_request_test
 * @group       ipo
 * @group       ipo_request
 */
class stubAbstractRequestTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubTestRequest
     */
    protected $request;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->request = new stubTestRequest();
    }

    /**
     * test whether cookies are accepted or not
     *
     * @test
     */
    public function acceptsCookies()
    {
        $this->assertTrue($this->request->acceptsCookies());
        $this->request->removeCookieValues();
        $this->assertFalse($this->request->acceptsCookies());
    }

    /**
     * test that values are handles as expected
     *
     * @test
     */
    public function value()
    {
        $this->assertTrue($this->request->hasValue('foo'));
        $this->assertFalse($this->request->hasValue('baz'));
        
        $this->assertTrue($this->request->hasValue('foo', stubRequest::SOURCE_PARAM));
        $this->assertFalse($this->request->hasValue('baz', stubRequest::SOURCE_PARAM));
        
        $this->assertTrue($this->request->hasValue('foo', 'dummy'));
        $this->assertFalse($this->request->hasValue('baz', 'dummy'));
        
        $mockValidator = $this->getMock('stubValidator');
        $mockValidator->expects($this->exactly(6))
                      ->method('validate')
                      ->will($this->onConsecutiveCalls(true, false, true, false, true, false));
        $this->assertTrue($this->request->validateValue($mockValidator, 'foo'));
        $this->assertFalse($this->request->validateValue($mockValidator, 'foo'));
        $this->assertFalse($this->request->validateValue($mockValidator, 'baz'));
        
        $this->assertTrue($this->request->validateValue($mockValidator, 'foo', stubRequest::SOURCE_PARAM));
        $this->assertFalse($this->request->validateValue($mockValidator, 'foo', stubRequest::SOURCE_PARAM));
        $this->assertFalse($this->request->validateValue($mockValidator, 'baz', stubRequest::SOURCE_PARAM));
        
        $this->assertTrue($this->request->validateValue($mockValidator, 'foo', 'dummy'));
        $this->assertFalse($this->request->validateValue($mockValidator, 'foo', 'dummy'));
        $this->assertFalse($this->request->validateValue($mockValidator, 'baz', 'dummy'));
        
        $mockValidator = $this->getMock('stubValidator');
        $mockValidator->expects($this->exactly(6))
                      ->method('validate')
                      ->will($this->onConsecutiveCalls(true, false, true, false, true, false));
        $this->assertEquals('bar', $this->request->getValidatedValue($mockValidator, 'foo'));
        $this->assertNull($this->request->getValidatedValue($mockValidator, 'foo'));
        $this->assertNull($this->request->getValidatedValue($mockValidator, 'baz'));
        
        $this->assertEquals('bar', $this->request->getValidatedValue($mockValidator, 'foo', stubRequest::SOURCE_PARAM));
        $this->assertNull($this->request->getValidatedValue($mockValidator, 'foo', stubRequest::SOURCE_PARAM));
        $this->assertNull($this->request->getValidatedValue($mockValidator, 'baz', stubRequest::SOURCE_PARAM));
        
        $this->assertEquals('bar', $this->request->getValidatedValue($mockValidator, 'foo', 'dummy'));
        $this->assertNull($this->request->getValidatedValue($mockValidator, 'foo', 'dummy'));
        $this->assertNull($this->request->getValidatedValue($mockValidator, 'baz', 'dummy'));
        
        $mockFilter = $this->getMock('stubFilter');
        $mockFilter->expects($this->exactly(9))
                   ->method('execute')
                   ->will($this->onConsecutiveCalls('baz', null, 'bam', 'baz', null, 'bam', 'baz', null, 'bam'));
        $this->assertEquals('baz', $this->request->getFilteredValue($mockFilter, 'baz'));
        $this->assertNull($this->request->getFilteredValue($mockFilter, 'baz'));
        $this->assertEquals('bam', $this->request->getFilteredValue($mockFilter, 'foo'));
        
        $this->assertEquals('baz', $this->request->getFilteredValue($mockFilter, 'baz', stubRequest::SOURCE_PARAM));
        $this->assertNull($this->request->getFilteredValue($mockFilter, 'baz', stubRequest::SOURCE_PARAM));
        $this->assertEquals('bam', $this->request->getFilteredValue($mockFilter, 'foo', stubRequest::SOURCE_PARAM));
        
        $this->assertEquals('baz', $this->request->getFilteredValue($mockFilter, 'baz', 'dummy'));
        $this->assertNull($this->request->getFilteredValue($mockFilter, 'baz', 'dummy'));
        $this->assertEquals('bam', $this->request->getFilteredValue($mockFilter, 'foo', 'dummy'));
    }

    /**
     * test that headers are handles as expected
     *
     * @test
     */
    public function header()
    {
        $this->assertTrue($this->request->hasValue('bar', stubRequest::SOURCE_HEADER));
        $this->assertFalse($this->request->hasValue('baz', stubRequest::SOURCE_HEADER));
        
        $mockValidator = $this->getMock('stubValidator');
        $mockValidator->expects($this->exactly(2))
                      ->method('validate')
                      ->will($this->onConsecutiveCalls(true, false));
        $this->assertTrue($this->request->validateValue($mockValidator, 'bar', stubRequest::SOURCE_HEADER));
        $this->assertFalse($this->request->validateValue($mockValidator, 'bar', stubRequest::SOURCE_HEADER));
        $this->assertFalse($this->request->validateValue($mockValidator, 'baz', stubRequest::SOURCE_HEADER));
        
        $mockValidator = $this->getMock('stubValidator');
        $mockValidator->expects($this->exactly(2))
                      ->method('validate')
                      ->will($this->onConsecutiveCalls(true, false));
        $this->assertEquals('baz', $this->request->getValidatedValue($mockValidator, 'bar', stubRequest::SOURCE_HEADER));
        $this->assertNull($this->request->getValidatedValue($mockValidator, 'bar', stubRequest::SOURCE_HEADER));
        $this->assertNull($this->request->getValidatedValue($mockValidator, 'baz', stubRequest::SOURCE_HEADER));
        
        $mockFilter = $this->getMock('stubFilter');
        $mockFilter->expects($this->exactly(3))
                   ->method('execute')
                   ->will($this->onConsecutiveCalls('foo', null, 'bam'));
        $this->assertEquals('foo', $this->request->getFilteredValue($mockFilter, 'baz', stubRequest::SOURCE_HEADER));
        $this->assertNull($this->request->getFilteredValue($mockFilter, 'baz', stubRequest::SOURCE_HEADER));
        $this->assertEquals('bam', $this->request->getFilteredValue($mockFilter, 'bar', stubRequest::SOURCE_HEADER));
    }

    /**
     * test that headers are handles as expected
     *
     * @test
     */
    public function cookie()
    {
        $this->assertTrue($this->request->hasValue('baz', stubRequest::SOURCE_COOKIE));
        $this->assertFalse($this->request->hasValue('foo', stubRequest::SOURCE_COOKIE));
        
        $mockValidator = $this->getMock('stubValidator');
        $mockValidator->expects($this->exactly(2))
                      ->method('validate')
                      ->will($this->onConsecutiveCalls(true, false));
        $this->assertTrue($this->request->validateValue($mockValidator, 'baz', stubRequest::SOURCE_COOKIE));
        $this->assertFalse($this->request->validateValue($mockValidator, 'baz', stubRequest::SOURCE_COOKIE));
        $this->assertFalse($this->request->validateValue($mockValidator, 'foo', stubRequest::SOURCE_COOKIE));
        
        $mockValidator = $this->getMock('stubValidator');
        $mockValidator->expects($this->exactly(2))
                      ->method('validate')
                      ->will($this->onConsecutiveCalls(true, false));
        $this->assertEquals('foo', $this->request->getValidatedValue($mockValidator, 'baz', stubRequest::SOURCE_COOKIE));
        $this->assertNull($this->request->getValidatedValue($mockValidator, 'baz', stubRequest::SOURCE_COOKIE));
        $this->assertNull($this->request->getValidatedValue($mockValidator, 'foo', stubRequest::SOURCE_COOKIE));
        
        $mockFilter = $this->getMock('stubFilter');
        $mockFilter->expects($this->exactly(3))
                   ->method('execute')
                   ->will($this->onConsecutiveCalls('baz', null, 'bam'));
        $this->assertEquals('baz', $this->request->getFilteredValue($mockFilter, 'foo', stubRequest::SOURCE_COOKIE));
        $this->assertNull($this->request->getFilteredValue($mockFilter, 'foo', stubRequest::SOURCE_COOKIE));
        $this->assertEquals('bam', $this->request->getFilteredValue($mockFilter, 'baz', stubRequest::SOURCE_COOKIE));
    }

    /**
     * assure that cancelling the request works as expected
     *
     * @test
     */
    public function cancelRequest()
    {
        $this->assertFalse($this->request->isCancelled());
        $this->request->cancel();
        $this->assertTrue($this->request->isCancelled());
    }

    /**
     * assure that value error handling works correct
     *
     * @test
     */
    public function valueError()
    {
        $this->assertFalse($this->request->hasValueError('foo'));
        $this->assertFalse($this->request->hasValueErrorWithId('foo', 'bar'));
        $this->assertNull($this->request->getValueErrorWithId('foo', 'bar'));
        $this->assertEquals(array(), $this->request->getValueError('foo'));
        $this->assertFalse($this->request->hasValueErrors());
        $this->assertEquals(array(), $this->request->getValueErrors('foo'));
        
        $this->assertFalse($this->request->hasValueError('foo', stubRequest::SOURCE_PARAM));
        $this->assertFalse($this->request->hasValueErrorWithId('foo', 'bar', stubRequest::SOURCE_PARAM));
        $this->assertNull($this->request->getValueErrorWithId('foo', 'bar', stubRequest::SOURCE_PARAM));
        $this->assertEquals(array(), $this->request->getValueError('foo', stubRequest::SOURCE_PARAM));
        $this->assertEquals(array(), $this->request->getValueErrors('foo', stubRequest::SOURCE_PARAM));
        
        $this->assertFalse($this->request->hasValueError('foo', 'dummy'));
        $this->assertEquals(array(), $this->request->getValueError('foo', 'dummy'));
        $this->assertEquals(array(), $this->request->getValueErrors('foo', 'dummy'));
        
        $errorValue = new stubRequestValueError('bar', array());
        $this->request->addValueError($errorValue, 'foo');
        $this->assertTrue($this->request->hasValueError('foo'));
        $this->assertTrue($this->request->hasValueErrorWithId('foo', 'bar'));
        $this->assertEquals($errorValue, $this->request->getValueErrorWithId('foo', 'bar'));
        $this->assertEquals(array('bar' => $errorValue), $this->request->getValueError('foo'));
        $this->assertTrue($this->request->hasValueErrors());
        $this->assertEquals(array('foo' => array('bar' => $errorValue)), $this->request->getValueErrors('foo'));
        
        $this->assertTrue($this->request->hasValueError('foo', stubRequest::SOURCE_PARAM));
        $this->assertTrue($this->request->hasValueErrorWithId('foo', 'bar', stubRequest::SOURCE_PARAM));
        $this->assertEquals($errorValue, $this->request->getValueErrorWithId('foo', 'bar', stubRequest::SOURCE_PARAM));
        $this->assertEquals(array('bar' => $errorValue), $this->request->getValueError('foo', stubRequest::SOURCE_PARAM));
        $this->assertTrue($this->request->hasValueErrors(stubRequest::SOURCE_PARAM));
        $this->assertEquals(array('foo' => array('bar' => $errorValue)), $this->request->getValueErrors('foo', stubRequest::SOURCE_PARAM));
        
        $this->assertTrue($this->request->hasValueError('foo', 'dummy'));
        $this->assertTrue($this->request->hasValueErrorWithId('foo', 'bar', 'dummy'));
        $this->assertEquals($errorValue, $this->request->getValueErrorWithId('foo', 'bar', 'dummy'));
        $this->assertEquals(array('bar' => $errorValue), $this->request->getValueError('foo', 'dummy'));
        $this->assertTrue($this->request->hasValueErrors('dummy'));
        $this->assertEquals(array('foo' => array('bar' => $errorValue)), $this->request->getValueErrors('foo', 'dummy'));
    }

    /**
     * assure that value error handling works correct
     *
     * @test
     */
    public function headerError()
    {
        $this->assertFalse($this->request->hasValueError('foo', stubRequest::SOURCE_HEADER));
        $this->assertFalse($this->request->hasValueErrorWithId('foo', 'bar', stubRequest::SOURCE_HEADER));
        $this->assertNull($this->request->getValueErrorWithId('foo', 'bar', stubRequest::SOURCE_HEADER));
        $this->assertEquals(array(), $this->request->getValueError('foo', stubRequest::SOURCE_HEADER));
        $this->assertFalse($this->request->hasValueErrors(stubRequest::SOURCE_HEADER));
        $this->assertEquals(array(), $this->request->getValueErrors(stubRequest::SOURCE_HEADER));
        
        $errorValue = new stubRequestValueError('bar', array());
        $this->request->addValueError($errorValue, 'foo', stubRequest::SOURCE_HEADER);
        $this->assertTrue($this->request->hasValueErrorWithId('foo', 'bar', stubRequest::SOURCE_HEADER));
        $this->assertEquals($errorValue, $this->request->getValueErrorWithId('foo', 'bar', stubRequest::SOURCE_HEADER));
        $this->assertTrue($this->request->hasValueError('foo', stubRequest::SOURCE_HEADER));
        $this->assertEquals(array('bar' => $errorValue), $this->request->getValueError('foo', stubRequest::SOURCE_HEADER));
        $this->assertTrue($this->request->hasValueErrors(stubRequest::SOURCE_HEADER));
        $this->assertEquals(array('foo' => array('bar' => $errorValue)), $this->request->getValueErrors(stubRequest::SOURCE_HEADER));
    }

    /**
     * assure that value error handling works correct
     *
     * @test
     */
    public function cookieError()
    {
        $this->assertFalse($this->request->hasValueError('foo', stubRequest::SOURCE_COOKIE));
        $this->assertFalse($this->request->hasValueErrorWithId('foo', 'bar', stubRequest::SOURCE_COOKIE));
        $this->assertNull($this->request->getValueErrorWithId('foo', 'bar', stubRequest::SOURCE_COOKIE));
        $this->assertEquals(array(), $this->request->getValueError('baz', stubRequest::SOURCE_COOKIE));
        $this->assertFalse($this->request->hasValueErrors(stubRequest::SOURCE_COOKIE));
        $this->assertEquals(array(), $this->request->getValueErrors(stubRequest::SOURCE_COOKIE));
        
        $errorValue = new stubRequestValueError('bar', array());
        $this->request->addValueError($errorValue, 'foo', stubRequest::SOURCE_COOKIE);
        $this->assertTrue($this->request->hasValueError('foo', stubRequest::SOURCE_COOKIE));
        $this->assertTrue($this->request->hasValueErrorWithId('foo', 'bar', stubRequest::SOURCE_COOKIE));
        $this->assertEquals($errorValue, $this->request->getValueErrorWithId('foo', 'bar', stubRequest::SOURCE_COOKIE));
        $this->assertEquals(array('bar' => $errorValue), $this->request->getValueError('foo', stubRequest::SOURCE_COOKIE));
        $this->assertTrue($this->request->hasValueErrors(stubRequest::SOURCE_COOKIE));
        $this->assertEquals(array('foo' => array('bar' => $errorValue)), $this->request->getValueErrors(stubRequest::SOURCE_COOKIE));
    }

    /**
     * assure that the same error occurs only once in list of errors for a value
     *
     * @test
     */
    public function errorOnlyAddedOnce()
    {
        $errorValue = new stubRequestValueError('foo', array());
        $this->request->addValueError($errorValue, 'foo');
        $this->assertEquals(array('foo' => $errorValue), $this->request->getValueError('foo'));
        $this->request->getFilteredValue(new stubTestExceptionFilter(), 'foo');
        $this->assertEquals(array('foo' => $errorValue), $this->request->getValueError('foo'));
    }

    /**
     * assure that value keys are delivered correct
     *
     * @test
     */
    public function valueKeys()
    {
        $this->assertEquals(array('foo'), $this->request->getValueKeys());
        $this->assertEquals(array('foo'), $this->request->getValueKeys(stubRequest::SOURCE_PARAM));
        $this->assertEquals(array('bar'), $this->request->getValueKeys(stubRequest::SOURCE_HEADER));
        $this->assertEquals(array('baz'), $this->request->getValueKeys(stubRequest::SOURCE_COOKIE));
    }

    /**
     * assure that raw data is handles correct
     *
     * @test
     */
    public function validateRawData()
    {
        $mockValidator = $this->getMock('stubValidator');
        $mockValidator->expects($this->exactly(2))
                      ->method('validate')
                      ->will($this->onConsecutiveCalls(true, false));
        $this->assertTrue($this->request->validateRawData($mockValidator));
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
        $mockValidator->expects($this->exactly(2))
                      ->method('validate')
                      ->will($this->onConsecutiveCalls(true, false));
        $this->assertEquals('This is the raw request data.', $this->request->getValidatedRawData($mockValidator));
        $this->assertNull($this->request->getValidatedRawData($mockValidator));
    }

    /**
     * assure that raw data is handled correct
     *
     * @test
     */
    public function getFilteredRawData()
    {
        $mockFilter = $this->getMock('stubFilter');
        $mockFilter->expects($this->exactly(3))
                   ->method('execute')
                   ->will($this->onConsecutiveCalls('foo', null, 'bam'));
        $this->request->setRawData(null);
        $this->assertEquals('foo', $this->request->getFilteredRawData($mockFilter));
        $this->assertNull($this->request->getFilteredRawData($mockFilter));
        
        $this->request->setRawData('bar');
        $this->assertEquals('bam', $this->request->getFilteredRawData($mockFilter));
    }

    /**
     * assure that raw data is handles correct
     *
     * @test
     * @expectedException  stubFilterException
     */
    public function getFilteredRawDataFails()
    {
        $this->request->getFilteredRawData(new stubTestExceptionFilter());
    }

    /**
     * cloning is forbidden
     *
     * @test
     * @expectedException  stubRuntimeException
     */
    public function cloneRequest()
    {
        $request = clone $this->request;
    }
}
?>