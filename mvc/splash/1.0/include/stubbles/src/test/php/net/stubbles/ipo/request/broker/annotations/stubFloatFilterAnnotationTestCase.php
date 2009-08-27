<?php
/**
 * Tests for net::stubbles::ipo::request::broker::annotations::stubFloatFilterAnnotation.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations_test
 */
stubClassLoader::load('net::stubbles::ipo::request::broker::annotations::stubFloatFilterAnnotation');
/**
 * Tests for net::stubbles::ipo::request::broker::annotations::stubFloatFilterAnnotation.
 *
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations_test
 * @group       ipo
 * @group       ipo_request
 * @group       ipo_request_broker
 */
class stubFloatFilterAnnotationTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubFloatFilterAnnotation
     */
    protected $floatFilterAnnotation;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->floatFilterAnnotation = new stubFloatFilterAnnotation();
        $this->floatFilterAnnotation->setRequired(false);
    }

    /**
     * test that the correct filter is created
     *
     * @test
     */
    public function instance()
    {
        $this->assertType('stubFloatFilter', $this->floatFilterAnnotation->getFilter());
    }

    /**
     * test that the correct filter is created
     *
     * @test
     */
    public function withMinNumValidator()
    {
        $this->floatFilterAnnotation->setMinValue(1);
        $filter = $this->floatFilterAnnotation->getFilter();
        $this->assertType('stubRangeFilterDecorator', $filter);
        $this->assertType('stubMinNumberValidator', $filter->getMinValidator());
        $this->assertNull($filter->getMaxValidator());
        $this->assertEquals(1, $filter->getMinValidator()->getValue());
        $this->assertType('stubFloatFilter', $filter->getDecoratedFilter());
    }

    /**
     * test that the correct filter is created
     *
     * @test
     */
    public function withMaxNumValidators()
    {
        $this->floatFilterAnnotation->setMaxValue(2);
        $filter = $this->floatFilterAnnotation->getFilter();
        $this->assertType('stubRangeFilterDecorator', $filter);
        $this->assertNull($filter->getMinValidator());
        $this->assertType('stubMaxNumberValidator', $filter->getMaxValidator());
        $this->assertEquals(2, $filter->getMaxValidator()->getValue());
        $this->assertType('stubFloatFilter', $filter->getDecoratedFilter());
    }

    /**
     * test that the correct filter is created
     *
     * @test
     */
    public function withBothValidators()
    {
        $this->floatFilterAnnotation->setMinValue(1);
        $this->floatFilterAnnotation->setMaxValue(2);
        $filter = $this->floatFilterAnnotation->getFilter();
        $this->assertType('stubRangeFilterDecorator', $filter);
        $this->assertType('stubMinNumberValidator', $filter->getMinValidator());
        $this->assertType('stubMaxNumberValidator', $filter->getMaxValidator());
        $this->assertEquals(1, $filter->getMinValidator()->getValue());
        $this->assertEquals('VALUE_TOO_SMALL', $filter->getMinErrorId());
        $this->assertEquals(2, $filter->getMaxValidator()->getValue());
        $this->assertEquals('VALUE_TOO_GREAT', $filter->getMaxErrorId());
        $this->assertType('stubFloatFilter', $filter->getDecoratedFilter());
    }

    /**
     * test that the correct filter is created
     *
     * @test
     */
    public function withBothValidatorsDifferentErrorIds()
    {
        $this->floatFilterAnnotation->setMinValue(1);
        $this->floatFilterAnnotation->setMinErrorId('differentMin');
        $this->floatFilterAnnotation->setMaxValue(2);
        $this->floatFilterAnnotation->setMaxErrorId('differentMax');
        $filter = $this->floatFilterAnnotation->getFilter();
        $this->assertType('stubRangeFilterDecorator', $filter);
        $this->assertType('stubMinNumberValidator', $filter->getMinValidator());
        $this->assertType('stubMaxNumberValidator', $filter->getMaxValidator());
        $this->assertEquals(1, $filter->getMinValidator()->getValue());
        $this->assertEquals('differentMin', $filter->getMinErrorId());
        $this->assertEquals(2, $filter->getMaxValidator()->getValue());
        $this->assertEquals('differentMax', $filter->getMaxErrorId());
        $this->assertType('stubFloatFilter', $filter->getDecoratedFilter());
    }
}
?>