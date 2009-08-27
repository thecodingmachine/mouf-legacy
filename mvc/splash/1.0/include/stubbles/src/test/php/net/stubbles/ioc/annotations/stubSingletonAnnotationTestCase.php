<?php
/**
 * Test for net::stubbles::ioc::annotations::stubSingletonAnnotation.
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  ioc_annotations_test
 */
stubClassLoader::load('net::stubbles::ioc::annotations::stubSingletonAnnotation');

/**
 * Test for net::stubbles::ioc::annotations::stubNamedAnnotation.
 *
 * @package     stubbles
 * @subpackage  ioc_annotations_test
 * @group       ioc
 * @group       ioc_annotations
 */
class stubSingletonAnnotationTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * test that the properties are handles properly
     *
     * @test
     */
    public function target()
    {
        $singleton = new stubSingletonAnnotation();
        $this->assertEquals(stubAnnotation::TARGET_CLASS, $singleton->getAnnotationTarget());
    }
}
?>