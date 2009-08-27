<?php
/**
 * Tests for net::stubbles::ipo::request::broker::annotations::stubAbstractFilterAnnotation.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations_test
 */
stubClassLoader::load('net::stubbles::ipo::request::broker::annotations::stubAbstractFilterAnnotation',
                      'net::stubbles::ipo::request::filter::stubFilter',
                      'net::stubbles::ipo::request::stubRequestValueErrorFactory'
);
/**
 * Helper class to circumvent the filter generation for rve tests.
 *
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations_test
 */
class TeststubAbstractFilterAnnotation extends stubAbstractFilterAnnotation
{
    /**
     * circumvent filter creation
     */
    protected function doGetFilter() { }
    
    /**
     * access to protected factory method
     *
     * @return  stubRequestValueErrorFactory
     */
    public function getRVEFactory()
    {
        return $this->createRVEFactory();
    }
}
/**
 * Tests for net::stubbles::ipo::request::broker::annotations::stubAbstractFilterAnnotation.
 *
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations_test
 * @group       ipo
 * @group       ipo_request
 * @group       ipo_request_broker
 */
class stubAbstractFilterAnnotationTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubAbstractFilterAnnotation
     */
    protected $abstractFilterAnnotation;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->abstractFilterAnnotation = $this->getMock('stubAbstractFilterAnnotation', array('doGetFilter'));
    }

    /**
     * test that field name is handled correct
     *
     * @test
     */
    public function fieldName()
    {
        $this->assertNull($this->abstractFilterAnnotation->getFieldName());
        $this->abstractFilterAnnotation->setFieldName('foo');
        $this->assertEquals('foo', $this->abstractFilterAnnotation->getFieldName());
    }

    /**
     * test that values are set as expected
     *
     * @test
     */
    public function createFilterWithDefaultValues()
    {
        $mockFilter = $this->getMock('stubFilter');
        $this->abstractFilterAnnotation->expects($this->once())->method('doGetFilter')->will($this->returnValue($mockFilter));
        $filter = $this->abstractFilterAnnotation->getFilter();
        $this->assertType('stubRequiredFilterDecorator', $filter);
        $this->assertSame($mockFilter, $filter->getDecoratedFilter());
    }

    /**
     * test that values are set as expected
     *
     * @test
     */
    public function createFilterWithoutRequired()
    {
        $mockFilter = $this->getMock('stubFilter');
        $this->abstractFilterAnnotation->expects($this->once())->method('doGetFilter')->will($this->returnValue($mockFilter));
        $this->abstractFilterAnnotation->setRequired(false);
        $this->assertSame($mockFilter, $this->abstractFilterAnnotation->getFilter());
    }

    /**
     * test that values are set as expected
     *
     * @test
     */
    public function createFilterWithChangedValues()
    {
        $mockFilter = $this->getMock('stubFilter');
        $this->abstractFilterAnnotation->expects($this->once())->method('doGetFilter')->will($this->returnValue($mockFilter));
        $this->abstractFilterAnnotation->setDefaultValue('foo');
        $filter = $this->abstractFilterAnnotation->getFilter();
        $this->assertType('stubDefaultValueFilterDecorator', $filter);
        $this->assertEquals('foo', $filter->getDefaultValue());
        $filter = $filter->getDecoratedFilter();
        $this->assertType('stubRequiredFilterDecorator', $filter);
        $this->assertSame($mockFilter, $filter->getDecoratedFilter());
    }

    /**
     * test that values are set as expected
     *
     * @test
     */
    public function createFilterWithChangedValuesWithoutRequired()
    {
        $mockFilter = $this->getMock('stubFilter');
        $this->abstractFilterAnnotation->expects($this->once())->method('doGetFilter')->will($this->returnValue($mockFilter));
        $this->abstractFilterAnnotation->setRequired(false);
        $this->abstractFilterAnnotation->setDefaultValue('foo');
        $filter = $this->abstractFilterAnnotation->getFilter();
        $this->assertType('stubDefaultValueFilterDecorator', $filter);
        $this->assertEquals('foo', $filter->getDefaultValue());
        $this->assertSame($mockFilter, $filter->getDecoratedFilter());
    }

    /**
     * test that the correct RequestErrorValueFactory is created
     *
     * @test
     */
    public function defaultRequestErrorValueFactory()
    {
        $abstractFilterAnnotation = new TeststubAbstractFilterAnnotation();
        $rveFactory = $abstractFilterAnnotation->getRVEFactory();
        $this->assertType('stubRequestValueErrorXJConfFactory', $rveFactory);
        $this->assertSame($rveFactory, $abstractFilterAnnotation->getRVEFactory());
    }

    /**
     * test that the correct RequestErrorValueFactory is created
     *
     * @test
     */
    public function otherRequestErrorValueFactory()
    {
        $abstractFilterAnnotation = new TeststubAbstractFilterAnnotation();
        $class = get_class($this->getMock('stubRequestValueErrorFactory'));
        $refClass = new stubReflectionClass($class);
        $abstractFilterAnnotation->setRVEFactoryClass($refClass);
        $rveFactory = $abstractFilterAnnotation->getRVEFactory();
        $this->assertType($class, $rveFactory);
        $this->assertSame($rveFactory, $abstractFilterAnnotation->getRVEFactory());
    }

    /**
     * test that the correct RequestErrorValueFactory is created
     *
     * @test
     * @expectedException  stubRequestBrokerException
     */
    public function wrongRequestErrorValueFactory()
    {
        $abstractFilterAnnotation = new TeststubAbstractFilterAnnotation();
        $refClass = new stubReflectionClass('stdClass');
        $abstractFilterAnnotation->setRVEFactoryClass($refClass);
        $abstractFilterAnnotation->getRVEFactory();
    }
}
?>