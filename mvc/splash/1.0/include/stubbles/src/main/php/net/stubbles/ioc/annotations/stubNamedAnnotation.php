<?php
/**
 * Annotation to name an injection
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  ioc_annotations
 */
stubClassLoader::load('net::stubbles::reflection::annotations::stubAbstractAnnotation');
/**
 * Annotation to name an injection
 *
 * @package     stubbles
 * @subpackage  ioc_annotations
 */
class stubNamedAnnotation extends stubAbstractAnnotation
{
    /**
     * Name
     *
     * @var  string
     */
    protected $name;

    /**
     * sets the list of class names to inject
     *
     * @param  string  $value
     */
    public function setValue($value)
    {
        $this->name = $value;
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
     * Get the name
     *
     * @return  string
     */
    public function getName()
    {
        return $this->name;
    }
}
?>