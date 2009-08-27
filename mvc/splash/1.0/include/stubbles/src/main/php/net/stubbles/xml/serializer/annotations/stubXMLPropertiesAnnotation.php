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
 * Use this annotation to define, which properties of a class should be serialized
 *
 * @package     stubbles
 * @subpackage  xml_serializer_annotations
 */
interface stubXMLPropertiesAnnotation
{
    /**
     * Get the name of the tag to use for a property
     *
     * @param   stubReflectionProperty  $property
     * @return  string|false
     */
    public function getTagnameForProperty(stubReflectionProperty $property);
}
?>