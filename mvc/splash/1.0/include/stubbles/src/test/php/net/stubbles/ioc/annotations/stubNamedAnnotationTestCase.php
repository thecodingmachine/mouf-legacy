<?php
/**
 * Test for net::stubbles::ioc::annotations::stubNamedAnnotation.
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  ioc_annotations_test
 */
stubClassLoader::load('net::stubbles::ioc::annotations::stubNamedAnnotation');

/**
 * Test for net::stubbles::ioc::annotations::stubNamedAnnotation.
 *
 * @package     stubbles
 * @subpackage  ioc_annotations_test
 * @group       ioc
 * @group       ioc_annotations
 */
class stubNamedAnnotationTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * test that the properties are handles properly
     *
     * @test
     */
    public function nameValue()
    {
        $named = new stubNamedAnnotation();
        $this->assertEquals(stubAnnotation::TARGET_METHOD, $named->getAnnotationTarget());
        $named->setValue('Foo');
        $this->assertEquals('Foo', $named->getName());
    }
}
?>