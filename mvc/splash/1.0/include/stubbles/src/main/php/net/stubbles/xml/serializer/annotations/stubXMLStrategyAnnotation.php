<?php
/**
 * Annotation for XMLSerializer
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  xml_serializer_annotations
 */
stubClassLoader::load('net::stubbles::reflection::annotations::stubAnnotation',
                      'net::stubbles::reflection::annotations::stubAbstractAnnotation'
);
/**
 * Annotation for XMLSerializer
 *
 * Use this annotation, if you do not want a property to be serialized.
 *
 * @package     stubbles
 * @subpackage  xml_serializer_annotations
 */
class stubXMLStrategyAnnotation extends stubAbstractAnnotation implements stubAnnotation
{
    /**
     * The strategy to use for this class
     *
     * @var  int
     */
    protected $value;

    /**
     * Set the value of the annotation
     *
     * @param  int  $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Get the value of the annotation
     *
     * @return  int
     */
    public function getValue()
    {
        return $this->value;
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
}
?>