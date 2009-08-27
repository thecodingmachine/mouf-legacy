<?php
/**
 * Annotation to mark the default implementation of an interface.
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  ioc_annotations
 */
stubClassLoader::load('net::stubbles::reflection::annotations::stubAbstractAnnotation',
                      'net::stubbles::reflection::stubReflectionClass'
);
/**
 * Annotation to mark the default implementation of an interface.
 *
 * @package     stubbles
 * @subpackage  ioc_annotations
 */
class stubImplementedByAnnotation extends stubAbstractAnnotation
{
    /**
     * default implementation
     *
     * @var  stubReflectionClass
     */
    protected $defaultImplementation;

    /**
     * sets the list of class names to inject
     *
     * @param  stubReflectionClass  $value
     */
    public function setValue(stubReflectionClass $value)
    {
        $this->defaultImplementation = $value;
    }

    /**
     * Returns the target of the annotation as bitmap.
     *
     * @return  int
     */
    public function getAnnotationTarget()
    {
        return stubAnnotation::TARGET_CLASS;
    }

    /**
     * Get the default implementation
     *
     * @return  stubReflectionClass
     */
    public function getDefaultImplementation()
    {
        return $this->defaultImplementation;
    }
}
?>