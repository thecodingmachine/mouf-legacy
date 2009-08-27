<?php
/**
 * Test for net::stubbles::ioc::annotations::stubInjectAnnotation.
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  ioc_annotations_test
 */
stubClassLoader::load('net::stubbles::ioc::annotations::stubInjectAnnotation');

/**
 * Test for net::stubbles::ioc::annotations::stubInjectAnnotation.
 *
 * @package     stubbles
 * @subpackage  ioc_annotations_test
 * @group       ioc
 * @group       ioc_annotations
 */
class stubInjectAnnotationTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * test that the injection names are handles properly
     *
     * @test
     */
    public function optional()
    {
        $inject = new stubInjectAnnotation();
        $this->assertEquals(stubAnnotation::TARGET_METHOD, $inject->getAnnotationTarget());
        $this->assertFalse($inject->isOptional());
        $inject->setOptional(true);
        $this->assertTrue($inject->isOptional());
    }
}
?>