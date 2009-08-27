<?php
/**
 * Annotation for XMLSerializer
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  xml_serializer_annotations
 */
stubClassLoader::load('net::stubbles::reflection::annotations::stubAnnotation',
                      'net::stubbles::reflection::annotations::stubAbstractAnnotation',
                      'net::stubbles::xml::stubXMLException',
                      'net::stubbles::xml::serializer::annotations::stubXMLMethodsAnnotation',
                      'net::stubbles::xml::serializer::annotations::stubXMLPropertiesAnnotation'
);

/**
 * Annotation for XMLSerializer
 *
 * Use this annotation to define, which properties/methods of a class should be serialized
 *
 * Properties of the annotation are:
 * - pattern
 *
 * @package     stubbles
 * @subpackage  xml_serializer_annotations
 */
class stubXMLMatcherAnnotation extends stubAbstractAnnotation implements stubAnnotation, stubXMLPropertiesAnnotation, stubXMLMethodsAnnotation
{
    /**
     * Pattern of the properties, that should be serialized
     *
     * @var  string
     */
    protected $pattern;

    /**
     * Set the pattern
     *
     * @param  string  $pattern
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * Get the name of the tag to use for a property
     *
     * @param   stubReflectionProperty  $property
     * @return  string|false
     * @throws  stubXMLException
     */
    public function getTagnameForProperty(stubReflectionProperty $property)
    {
        $matches = array();
        $success = @preg_match($this->pattern, $property->getName(), $matches);
        if (false === $success) {
            throw new stubXMLException("Syntax error in regular expression '{$this->pattern}': {$php_errormsg}");
        }
        
        if (empty($matches) === true) {
            return false;
        }
        
        if (isset($matches[1]) === true) {
            return $matches[1];
        }
        
        return $matches[0];
    }

    /**
     * Get the name of the tag to use for a method
     *
     * @param   stubReflectionMethod  $method
     * @return  string|bool
     * @throws  stubXMLException
     */
    public function getTagnameForMethod(stubReflectionMethod $method)
    {
        $matches = array();
        $success = preg_match($this->pattern, $method->getName(), $matches);
        if (false === $success) {
            throw new stubXMLException("Syntax error in regular expression '{$this->pattern}': {$php_errormsg}");
        }
        
        if (empty($matches) === true) {
            return false;
        }
        
        if (isset($matches[1]) === true) {
            $name = $matches[1];
        } else {
            $name = $matches[0];
        }
        
        return strtolower($name{0}) . substr($name, 1);
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