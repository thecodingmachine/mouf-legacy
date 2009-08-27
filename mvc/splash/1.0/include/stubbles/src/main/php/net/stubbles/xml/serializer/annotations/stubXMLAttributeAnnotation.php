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
 * Use this annotation to serialize a value as an XML attribute.
 *
 * Properties of the annotation are:
 * - attributeName
 *
 * @package     stubbles
 * @subpackage  xml_serializer_annotations
 */
class stubXMLAttributeAnnotation extends stubAbstractAnnotation implements stubAnnotation
{
    /**
     * Name of the XML attribute
     *
     * @var  string
     */
    protected $attributeName;
    /**
     * Whether an empty value should be skipped
     *
     * @var  boolean
     */
    protected $skipEmpty = true;
    /**
     * Set the attribute name
     *
     * @param  string  $attributeName
     */
    public function setAttributeName($attributeName)
    {
        $this->attributeName = $attributeName;
    }

    /**
     * Set the skipEmpty behaviour
     *
     * @param  boolean  $skipEmpty
     */
    public function setSkipEmpty($skipEmpty)
    {
        $this->skipEmpty = $skipEmpty;
    }

    /**
     * Get the name for the attribute
     *
     * @return  string
     */
    public function getAttributeName()
    {
        return $this->attributeName;
    }

    /**
     * Check, whether empty values should be skipped
     *
     * @return  boolean
     */
    public function shouldSkipEmpty()
    {
        return $this->skipEmpty;
    }

    /**
     * Returns the target of the annotation as bitmap.
     *
     * @return  int
     */
    public function getAnnotationTarget()
    {
        return stubAnnotation::TARGET_FUNCTION + stubAnnotation::TARGET_METHOD + stubAnnotation::TARGET_PROPERTY;
    }
}
?>