<?php
/**
 * Tests for net::stubbles::ipo::request::broker::annotations::stubDateFilterAnnotation.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations_test
 * @version     $Id: stubDateFilterAnnotationTestCase.php 1867 2008-09-27 13:07:07Z mikey $
 */
stubClassLoader::load('net::stubbles::ipo::request::broker::annotations::stubDateFilterAnnotation');
/**
 * Helper class for the test.
 *
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations_test
 */
class DateProvider extends stubBaseObject
{
    /**
     * returns min data in a static way
     *
     * @return  stubDate
     */
    public static function getMinDate()
    {
        return stubDate::now();
    }

    /**
     * returns min data in a dynamic way
     *
     * @return  stubDate
     */
    public function getMinDateNonStatic()
    {
        return stubDate::now();
    }

    /**
     * returns max data in a static way
     *
     * @return  stubDate
     */
    public static function getMaxDate()
    {
        return stubDate::now();
    }

    /**
     * returns max data in a dynamic way
     *
     * @return  stubDate
     */
    public function getMaxDateNonStatic()
    {
        return stubDate::now();
    }
}
/**
 * Tests for net::stubbles::ipo::request::broker::annotations::stubDateFilterAnnotation.
 *
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations_test
 * @group       ipo
 * @group       ipo_request
 * @group       ipo_request_broker
 */
class stubDateFilterAnnotationTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubDateFilterAnnotation
     */
    protected $dateFilterAnnotation;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->dateFilterAnnotation = new stubDateFilterAnnotation();
        $this->dateFilterAnnotation->setRequired(false);
    }

    /**
     * test that the correct filter is created
     *
     * @test
     */
    public function instance()
    {
        $this->assertType('stubDateFilter', $this->dateFilterAnnotation->getFilter());
    }

    /**
     * test that the correct filter is created
     *
     * @test
     */
    public function withMinDateFromDate()
    {
        $minDate = stubDate::now();
        $this->dateFilterAnnotation->setMinDate($minDate);
        $filter = $this->dateFilterAnnotation->getFilter();
        $this->assertType('stubPeriodFilterDecorator', $filter);
        $this->assertNull($filter->getMaxDate());
        $this->assertSame($minDate, $filter->getMinDate());
        $this->assertType('stubDateFilter', $filter->getDecoratedFilter());
    }

    /**
     * test that the correct filter is created
     *
     * @test
     */
    public function withMinDateFromString()
    {
        $this->dateFilterAnnotation->setMinDate('now');
        $filter = $this->dateFilterAnnotation->getFilter();
        $this->assertType('stubPeriodFilterDecorator', $filter);
        $this->assertNull($filter->getMaxDate());
        $this->assertTrue(stubDate::now()->equals($filter->getMinDate()));
        $this->assertType('stubDateFilter', $filter->getDecoratedFilter());
    }

    /**
     * test that the correct filter is created
     *
     * @test
     */
    public function withMinDateFromProviderWithDefaultMethod()
    {
        $this->dateFilterAnnotation->setMinDateProviderClass(new stubReflectionClass('DateProvider'));
        $filter = $this->dateFilterAnnotation->getFilter();
        $this->assertType('stubPeriodFilterDecorator', $filter);
        $this->assertNull($filter->getMaxDate());
        $this->assertTrue(stubDate::now()->equals($filter->getMinDate()));
        $this->assertType('stubDateFilter', $filter->getDecoratedFilter());
    }

    /**
     * test that the correct filter is created
     *
     * @test
     */
    public function withMinDateFromProviderWithOtherMethod()
    {
        $this->dateFilterAnnotation->setMinDateProviderClass(new stubReflectionClass('DateProvider'));
        $this->dateFilterAnnotation->setMinDateProviderMethod('getMinDateNonStatic');
        $filter = $this->dateFilterAnnotation->getFilter();
        $this->assertType('stubPeriodFilterDecorator', $filter);
        $this->assertNull($filter->getMaxDate());
        $this->assertTrue(stubDate::now()->equals($filter->getMinDate()));
        $this->assertType('stubDateFilter', $filter->getDecoratedFilter());
    }

    /**
     * test that the correct filter is created
     *
     * @test
     */
    public function withMaxDateFromDate()
    {
        $maxDate = stubDate::now();
        $this->dateFilterAnnotation->setMaxDate($maxDate);
        $filter = $this->dateFilterAnnotation->getFilter();
        $this->assertType('stubPeriodFilterDecorator', $filter);
        $this->assertNull($filter->getMinDate());
        $this->assertTrue(stubDate::now()->equals($filter->getMaxDate()));
        $this->assertType('stubDateFilter', $filter->getDecoratedFilter());
    }

    /**
     * test that the correct filter is created
     *
     * @test
     */
    public function withMaxFromString()
    {
        $this->dateFilterAnnotation->setMaxDate('now');
        $filter = $this->dateFilterAnnotation->getFilter();
        $this->assertType('stubPeriodFilterDecorator', $filter);
        $this->assertNull($filter->getMinDate());
        $this->assertTrue(stubDate::now()->equals($filter->getMaxDate()));
        $this->assertType('stubDateFilter', $filter->getDecoratedFilter());
    }

    /**
     * test that the correct filter is created
     *
     * @test
     */
    public function withMaxFromProviderWithDefaultMethod()
    {
        $this->dateFilterAnnotation->setMaxDateProviderClass(new stubReflectionClass('DateProvider'));
        $filter = $this->dateFilterAnnotation->getFilter();
        $this->assertType('stubPeriodFilterDecorator', $filter);
        $this->assertNull($filter->getMinDate());
        $this->assertTrue(stubDate::now()->equals($filter->getMaxDate()));
        $this->assertType('stubDateFilter', $filter->getDecoratedFilter());
    }

    /**
     * test that the correct filter is created
     *
     * @test
     */
    public function withMaxFromProviderWithOtherMethod()
    {
        $this->dateFilterAnnotation->setMaxDateProviderClass(new stubReflectionClass('DateProvider'));
        $this->dateFilterAnnotation->setMaxDateProviderMethod('getMaxDateNonStatic');
        $filter = $this->dateFilterAnnotation->getFilter();
        $this->assertType('stubPeriodFilterDecorator', $filter);
        $this->assertNull($filter->getMinDate());
        $this->assertTrue(stubDate::now()->equals($filter->getMaxDate()));
        $this->assertType('stubDateFilter', $filter->getDecoratedFilter());
    }

    /**
     * test that the correct filter is created
     *
     * @test
     */
    public function withMinAndMaxDate()
    {
        $minDate = stubDate::now();
        $maxDate = stubDate::now();
        $this->dateFilterAnnotation->setMinDate($minDate);
        $this->dateFilterAnnotation->setMaxDate($maxDate);
        $filter = $this->dateFilterAnnotation->getFilter();
        $this->assertType('stubPeriodFilterDecorator', $filter);
        $this->assertTrue(stubDate::now()->equals($filter->getMinDate()));
        $this->assertEquals('DATE_TOO_EARLY', $filter->getMinDateErrorId());
        $this->assertTrue(stubDate::now()->equals($filter->getMaxDate()));
        $this->assertEquals('DATE_TOO_LATE', $filter->getMaxDateErrorId());
        $this->assertType('stubDateFilter', $filter->getDecoratedFilter());
    }

    /**
     * test that the correct filter is created
     *
     * @test
     */
    public function withMinAndMaxDateDifferentErrorIds()
    {
        $minDate = stubDate::now();
        $maxDate = stubDate::now();
        $this->dateFilterAnnotation->setMinDate($minDate);
        $this->dateFilterAnnotation->setMinDateErrorId('differentMin');
        $this->dateFilterAnnotation->setMaxDate($maxDate);
        $this->dateFilterAnnotation->setMaxDateErrorId('differentMax');
        $this->dateFilterAnnotation->setDateFormat('d/m/y');
        $filter = $this->dateFilterAnnotation->getFilter();
        $this->assertType('stubPeriodFilterDecorator', $filter);
        $this->assertTrue(stubDate::now()->equals($filter->getMinDate()));
        $this->assertEquals('differentMin', $filter->getMinDateErrorId());
        $this->assertTrue(stubDate::now()->equals($filter->getMaxDate()));
        $this->assertEquals('differentMax', $filter->getMaxDateErrorId());
        $this->assertEquals('d/m/y', $filter->getDateFormat());
        $this->assertType('stubDateFilter', $filter->getDecoratedFilter());
    }
}
?>