<?php
/**
 * Test for net::stubbles::xml::serializer::annotations::stubXMLMatcherAnnotation.
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  xml_test
 */
stubClassLoader::load('net::stubbles::xml::serializer::annotations::stubXMLMatcherAnnotation',
                      'net::stubbles::reflection::stubReflectionMethod',
                      'net::stubbles::reflection::stubReflectionProperty'
);

/**
 * Simple class used in this testcase
 */
class stubXMLMatcherAnnotationTestCase_Foo {
    public $bar = 'foo';

    public function getBar() {
        return 'foo';
    }
}

/**
 * Test for net::stubbles::xml::serializer::annotations::stubXMLMatcherAnnotation.
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  xml_test
 * @group       xml
 * @group       xml_serializer
 */
class stubXMLMatcherAnnotationTestCase extends PHPUnit_Framework_TestCase
{

    /**
     * Test a matching pattern for a property
     *
     * @test
     */
    public function matchingPatternForProperty()
    {
        $matcher = new stubXMLMatcherAnnotation();
        $matcher->setPattern('/.+/');

        $tagName = $matcher->getTagnameForProperty(new stubReflectionProperty('stubXMLMatcherAnnotationTestCase_Foo', 'bar'));
        $this->assertEquals('bar', $tagName);
    }

    /**
     * Test a matching pattern for a method
     *
     * @test
     */
    public function matchingPatternForMethod()
    {
        $matcher = new stubXMLMatcherAnnotation();
        $matcher->setPattern('/^get(.+)/');

        $tagName = $matcher->getTagnameForMethod(new stubReflectionMethod('stubXMLMatcherAnnotationTestCase_Foo', 'getBar'));
        $this->assertEquals('bar', $tagName);
    }

    /**
     * Test a non-matching pattern for a property
     *
     * @test
     */
    public function nonMatchingPatternForProperty()
    {
        $matcher = new stubXMLMatcherAnnotation();
        $matcher->setPattern('/^[0-9]+$/');

        $tagName = $matcher->getTagnameForProperty(new stubReflectionProperty('stubXMLMatcherAnnotationTestCase_Foo', 'bar'));
        $this->assertFalse($tagName);
    }

    /**
     * Test a non-matching pattern for a method
     *
     * @test
     */
    public function nonMatchingPatternForMethod()
    {
        $matcher = new stubXMLMatcherAnnotation();
        $matcher->setPattern('/^getFoo/');

        $tagName = $matcher->getTagnameForMethod(new stubReflectionMethod('stubXMLMatcherAnnotationTestCase_Foo', 'getBar'));
        $this->assertFalse($tagName);
    }

    /**
     * Test an invalid pattern with a property
     *
     * @test
     * @expectedException  stubXMLException
     */
    public function invalidPatternForProperty()
    {
        $matcher = new stubXMLMatcherAnnotation();
        $matcher->setPattern('/foo');

        @$matcher->getTagnameForProperty(new stubReflectionProperty('stubXMLMatcherAnnotationTestCase_Foo', 'bar'));
    }

    /**
     * Test an invalid pattern with a method
     *
     * @test
     * @expectedException  stubXMLException
     */
    public function invalidPatternForMethod()
    {
        $matcher = new stubXMLMatcherAnnotation();
        $matcher->setPattern('/foo');

        @$matcher->getTagnameFormethod(new stubReflectionMethod('stubXMLMatcherAnnotationTestCase_Foo', 'getBar'));
    }
}
?>