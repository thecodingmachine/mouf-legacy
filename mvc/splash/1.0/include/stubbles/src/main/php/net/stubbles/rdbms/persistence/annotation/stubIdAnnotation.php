<?php
/**
 * Annotation to mark a primary key of an entity.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_persistence_annotation
 */
stubClassLoader::load('net::stubbles::reflection::annotations::stubAnnotation',
                      'net::stubbles::reflection::annotations::stubAbstractAnnotation'
);
/**
 * Annotation to mark a primary key of an entity.
 *
 * @package     stubbles
 * @subpackage  rdbms_persistence_annotation
 */
class stubIdAnnotation extends stubAbstractAnnotation implements stubAnnotation
{
    /**
     * returns the target of the annotation as bitmap
     *
     * @return  int
     */
    public function getAnnotationTarget()
    {
        return stubAnnotation::TARGET_METHOD + stubAnnotation::TARGET_PROPERTY;
    }
}
?>