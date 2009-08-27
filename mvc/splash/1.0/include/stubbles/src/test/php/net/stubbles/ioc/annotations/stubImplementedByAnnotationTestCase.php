<?php
/**
 * Test for net::stubbles::ioc::annotations::stubImplementedByAnnotation.
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  ioc_annotations_test
 */
stubClassLoader::load('net::stubbles::ioc::annotations::stubImplementedByAnnotation',
                      'net::stubbles::reflection::stubReflectionClass'
);

class stubTestImplementedByAnnotationTestClass {
}

/**
 * Test for net::stubbles::ioc::annotations::stubImplementedByAnnotation.
 *
 * @package     stubbles
 * @subpackage  ioc_annotations_test
 * @group       ioc
 * @group       ioc_annotations
 */
class stubImplementedByAnnotationTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * test that the properties are handles properly
     *
     * @test
     */
    public function value()
    {
        $implemented = new stubImplementedByAnnotation();
        $implemented->setValue(new stubReflectionClass('stubTestImplementedByAnnotationTestClass'));

        $this->assertEquals(stubAnnotation::TARGET_CLASS, $implemented->getAnnotationTarget());
        $this->assertEquals(new stubReflectionClass('stubTestImplementedByAnnotationTestClass'), $implemented->getDefaultImplementation());
    }
}
?>