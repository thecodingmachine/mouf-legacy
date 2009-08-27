<?php
/**
 * Interface for reflected structures that may have annotations.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  reflection_annotations
 */
/**
 * Interface for reflected structures that may have annotations.
 *
 * @package     stubbles
 * @subpackage  reflection_annotations
 */
interface stubAnnotatable
{
    /**
     * check whether the class has the given annotation or not
     *
     * @param   string  $annotationName
     * @return  bool
     */
    public function hasAnnotation($annotationName);

    /**
     * return the specified annotation
     *
     * @param   string          $annotationName
     * @return  stubAnnotation
     * @throws  ReflectionException
     */
    public function getAnnotation($annotationName);
}
?>
