<?php
/**
 * Interface for an annotation.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  reflection_annotations
 */
stubClassLoader::load('net::stubbles::lang::stubClonable');
/**
 * Interface for an annotation.
 *
 * @package     stubbles
 * @subpackage  reflection_annotations
 */
interface stubAnnotation extends stubSerializable, stubClonable
{
    /**
     * annotation is applicable for classes
     */
    const TARGET_CLASS    = 1;
    /**
     * annotation is applicable for properties
     */
    const TARGET_PROPERTY = 2;
    /**
     * annotation is applicable for methods
     */
    const TARGET_METHOD   = 4;
    /**
     * annotation is applicable for functions
     */
    const TARGET_FUNCTION = 8;
    /**
     * annotation is applicable for parameters
     */
    const TARGET_PARAM    = 16;
    /**
     * annotation is applicable for classes, properties, methods and functions
     */
    const TARGET_ALL      = 31;

    /**
     * Sets the name under which the annotation is stored.
     *
     * @param  string  $name
     */
    public function setAnnotationName($name);

    /**
     * Returns the name under which the annotation is stored.
     *
     * @return  string
     */
    public function getAnnotationName();

    /**
     * Returns the target of the annotation as bitmap.
     *
     * @return  int
     */
    public function getAnnotationTarget();

    /**
     * do some last operations after all values have been set
     *
     * This method may check if all required values have been set and throw
     * an exception if values are missing.
     *
     * @throws  ReflectionException
     */
    public function finish();
}
?>