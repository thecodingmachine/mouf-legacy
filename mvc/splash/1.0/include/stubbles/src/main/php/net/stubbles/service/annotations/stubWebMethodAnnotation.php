<?php
/**
 * Annotation to mark a method as a service method
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  service_annotations
 */
stubClassLoader::load('net::stubbles::reflection::annotations::stubAnnotation',
                      'net::stubbles::reflection::annotations::stubAbstractAnnotation'
);
/**
 * Annotation to mark a method as a service method
 *
 * Use this annotation, if you do not want a property to be serialized.
 *
 * @package     stubbles
 * @subpackage  service_annotations
 */
class stubWebMethodAnnotation extends stubAbstractAnnotation implements stubAnnotation
{
    /**
     * Returns the target of the annotation as bitmap.
     *
     * @return  int
     */
    public function getAnnotationTarget()
    {
        return stubAnnotation::TARGET_METHOD;
    }
}
?>