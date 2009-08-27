<?php
/**
 * Tests for net::stubbles::ipo::request::broker::annotations::stubPreselectFilterAnnotation.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations_test
 */
stubClassLoader::load('net::stubbles::ipo::request::broker::annotations::stubPreselectFilterAnnotation');
/**
 * Helper class for the test.
 *
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations_test
 * @group       ipo
 * @group       ipo_request
 * @group       ipo_request_broker
 */
class stubPreselectFilterAnnotationFilterAnnotationDataSource extends stubBaseObject
{
    /**
     * returns data
     *
     * @return  array<string>
     */
    public static function getData()
    {
        return array('foo', 'bar');
    }

    /**
     * returns other data
     *
     * @return  array<string>
     */
    public static function getBaz()
    {
        return array('baz');
    }
}
/**
 * Tests for net::stubbles::ipo::request::broker::annotations::stubPreselectFilterAnnotation.
 *
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations_test
 */
class stubPreselectFilterAnnotationTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubPreselectFilterAnnotation
     */
    protected $preselectFilterAnnotation;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->preselectFilterAnnotation = new stubPreselectFilterAnnotation();
        $this->preselectFilterAnnotation->setRequired(false);
        $this->preselectFilterAnnotation->setSourceDataClass(new stubReflectionClass('stubPreselectFilterAnnotationFilterAnnotationDataSource'));
    }

    /**
     * default method getData() should be used
     *
     * @test
     */
    public function usingDefaultMethod()
    {
        $validatorFilterDecorator = $this->preselectFilterAnnotation->getFilter();
        $this->assertType('stubValidatorFilterDecorator', $validatorFilterDecorator);
        $this->assertEquals('FIELD_WRONG_VALUE', $validatorFilterDecorator->getErrorId());
        $validator = $validatorFilterDecorator->getValidator();
        $this->assertType('stubPreSelectValidator', $validator);
        $this->assertEquals(array('foo', 'bar'), $validator->getAllowedValues());
    }

    /**
     * other method getBaz() should be used
     *
     * @test
     */
    public function usingOtherMethod()
    {
        $this->preselectFilterAnnotation->setSourceDataMethod('getBaz');
        $this->preselectFilterAnnotation->setErrorId('OTHER_ID');
        $validatorFilterDecorator = $this->preselectFilterAnnotation->getFilter();
        $this->assertType('stubValidatorFilterDecorator', $validatorFilterDecorator);
        $this->assertEquals('OTHER_ID', $validatorFilterDecorator->getErrorId());
        $validator = $validatorFilterDecorator->getValidator();
        $this->assertType('stubPreSelectValidator', $validator);
        $this->assertEquals(array('baz'), $validator->getAllowedValues());
    }
}
?>