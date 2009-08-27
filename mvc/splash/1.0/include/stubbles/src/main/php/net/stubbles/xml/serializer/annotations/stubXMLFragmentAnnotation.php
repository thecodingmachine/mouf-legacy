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
 * Use this annotation to serialize a value as an XML fragment.
 *
 * Properties of the annotation are:
 * - tagName
 *
 * @package     stubbles
 * @subpackage  xml_serializer_annotations
 */
class stubXMLFragmentAnnotation extends stubAbstractAnnotation implements stubAnnotation
{
    /**
     * Name of the XML tag
     *
     * @var  string
     */
    protected $tagName;

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
     * Returns the target of the annotation as bitmap.
     *
     * @return  int
     */
    public function getAnnotationTarget()
    {
        return stubAnnotation::TARGET_PROPERTY + stubAnnotation::TARGET_METHOD;
    }
}
?>