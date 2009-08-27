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
 * Use this annotation to define, which methods of a class should be serialized
 *
 * @package     stubbles
 * @subpackage  xml_serializer_annotations
 */
interface stubXMLMethodsAnnotation
{
    /**
     * Get the name of the tag to use for a method
     *
     * @param   stubReflectionMethod  $method
     * @return  string|false
     */
    public function getTagnameForMethod(stubReflectionMethod $method);
}
?>