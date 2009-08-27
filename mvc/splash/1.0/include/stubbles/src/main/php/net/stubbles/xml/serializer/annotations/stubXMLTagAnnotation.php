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
 * Use this annotation to serialize a value as an XML tag.
 *
 * Properties of the annotation are:
 * - tagName
 * - elementTagName
 *
 * @package     stubbles
 * @subpackage  xml_serializer_annotations
 */
class stubXMLTagAnnotation extends stubAbstractAnnotation implements stubAnnotation
{
    /**
     * Name of the XML tag
     *
     * @var  string
     */
    protected $tagName;
    /**
     * Name of the XML tag for elements if this element is indexed
     *
     * @var  string
     */
    protected $elementTagName = null;

    /**
     * Set the tag name
     *
     * @param  string  $tagName
     */
    public function setTagName($tagName)
    {
        $this->tagName = $tagName;
    }

    /**
     * Get the tag name for the tag
     *
     * @return  string
     */
    public function getTagName()
    {
        return $this->tagName;
    }

    /**
     * Set the element tag name
     *
     * @param  string  $elementTagName
     */
    public function setElementTagName($elementTagName)
    {
        $this->elementTagName = $elementTagName;
    }

    /**
     * Get the name for the element tag
     *
     * @return  string
     */
    public function getElementTagName()
    {
        return $this->elementTagName;
    }

    /**
     * Returns the target of the annotation as bitmap.
     *
     * @return  int
     */
    public function getAnnotationTarget()
    {
        return stubAnnotation::TARGET_ALL;
    }
}
?>