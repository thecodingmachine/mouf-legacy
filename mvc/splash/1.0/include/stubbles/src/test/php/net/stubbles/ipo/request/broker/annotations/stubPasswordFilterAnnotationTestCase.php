<?php
/**
 * Tests for net::stubbles::ipo::request::broker::annotations::stubPasswordFilterAnnotation.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations_test
 */
stubClassLoader::load('net::stubbles::ipo::request::broker::annotations::stubPasswordFilterAnnotation');
/**
 * Tests for net::stubbles::ipo::request::broker::annotations::stubPasswordFilterAnnotation.
 *
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations_test
 * @group       ipo
 * @group       ipo_request
 * @group       ipo_request_broker
 */
class stubPasswordFilterAnnotationTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubPasswordFilterAnnotation
     */
    protected $passwordFilterAnnotation;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->passwordFilterAnnotation = new stubPasswordFilterAnnotation();
        $this->passwordFilterAnnotation->setRequired(false);
    }

    /**
     * test that the correct filter is created
     *
     * @test
     */
    public function instance()
    {
        $passwordFilter = $this->passwordFilterAnnotation->getFilter();
        $this->assertType('stubLengthFilterDecorator', $passwordFilter);
        $this->assertType('stubPasswordFilter', $passwordFilter->getDecoratedFilter());
        $this->assertType('stubMinLengthValidator', $passwordFilter->getMinLengthValidator());
        $this->assertEquals(6, $passwordFilter->getMinLengthValidator()->getValue());
    }

    /**
     * test that the correct filter is created
     *
     * @test
     */
    public function withValidators()
    {
        $this->passwordFilterAnnotation->setMinLength(8);
        $passwordFilter = $this->passwordFilterAnnotation->getFilter();
        $this->assertType('stubLengthFilterDecorator', $passwordFilter);
        $this->assertType('stubPasswordFilter', $passwordFilter->getDecoratedFilter());
        $this->assertType('stubMinLengthValidator', $passwordFilter->getMinLengthValidator());
        $this->assertEquals(8, $passwordFilter->getMinLengthValidator()->getValue());
    }

    /**
     * assert that the encoder is set correct
     *
     * @test
     */
    public function withEncoder()
    {
        $this->passwordFilterAnnotation->setEncoder(new stubReflectionClass('net::stubbles::php::string::stubMd5Encoder'));
        $passwordFilter = $this->passwordFilterAnnotation->getFilter();
        $this->assertType('stubEncodingFilterDecorator', $passwordFilter);
        $this->assertType('stubLengthFilterDecorator', $passwordFilter->getDecoratedFilter());
        $this->assertType('stubPasswordFilter', $passwordFilter->getDecoratedFilter()->getDecoratedFilter());
        $this->assertType('stubMd5Encoder', $passwordFilter->getEncoder());
    }
}
?>