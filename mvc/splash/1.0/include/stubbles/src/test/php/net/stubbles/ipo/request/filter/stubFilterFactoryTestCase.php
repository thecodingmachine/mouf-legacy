<?php
/**
 * Tests for net::stubbles::ipo::request::filter::stubFilterFactory.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_filter_test
 */
stubClassLoader::load('net::stubbles::ipo::request::filter::stubFilterFactory');
/**
 * Tests for net::stubbles::ipo::request::filter::stubFilterFactory.
 *
 * @package     stubbles
 * @subpackage  ipo_request_filter_test
 * @group       ipo
 * @group       ipo_request
 * @group       ipo_request_filter
 */
class stubFilterFactoryTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * clean up test environment
     */
    public function tearDown()
    {
        stubRegistry::removeConfig('net.stubbles.ipo.request.valueerrorfactory.class');
    }

    /**
     * test that integer filter is created
     *
     * @test
     */
    public function integerFilter()
    {
        $filter = stubFilterFactory::forType('integer');
        $this->assertType('stubFilterFactory', $filter);
        $this->assertType('stubIntegerFilter', $filter->getDecoratedFilter());
        $filter = stubFilterFactory::forType('int');
        $this->assertType('stubFilterFactory', $filter);
        $this->assertType('stubIntegerFilter', $filter->getDecoratedFilter());
    }

    /**
     * test that range filter is created decorating an integer filter
     *
     * @test
     */
    public function rangeFilter()
    {
        $filter = stubFilterFactory::forType('int')->inRange(1, 4);
        $this->assertType('stubFilterFactory', $filter);
        $rangeFilterDecorator = $filter->getDecoratedFilter();
        $this->assertType('stubRangeFilterDecorator', $rangeFilterDecorator);
        $this->assertEquals(1, $rangeFilterDecorator->getMinValidator()->getValue());
        $this->assertEquals('VALUE_TOO_SMALL', $rangeFilterDecorator->getMinErrorId());
        $this->assertEquals(4, $rangeFilterDecorator->getMaxValidator()->getValue());
        $this->assertEquals('VALUE_TOO_GREAT', $rangeFilterDecorator->getMaxErrorId());
        $this->assertType('stubIntegerFilter', $rangeFilterDecorator->getDecoratedFilter());
    }

    /**
     * test that range filter is created decorating an integer filter
     *
     * @test
     */
    public function rangeFilterDifferentErrorIds()
    {
        $filter = stubFilterFactory::forType('int')->inRange(1, 4, 'differentMin', 'differentMax');
        $this->assertType('stubFilterFactory', $filter);
        $rangeFilterDecorator = $filter->getDecoratedFilter();
        $this->assertType('stubRangeFilterDecorator', $rangeFilterDecorator);
        $this->assertEquals(1, $rangeFilterDecorator->getMinValidator()->getValue());
        $this->assertEquals('differentMin', $rangeFilterDecorator->getMinErrorId());
        $this->assertEquals(4, $rangeFilterDecorator->getMaxValidator()->getValue());
        $this->assertEquals('differentMax', $rangeFilterDecorator->getMaxErrorId());
        $this->assertType('stubIntegerFilter', $rangeFilterDecorator->getDecoratedFilter());
    }

    /**
     * test that range filter is created decorating an integer filter
     *
     * @test
     */
    public function rangeFilterWithoutLowerBorder()
    {
        $filter = stubFilterFactory::forType('int')->inRange(null, 4);
        $this->assertType('stubFilterFactory', $filter);
        $rangeFilterDecorator = $filter->getDecoratedFilter();
        $this->assertType('stubRangeFilterDecorator', $rangeFilterDecorator);
        $this->assertNull($rangeFilterDecorator->getMinValidator());
        $this->assertEquals(4, $rangeFilterDecorator->getMaxValidator()->getValue());
        $this->assertType('stubIntegerFilter', $rangeFilterDecorator->getDecoratedFilter());
    }

    /**
     * test that range filter is created decorating an integer filter
     *
     * @test
     */
    public function rangeFilterWithoutUpperBorder()
    {
        $filter = stubFilterFactory::forType('int')->inRange(1, null);
        $this->assertType('stubFilterFactory', $filter);
        $rangeFilterDecorator = $filter->getDecoratedFilter();
        $this->assertType('stubRangeFilterDecorator', $rangeFilterDecorator);
        $this->assertEquals(1, $rangeFilterDecorator->getMinValidator()->getValue());
        $this->assertNull($rangeFilterDecorator->getMaxValidator());
        $this->assertType('stubIntegerFilter', $rangeFilterDecorator->getDecoratedFilter());
    }

    /**
     * test that integer filter is created
     *
     * @test
     */
    public function rangeFilterWithoutBorder()
    {
        $filter = stubFilterFactory::forType('int')->inRange(null, null);
        $this->assertType('stubFilterFactory', $filter);
        $this->assertType('stubIntegerFilter', $filter->getDecoratedFilter());
    }

    /**
     * test that float filter is created
     *
     * @test
     */
    public function floatFilter()
    {
        $filter = stubFilterFactory::forType('double');
        $this->assertType('stubFilterFactory', $filter);
        $this->assertType('stubFloatFilter', $filter->getDecoratedFilter());
        $filter = stubFilterFactory::forType('float');
        $this->assertType('stubFilterFactory', $filter);
        $this->assertType('stubFloatFilter', $filter->getDecoratedFilter());
    }

    /**
     * test that string filter is created
     *
     * @test
     */
    public function stringFilter()
    {
        $filter = stubFilterFactory::forType('string');
        $this->assertType('stubFilterFactory', $filter);
        $this->assertType('stubStringFilter', $filter->getDecoratedFilter());
    }

    /**
     * test that text filter is created
     *
     * @test
     */
    public function textFilter()
    {
        $filter = stubFilterFactory::forType('text');
        $this->assertType('stubFilterFactory', $filter);
        $this->assertType('stubTextFilter', $filter->getDecoratedFilter());
    }

    /**
     * test that length filter is created decorating a text filter
     *
     * @test
     */
    public function lengthFilter()
    {
        $filter = stubFilterFactory::forType('text')->length(2, 5);
        $this->assertType('stubFilterFactory', $filter);
        $lengthFilterDecorator = $filter->getDecoratedFilter();
        $this->assertType('stubLengthFilterDecorator', $lengthFilterDecorator);
        $this->assertEquals(2, $lengthFilterDecorator->getMinLengthValidator()->getValue());
        $this->assertEquals('STRING_TOO_SHORT', $lengthFilterDecorator->getMinLengthErrorId());
        $this->assertEquals(5, $lengthFilterDecorator->getMaxLengthValidator()->getValue());
        $this->assertEquals('STRING_TOO_LONG', $lengthFilterDecorator->getMaxLengthErrorId());
        $this->assertType('stubTextFilter', $lengthFilterDecorator->getDecoratedFilter());
    }

    /**
     * test that length filter is created decorating a text filter
     *
     * @test
     */
    public function lengthFilterWithDifferentErrorIds()
    {
        $filter = stubFilterFactory::forType('text')->length(2, 5, 'differentMin', 'differentMax');
        $this->assertType('stubFilterFactory', $filter);
        $lengthFilterDecorator = $filter->getDecoratedFilter();
        $this->assertType('stubLengthFilterDecorator', $lengthFilterDecorator);
        $this->assertEquals(2, $lengthFilterDecorator->getMinLengthValidator()->getValue());
        $this->assertEquals('differentMin', $lengthFilterDecorator->getMinLengthErrorId());
        $this->assertEquals(5, $lengthFilterDecorator->getMaxLengthValidator()->getValue());
        $this->assertEquals('differentMax', $lengthFilterDecorator->getMaxLengthErrorId());
        $this->assertType('stubTextFilter', $lengthFilterDecorator->getDecoratedFilter());
    }

    /**
     * test that length filter is created decorating a text filter
     *
     * @test
     */
    public function lengthFilterWithoutLowerBorder()
    {
        $filter = stubFilterFactory::forType('text')->length(null, 5);
        $this->assertType('stubFilterFactory', $filter);
        $lengthFilterDecorator = $filter->getDecoratedFilter();
        $this->assertType('stubLengthFilterDecorator', $lengthFilterDecorator);
        $this->assertNull($lengthFilterDecorator->getMinLengthValidator());
        $this->assertEquals(5, $lengthFilterDecorator->getMaxLengthValidator()->getValue());
        $this->assertType('stubTextFilter', $lengthFilterDecorator->getDecoratedFilter());
    }

    /**
     * test that length filter is created decorating a text filter
     *
     * @test
     */
    public function lengthFilterWithoutUpperBorder()
    {
        $filter = stubFilterFactory::forType('text')->length(2, null);
        $this->assertType('stubFilterFactory', $filter);
        $lengthFilterDecorator = $filter->getDecoratedFilter();
        $this->assertType('stubLengthFilterDecorator', $lengthFilterDecorator);
        $this->assertEquals(2, $lengthFilterDecorator->getMinLengthValidator()->getValue());
        $this->assertNull($lengthFilterDecorator->getMaxLengthValidator());
        $this->assertType('stubTextFilter', $lengthFilterDecorator->getDecoratedFilter());
    }

    /**
     * test that text filter is created
     *
     * @test
     */
    public function lengthFilterWithoutBorder()
    {
        $filter = stubFilterFactory::forType('text')->length(null, null);
        $this->assertType('stubFilterFactory', $filter);
        $this->assertType('stubTextFilter', $filter->getDecoratedFilter());
    }

    /**
     * test that required filter is created decorating a string filter
     *
     * @test
     */
    public function requiredFilter()
    {
        $filter = stubFilterFactory::forType('string')->asRequired();
        $this->assertType('stubFilterFactory', $filter);
        $requiredFilterDecorator = $filter->getDecoratedFilter();
        $this->assertType('stubRequiredFilterDecorator', $requiredFilterDecorator);
        $this->assertEquals('FIELD_EMPTY', $requiredFilterDecorator->getErrorId());
        $this->assertType('stubStringFilter', $requiredFilterDecorator->getDecoratedFilter());
    }

    /**
     * test that required filter is created decorating a string filter
     *
     * @test
     */
    public function requiredFilterDifferentErrorId()
    {
        $filter = stubFilterFactory::forType('string')->asRequired('foo');
        $this->assertType('stubFilterFactory', $filter);
        $requiredFilterDecorator = $filter->getDecoratedFilter();
        $this->assertType('stubRequiredFilterDecorator', $requiredFilterDecorator);
        $this->assertEquals('foo', $requiredFilterDecorator->getErrorId());
        $this->assertType('stubStringFilter', $requiredFilterDecorator->getDecoratedFilter());
    }

    /**
     * test that default value filter is created decorating a string filter
     *
     * @test
     */
    public function defaultValueFilter()
    {
        $filter = stubFilterFactory::forType('string')->defaultsTo('foo');
        $this->assertType('stubFilterFactory', $filter);
        $defaultValueFilterDecorator = $filter->getDecoratedFilter();
        $this->assertType('stubDefaultValueFilterDecorator', $defaultValueFilterDecorator);
        $this->assertEquals('foo', $defaultValueFilterDecorator->getDefaultValue());
        $this->assertType('stubStringFilter', $defaultValueFilterDecorator->getDecoratedFilter());
    }

    /**
     * test that validator filter is created decorating a string filter
     *
     * @test
     */
    public function validatorFilter()
    {
        $mockValidator = $this->getMock('stubValidator');
        $filter = stubFilterFactory::forType('string')->validatedBy($mockValidator);
        $this->assertType('stubFilterFactory', $filter);
        $validatorFilterDecorator = $filter->getDecoratedFilter();
        $this->assertType('stubValidatorFilterDecorator', $validatorFilterDecorator);
        $this->assertSame($mockValidator, $validatorFilterDecorator->getValidator());
        $this->assertEquals('FIELD_WRONG_VALUE', $validatorFilterDecorator->getErrorId());
        $this->assertType('stubStringFilter', $validatorFilterDecorator->getDecoratedFilter());
    }

    /**
     * test that validator filter is created decorating a float filter
     *
     * @test
     */
    public function validatorFilterWithErrorId()
    {
        $mockValidator = $this->getMock('stubValidator');
        $filter = stubFilterFactory::forType('float')->validatedBy($mockValidator, 'OTHER_ID');
        $this->assertType('stubFilterFactory', $filter);
        $validatorFilterDecorator = $filter->getDecoratedFilter();
        $this->assertType('stubValidatorFilterDecorator', $validatorFilterDecorator);
        $this->assertSame($mockValidator, $validatorFilterDecorator->getValidator());
        $this->assertEquals('OTHER_ID', $validatorFilterDecorator->getErrorId());
        $this->assertType('stubFloatFilter', $validatorFilterDecorator->getDecoratedFilter());
    }

    /**
     * test that validator filter is created decorating a string filter
     *
     * @test
     */
    public function encodingFilter()
    {
        $mockStringEncoder = $this->getMock('stubStringEncoder');
        $filter = stubFilterFactory::forType('string')->encodedWith($mockStringEncoder);
        $this->assertType('stubFilterFactory', $filter);
        $encodingFilterDecorator = $filter->getDecoratedFilter();
        $this->assertType('stubEncodingFilterDecorator', $encodingFilterDecorator);
        $this->assertSame($mockStringEncoder, $encodingFilterDecorator->getEncoder());
        $this->assertEquals(stubStringEncoder::MODE_ENCODE, $encodingFilterDecorator->getEncoderMode());
        $this->assertType('stubStringFilter', $encodingFilterDecorator->getDecoratedFilter());
    }

    /**
     * test that validator filter is created decorating a text filter
     *
     * @test
     */
    public function decodingFilter()
    {
        $mockStringEncoder = $this->getMock('stubStringEncoder');
        $filter = stubFilterFactory::forType('text')->decodedWith($mockStringEncoder);
        $this->assertType('stubFilterFactory', $filter);
        $encodingFilterDecorator = $filter->getDecoratedFilter();
        $this->assertType('stubEncodingFilterDecorator', $encodingFilterDecorator);
        $this->assertSame($mockStringEncoder, $encodingFilterDecorator->getEncoder());
        $this->assertEquals(stubStringEncoder::MODE_DECODE, $encodingFilterDecorator->getEncoderMode());
        $this->assertType('stubTextFilter', $encodingFilterDecorator->getDecoratedFilter());
    }

    /**
     * test that text filter is created
     *
     * @test
     * @expectedException  stubIllegalArgumentException
     */
    public function illegalFilter()
    {
        $filter = stubFilterFactory::forType('illegal');
    }

    /**
     * test that hpasswordttp filter is created
     *
     * @test
     */
    public function passwordFilter()
    {
        $mockRequestValueErrorFactory = $this->getMock('stubRequestValueErrorFactory');
        $filter = stubFilterFactory::forType('password', array($mockRequestValueErrorFactory))->minDiffChars(3)->nonAllowedValues(array(1, 2, 3));
        $this->assertType('stubFilterFactory', $filter);
        $this->assertSame($mockRequestValueErrorFactory, $filter->getRVEFactory());
        $passwordFilter = $filter->getDecoratedFilter();
        $this->assertType('stubPasswordFilter', $passwordFilter);
        $this->assertEquals(3, $passwordFilter->getMinDiffChars());
        $this->assertEquals(array(1, 2, 3), $passwordFilter->getNonAllowedValues());
    }

    /**
     * test that http filter is created
     *
     * @test
     */
    public function httpFilter()
    {
        $mockRequestValueErrorFactory = $this->getMock('stubRequestValueErrorFactory');
        $filter = stubFilterFactory::forType('http', array($mockRequestValueErrorFactory));
        $this->assertType('stubFilterFactory', $filter);
        $this->assertSame($mockRequestValueErrorFactory, $filter->getRVEFactory());
        $this->assertType('stubHTTPURLFilter', $filter->getDecoratedFilter());
    }

    /**
     * test that mail filter is created
     *
     * @test
     */
    public function mailFilter()
    {
        $mockRequestValueErrorFactory = $this->getMock('stubRequestValueErrorFactory');
        $filter = stubFilterFactory::forType('mail', array($mockRequestValueErrorFactory));
        $this->assertType('stubFilterFactory', $filter);
        $this->assertSame($mockRequestValueErrorFactory, $filter->getRVEFactory());
        $this->assertType('stubMailFilter', $filter->getDecoratedFilter());
    }

    /**
     * test that string filter is created
     *
     * @test
     */
    public function callMethodOnDeeplyDecoratedFilter()
    {
        $mockRequestValueErrorFactory = $this->getMock('stubRequestValueErrorFactory');
        $filter = stubFilterFactory::forType('password', array($mockRequestValueErrorFactory))->asRequired()->minDiffChars(3)->defaultsTo('foo')->nonAllowedValues(array(1, 2, 3));
        $this->assertType('stubFilterFactory', $filter);
        $this->assertSame($mockRequestValueErrorFactory, $filter->getRVEFactory());
        $defaultValueFilterDecorator = $filter->getDecoratedFilter();
        $this->assertType('stubDefaultValueFilterDecorator', $defaultValueFilterDecorator);
        $this->assertEquals('foo', $defaultValueFilterDecorator->getDefaultValue());
        $requiredFilter = $defaultValueFilterDecorator->getDecoratedFilter();
        $this->assertType('stubRequiredFilterDecorator', $requiredFilter);
        $passwordFilter = $requiredFilter->getDecoratedFilter();
        $this->assertType('stubPasswordFilter', $passwordFilter);
        $this->assertEquals(3, $passwordFilter->getMinDiffChars());
        $this->assertEquals(array(1, 2, 3), $passwordFilter->getNonAllowedValues());
    }

    /**
     * test that string filter is created
     *
     * @test
     * @expectedException  stubMethodNotSupportedException
     */
    public function callNonExistingMethodOnFilter()
    {
        $filter = stubFilterFactory::forType('string')->doesNotExist();
    }

    /**
     * test that user defined filter is created
     *
     * @test
     */
    public function userDefinedFilterProvider()
    {
        $stdClass           = new stdClass();
        $mockFilter         = $this->getMock('stubFilter');
        $mockFilterProvider = $this->getMock('stubFilterProvider');
        stubFilterFactory::addFilterProvider($mockFilterProvider);
        $mockFilterProvider->expects($this->exactly(2))
                           ->method('isResponsible')
                           ->with($this->equalTo('foo'))
                           ->will($this->returnValue(true));
        $mockFilterProvider->expects($this->once())
                           ->method('getFilter')
                           ->with($this->equalTo(array($stdClass)))
                           ->will($this->returnValue($mockFilter));
        $filter = stubFilterFactory::forType('foo', array($stdClass));
        $this->assertType('stubFilterFactory', $filter);
        $this->assertSame($mockFilter, $filter->getDecoratedFilter());
        $mockFilter->expects($this->once())
                   ->method('execute')
                   ->with($this->equalTo('foo'))
                   ->will($this->returnValue('bar'));
        $this->assertEquals('bar', $filter->execute('foo'));
        stubFilterFactory::removeFilterProvider('foo');
    }

    /**
     * test that method chaining is possible with forFilter() is created
     *
     * @test
     */
    public function forFilterMethodChaining()
    {
        $mockFilter         = $this->getMock('stubFilter');
        $filter = stubFilterFactory::forFilter($mockFilter);
        $this->assertType('stubFilterFactory', $filter);
        $this->assertSame($mockFilter, $filter->getDecoratedFilter());
    }

    /**
     * test rve factory behaviour
     *
     * @test
     */
    public function setRveFactory()
    {
        $mockRequestValueErrorFactory = $this->getMock('stubRequestValueErrorFactory');
        $filterFactory = new stubFilterFactory($this->getMock('stubFilter'));
        $filterFactory->using($mockRequestValueErrorFactory);
        $this->assertSame($mockRequestValueErrorFactory, $filterFactory->getRVEFactory());
    }

    /**
     * test rve factory behaviour
     *
     * @test
     */
    public function useUserDefinedRveFactoryClass()
    {
        $mockRequestValueErrorFactory = $this->getMock('stubRequestValueErrorFactory');
        stubRegistry::setConfig('net.stubbles.ipo.request.valueerrorfactory.class', get_class($mockRequestValueErrorFactory));
        $filterFactory = new stubFilterFactory($this->getMock('stubFilter'));
        $this->assertType(get_class($mockRequestValueErrorFactory), $filterFactory->getRVEFactory());
    }

    /**
     * test rve factory behaviour
     *
     * @test
     * @expectedException  stubRuntimeException
     */
    public function useUserDefinedIllegalRveFactoryClass()
    {
        stubRegistry::setConfig('net.stubbles.ipo.request.valueerrorfactory.class', 'stdClass');
        $filterFactory = new stubFilterFactory($this->getMock('stubFilter'));
        $filterFactory->getRVEFactory();
    }

    /**
     * test that date filter is created
     *
     * @test
     */
    public function dateFilter()
    {
        $mockRequestValueErrorFactory = $this->getMock('stubRequestValueErrorFactory');
        $filter = stubFilterFactory::forType('date', array($mockRequestValueErrorFactory));
        $this->assertType('stubFilterFactory', $filter);
        $this->assertType('stubDateFilter', $filter->getDecoratedFilter());
    }

    /**
     * test that period filter is created decorating a date filter
     *
     * @test
     */
    public function periodFilter()
    {
        $minDate = new stubDate('2008-09-01');
        $maxDate = new stubDate('2008-09-30');
        $filter  = stubFilterFactory::forType('date', array($this->getMock('stubRequestValueErrorFactory')))
                                    ->inPeriod($minDate, $maxDate);
        $this->assertType('stubFilterFactory', $filter);
        $periodFilterDecorator = $filter->getDecoratedFilter();
        $this->assertType('stubPeriodFilterDecorator', $periodFilterDecorator);
        $this->assertSame($minDate, $periodFilterDecorator->getMinDate());
        $this->assertEquals('DATE_TOO_EARLY', $periodFilterDecorator->getMinDateErrorId());
        $this->assertSame($maxDate, $periodFilterDecorator->getMaxDate());
        $this->assertEquals('DATE_TOO_LATE', $periodFilterDecorator->getMaxDateErrorId());
        $this->assertEquals('Y-m-d', $periodFilterDecorator->getDateFormat());
        $this->assertType('stubDateFilter', $periodFilterDecorator->getDecoratedFilter());
    }

    /**
     * test that period filter is created decorating a date filter
     *
     * @test
     */
    public function periodFilterDifferentErrorIds()
    {
        $minDate = new stubDate('2008-09-01');
        $maxDate = new stubDate('2008-09-30');
        $filter  = stubFilterFactory::forType('date', array($this->getMock('stubRequestValueErrorFactory')))
                                    ->inPeriod($minDate, $maxDate, 'differentMin', 'differentMax', 'd/m/Y');
        $this->assertType('stubFilterFactory', $filter);
        $periodFilterDecorator = $filter->getDecoratedFilter();
        $this->assertType('stubPeriodFilterDecorator', $periodFilterDecorator);
        $this->assertSame($minDate, $periodFilterDecorator->getMinDate());
        $this->assertEquals('differentMin', $periodFilterDecorator->getMinDateErrorId());
        $this->assertSame($maxDate, $periodFilterDecorator->getMaxDate());
        $this->assertEquals('differentMax', $periodFilterDecorator->getMaxDateErrorId());
        $this->assertEquals('d/m/Y', $periodFilterDecorator->getDateFormat());
        $this->assertType('stubDateFilter', $periodFilterDecorator->getDecoratedFilter());
    }

    /**
     * test that period filter is created decorating a date filter
     *
     * @test
     */
    public function periodFilterWithoutMinDate()
    {
        $maxDate = new stubDate('2008-09-01');
        $filter  = stubFilterFactory::forType('date', array($this->getMock('stubRequestValueErrorFactory')))
                                    ->inPeriod(null, $maxDate);
        $this->assertType('stubFilterFactory', $filter);
        $periodFilterDecorator = $filter->getDecoratedFilter();
        $this->assertType('stubPeriodFilterDecorator', $periodFilterDecorator);
        $this->assertNull($periodFilterDecorator->getMinDate());
        $this->assertSame($maxDate, $periodFilterDecorator->getMaxDate());
        $this->assertType('stubDateFilter', $periodFilterDecorator->getDecoratedFilter());
    }

    /**
     * ttest that period filter is created decorating a date filter
     *
     * @test
     */
    public function periodFilterWithoutMaxDate()
    {
        $minDate = new stubDate('2008-09-01');
        $filter  = stubFilterFactory::forType('date', array($this->getMock('stubRequestValueErrorFactory')))
                                    ->inPeriod($minDate, null);
        $this->assertType('stubFilterFactory', $filter);
        $periodFilterDecorator = $filter->getDecoratedFilter();
        $this->assertType('stubPeriodFilterDecorator', $periodFilterDecorator);
        $this->assertSame($minDate, $periodFilterDecorator->getMinDate());
        $this->assertNull($periodFilterDecorator->getMaxDate());
        $this->assertType('stubDateFilter', $periodFilterDecorator->getDecoratedFilter());
    }

    /**
     * test that date filter is created
     *
     * @test
     */
    public function periodFilterWithoutMinAndMaxDateCreatesReturnsDateFilter()
    {
        $minDate = new stubDate('2008-09-01');
        $maxDate = new stubDate('2008-09-30');
        $filter  = stubFilterFactory::forType('date', array($this->getMock('stubRequestValueErrorFactory')))
                                    ->inPeriod(null, null);
        $this->assertType('stubFilterFactory', $filter);
        $this->assertType('stubDateFilter', $filter->getDecoratedFilter());
    }
}
?>