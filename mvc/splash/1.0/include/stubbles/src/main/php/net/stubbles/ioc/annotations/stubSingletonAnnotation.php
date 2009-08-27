<?php
/**
 * Annotation to mark a class as a singleton.
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  ioc_annotations
 */
stubClassLoader::load('net::stubbles::reflection::annotations::stubAbstractAnnotation');
/**
 * Annotation to mark a class as a singleton.
 *
 * @package     stubbles
 * @subpackage  ioc_annotations
 */
class stubSingletonAnnotation extends stubAbstractAnnotation
{
    /**
     * Returns the target of the annotation as bitmap.
     *
     * @return  int
     */
    public function getAnnotationTarget()
    {
        return stubAnnotation::TARGET_CLASS;
    }
}
?>