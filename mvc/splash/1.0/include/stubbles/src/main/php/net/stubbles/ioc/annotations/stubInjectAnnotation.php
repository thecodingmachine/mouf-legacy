<?php
/**
 * Annotation to mark a method that is used for dependency injection
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  ioc_annotations
 */
stubClassLoader::load('net::stubbles::reflection::annotations::stubAbstractAnnotation');
/**
 * Annotation to mark a method that is used for dependency injection
 *
 * @package     stubbles
 * @subpackage  ioc_annotations
 */
class stubInjectAnnotation extends stubAbstractAnnotation
{
    /**
     * whether the injection is optional
     *
     * @var  boolean
     */
    protected $optional = false;

    /**
     * sets, whether the injection is optional
     *
     * @param  boolean  $optional
     */
    public function setOptional($optional)
    {
        $this->optional = $optional;
    }

    /**
     * Returns the target of the annotation as bitmap.
     *
     * @return  int
     */
    public function getAnnotationTarget()
    {
        return stubAnnotation::TARGET_METHOD;
    }

    /**
     * Checks, whether the injection is optional
     *
     * @return  boolean
     */
    public function isOptional()
    {
        return $this->optional;
    }
}
?>