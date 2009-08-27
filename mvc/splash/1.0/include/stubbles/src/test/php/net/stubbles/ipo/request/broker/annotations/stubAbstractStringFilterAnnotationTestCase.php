<?php
/**
 * Tests for net::stubbles::ipo::request::broker::annotations::stubAbstractStringFilterAnnotation.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations_test
 */
stubClassLoader::load('net::stubbles::ipo::request::broker::annotations::stubAbstractStringFilterAnnotation');
/**
 * Tests for net::stubbles::ipo::request::broker::annotations::stubAbstractStringFilterAnnotation.
 *
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations_test
 * @group       ipo
 * @group       ipo_request
 * @group       ipo_request_broker
 */
class stubAbstractStringFilterAnnotationTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubAbstractStringFilterAnnotation
     */
    protected $abstractStringFilterAnnotation;
    /**
     * mocked filter instance
     *
     * @var unknown_type
     */
    protected $mockFilter;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->abstractStringFilterAnnotation = $this->getMock('stubAbstractStringFilterAnnotation',
                                                               array('doDoGetFilter')
                                                );
        $this->abstractStringFilterAnnotation->setRequired(false);
        $this->mockFilter = $this->getMock('stubFilter');
        $this->abstractStringFilterAnnotation->expects($this->any())
                                             ->method('doDoGetFilter')
                                             ->will($this->returnValue($this->mockFilter));
    }

    /**
     * test that the correct filter is created
     *
     * @test
     */
    public function instance()
    {
        $this->assertSame($this->mockFilter, $this->abstractStringFilterAnnotation->getFilter());
        $this->abstractStringFilterAnnotation->finish();
    }

    /**
     * test that the correct filter is created
     *
     * @test
     */
    public function withValidators()
    {
        $this->abstractStringFilterAnnotation->setMinLength(1);
        $this->abstractStringFilterAnnotation->setMaxLength(2);
        $filter = $this->abstractStringFilterAnnotation->getFilter();
        $this->assertType('stubLengthFilterDecorator', $filter);
        $this->assertType('stubMinLengthValidator', $filter->getMinLengthValidator());
        $this->assertType('stubMaxLengthValidator', $filter->getMaxLengthValidator());
        $this->assertEquals(1, $filter->getMinLengthValidator()->getValue());
        $this->assertEquals('STRING_TOO_SHORT', $filter->getMinLengthErrorId());
        $this->assertEquals(2, $filter->getMaxLengthValidator()->getValue());
        $this->assertEquals('STRING_TOO_LONG', $filter->getMaxLengthErrorId());
        $this->assertSame($this->mockFilter, $filter->getDecoratedFilter());
        $this->abstractStringFilterAnnotation->finish();
    }

    /**
     * test that the correct filter is created
     *
     * @test
     */
    public function withValidatorsAndDifferentErrorIds()
    {
        $this->abstractStringFilterAnnotation->setMinLength(1);
        $this->abstractStringFilterAnnotation->setMinLengthErrorId('differentMin');
        $this->abstractStringFilterAnnotation->setMaxLength(2);
        $this->abstractStringFilterAnnotation->setMaxLengthErrorId('differentMax');
        $filter = $this->abstractStringFilterAnnotation->getFilter();
        $this->assertType('stubLengthFilterDecorator', $filter);
        $this->assertType('stubMinLengthValidator', $filter->getMinLengthValidator());
        $this->assertType('stubMaxLengthValidator', $filter->getMaxLengthValidator());
        $this->assertEquals(1, $filter->getMinLengthValidator()->getValue());
        $this->assertEquals('differentMin', $filter->getMinLengthErrorId());
        $this->assertEquals(2, $filter->getMaxLengthValidator()->getValue());
        $this->assertEquals('differentMax', $filter->getMaxLengthErrorId());
        $this->assertSame($this->mockFilter, $filter->getDecoratedFilter());
        $this->abstractStringFilterAnnotation->finish();
    }

    /**
     * assert that the encoder is set correct
     *
     * @test
     */
    public function withEncoder()
    {
        $this->abstractStringFilterAnnotation->setEncoder(new stubReflectionClass('net::stubbles::php::string::stubMd5Encoder'));
        $filter = $this->abstractStringFilterAnnotation->getFilter();
        $this->assertType('stubEncodingFilterDecorator', $filter);
        $this->assertType('stubMd5Encoder', $filter->getEncoder());
        $this->assertEquals(stubStringEncoder::MODE_DECODE, $filter->getEncoderMode());
        $this->abstractStringFilterAnnotation->setEncoderMode(stubStringEncoder::MODE_ENCODE);
        $filter = $this->abstractStringFilterAnnotation->getFilter();
        $this->assertType('stubEncodingFilterDecorator', $filter);
        $this->assertType('stubMd5Encoder', $filter->getEncoder());
        $this->assertEquals(stubStringEncoder::MODE_ENCODE, $filter->getEncoderMode());
        $this->assertSame($this->mockFilter, $filter->getDecoratedFilter());
        $this->abstractStringFilterAnnotation->finish();
    }

    /**
     * test that finish() works as expected
     *
     * @test
     * @expectedException  ReflectionException
     */
    public function finishWithWrongEncoderMode()
    {
        $this->abstractStringFilterAnnotation->setEncoder(new stubReflectionClass('net::stubbles::php::string::stubMd5Encoder'));
        $this->abstractStringFilterAnnotation->setEncoderMode('invalid');
        $this->abstractStringFilterAnnotation->finish();
    }
}
?>