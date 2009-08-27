<?php
/**
 * Tests for net::stubbles::ipo::request::broker::annotations::stubIntegerFilterAnnotation.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations_test
 */
stubClassLoader::load('net::stubbles::ipo::request::broker::annotations::stubIntegerFilterAnnotation');
/**
 * Tests for net::stubbles::ipo::request::broker::annotations::stubIntegerFilterAnnotation.
 *
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations_test
 * @group       ipo
 * @group       ipo_request
 * @group       ipo_request_broker
 */
class stubIntegerFilterAnnotationTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubIntegerFilterAnnotation
     */
    protected $integerFilterAnnotation;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->integerFilterAnnotation = new stubIntegerFilterAnnotation();
        $this->integerFilterAnnotation->setRequired(false);
    }

    /**
     * test that the correct filter is created
     *
     * @test
     */
    public function instance()
    {
        $this->assertType('stubIntegerFilter', $this->integerFilterAnnotation->getFilter());
    }

    /**
     * test that the correct filter is created
     *
     * @test
     */
    public function withMinNumValidator()
    {
        $this->integerFilterAnnotation->setMinValue(1);
        $filter = $this->integerFilterAnnotation->getFilter();
        $this->assertType('stubRangeFilterDecorator', $filter);
        $this->assertType('stubMinNumberValidator', $filter->getMinValidator());
        $this->assertNull($filter->getMaxValidator());
        $this->assertEquals(1, $filter->getMinValidator()->getValue());
        $this->assertType('stubIntegerFilter', $filter->getDecoratedFilter());
    }

    /**
     * test that the correct filter is created
     *
     * @test
     */
    public function withMaxNumValidators()
    {
        $this->integerFilterAnnotation->setMaxValue(2);
        $filter = $this->integerFilterAnnotation->getFilter();
        $this->assertType('stubRangeFilterDecorator', $filter);
        $this->assertNull($filter->getMinValidator());
        $this->assertType('stubMaxNumberValidator', $filter->getMaxValidator());
        $this->assertEquals(2, $filter->getMaxValidator()->getValue());
        $this->assertType('stubIntegerFilter', $filter->getDecoratedFilter());
    }

    /**
     * test that the correct filter is created
     *
     * @test
     */
    public function withBothValidators()
    {
        $this->integerFilterAnnotation->setMinValue(1);
        $this->integerFilterAnnotation->setMaxValue(2);
        $filter = $this->integerFilterAnnotation->getFilter();
        $this->assertType('stubRangeFilterDecorator', $filter);
        $this->assertType('stubMinNumberValidator', $filter->getMinValidator());
        $this->assertType('stubMaxNumberValidator', $filter->getMaxValidator());
        $this->assertEquals(1, $filter->getMinValidator()->getValue());
        $this->assertEquals('VALUE_TOO_SMALL', $filter->getMinErrorId());
        $this->assertEquals(2, $filter->getMaxValidator()->getValue());
        $this->assertEquals('VALUE_TOO_GREAT', $filter->getMaxErrorId());
        $this->assertType('stubIntegerFilter', $filter->getDecoratedFilter());
    }

    /**
     * test that the correct filter is created
     *
     * @test
     */
    public function withBothValidatorsDifferentErrorIds()
    {
        $this->integerFilterAnnotation->setMinValue(1);
        $this->integerFilterAnnotation->setMinErrorId('differentMin');
        $this->integerFilterAnnotation->setMaxValue(2);
        $this->integerFilterAnnotation->setMaxErrorId('differentMax');
        $filter = $this->integerFilterAnnotation->getFilter();
        $this->assertType('stubRangeFilterDecorator', $filter);
        $this->assertType('stubMinNumberValidator', $filter->getMinValidator());
        $this->assertType('stubMaxNumberValidator', $filter->getMaxValidator());
        $this->assertEquals(1, $filter->getMinValidator()->getValue());
        $this->assertEquals('differentMin', $filter->getMinErrorId());
        $this->assertEquals(2, $filter->getMaxValidator()->getValue());
        $this->assertEquals('differentMax', $filter->getMaxErrorId());
        $this->assertType('stubIntegerFilter', $filter->getDecoratedFilter());
    }
}
?>