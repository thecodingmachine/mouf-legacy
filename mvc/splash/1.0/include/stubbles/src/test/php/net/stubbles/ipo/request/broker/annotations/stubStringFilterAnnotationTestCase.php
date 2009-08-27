<?php
/**
 * Tests for net::stubbles::ipo::request::broker::annotations::stubStringFilterAnnotation.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations_test
 */
stubClassLoader::load('net::stubbles::ipo::request::broker::annotations::stubStringFilterAnnotation');
/**
 * Tests for net::stubbles::ipo::request::broker::annotations::stubStringFilterAnnotation.
 *
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations_test
 * @group       ipo
 * @group       ipo_request
 * @group       ipo_request_broker
 */
class stubStringFilterAnnotationTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubStringFilterAnnotation
     */
    protected $stringFilterAnnotation;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->stringFilterAnnotation = new stubStringFilterAnnotation();
        $this->stringFilterAnnotation->setRequired(false);
    }

    /**
     * test that the correct filter is created
     *
     * @test
     */
    public function instance()
    {
        $filter = $this->stringFilterAnnotation->getFilter();
        $this->assertType('stubStringFilter', $filter);
    }

    /**
     * test that the correct filter is created
     *
     * @test
     */
    public function withValidator()
    {
        $this->stringFilterAnnotation->setRegex('/foo/');
        $filter = $this->stringFilterAnnotation->getFilter();
        $this->assertType('stubValidatorFilterDecorator', $filter);
        $this->assertEquals('FIELD_WRONG_VALUE', $filter->getErrorId());
        $this->assertType('stubRegexValidator', $filter->getValidator());
        $this->assertEquals('/foo/', $filter->getValidator()->getValue());
        $this->assertType('stubStringFilter', $filter->getDecoratedFilter());
    }

    /**
     * test that the correct filter is created
     *
     * @test
     */
    public function withValidatorAndDifferentErrorId()
    {
        $this->stringFilterAnnotation->setRegex('/foo/');
        $this->stringFilterAnnotation->setRegexErrorId('foo');
        $filter = $this->stringFilterAnnotation->getFilter();
        $this->assertType('stubValidatorFilterDecorator', $filter);
        $this->assertEquals('foo', $filter->getErrorId());
        $this->assertType('stubRegexValidator', $filter->getValidator());
        $this->assertEquals('/foo/', $filter->getValidator()->getValue());
        $this->assertType('stubStringFilter', $filter->getDecoratedFilter());
    }
}
?>