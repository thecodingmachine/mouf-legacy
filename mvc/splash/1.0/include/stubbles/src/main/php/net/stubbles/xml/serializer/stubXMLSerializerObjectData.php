<?php
/**
 * Container for extracting informations on how to serialize a class.
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  xml_serializer
 */
stubClassLoader::load('net::stubbles::lang::exceptions::stubIllegalArgumentException',
                      'net::stubbles::xml::serializer::annotations::stubXMLTagAnnotation',
                      'net::stubbles::xml::serializer::annotations::stubXMLFragmentAnnotation',
                      'net::stubbles::xml::serializer::annotations::stubXMLAttributeAnnotation',
                      'net::stubbles::xml::serializer::annotations::stubXMLIgnoreAnnotation',
                      'net::stubbles::xml::serializer::annotations::stubXMLPropertiesAnnotation',
                      'net::stubbles::xml::serializer::annotations::stubXMLMethodsAnnotation',
                      'net::stubbles::xml::serializer::annotations::stubXMLMatcherAnnotation',
                      'net::stubbles::xml::serializer::annotations::stubXMLStrategyAnnotation',
                      'net::stubbles::xml::serializer::matcher::stubXMLSerializerMethodPropertyMatcher',
                      'net::stubbles::reflection::stubReflectionObject'
);
/**
 * Container for extracting informations on how to serialize a class.
 *
 * @package     stubbles
 * @subpackage  xml_serializer
 */
class stubXMLSerializerObjectData extends stubBaseObject
{
    /**
     * list of key-values pairs containing informations about the class to serialize
     *
     * @var  array<string,mixed>
     */
    protected $classData  = array();
    /**
     * list of properties to serialize
     *
     * @var  array<string,array<string,scalar>>
     */
    protected $properties  = array();
    /**
     * list of methods to serialize
     *
     * @var  array<string,array<string,scalar>>
     */
    protected $methods     = array();
    /**
     * reflection instance of class to serialize
     *
     * @var  stubBaseReflectionClass
     */
    protected $refClass;
    /**
     * the matcher to be used for methods and properties
     *
     * @var  stubXMLSerializerMethodPropertyMatcher
     */
    protected static $methodAndPropertyMatcher;
    /**
     * simple cache
     *
     * @var  array
     */
    protected static $cache = array();

    /**
     * static initializer
     */
    // @codeCoverageIgnoreStart
    public static function __static()
    {
        self::$methodAndPropertyMatcher = new stubXMLSerializerMethodPropertyMatcher();
    }
    // @codeCoverageIgnoreEnd

    /**
     * constructor
     *
     * It is recommended to not use the constructor but the static fromObject()
     * method. The constructor should be used if one is sure that there is only
     * one instance of a class to serialize.
     *
     * @param   object  $object
     * @throws  stubIllegalArgumentException
     */
    public function __construct($object)
    {
        if (is_object($object) === false) {
            throw new stubIllegalArgumentException('Can only handle objects.');
        }
        
        $this->refClass              = new stubReflectionObject($object);
        $this->classData['tagName']  = (($this->refClass->hasAnnotation('XMLTag') === true) ? ($this->refClass->getAnnotation('XMLTag')->getTagName()) : ($this->refClass->getName()));
        $this->classData['strategy'] = (($this->refClass->hasAnnotation('XMLStrategy') === false) ? (null) : ($this->refClass->getAnnotation('XMLStrategy')->getValue()));
        $this->extractProperties();
        $this->extractMethods();
    }

    /**
     * creates the structure from given object
     *
     * This method will cache the result - on the next request with the same
     * class it will return the same result, even if the given object is a
     * different instance.
     *
     * @param   object                       $object
     * @return  stubXMLSerializerObjectData
     * @throws  stubIllegalArgumentException
     */
    public static function fromObject($object)
    {
        if (is_object($object) === false) {
            throw new stubIllegalArgumentException('Can only handle objects.');
        }
        
        $class = get_class($object);
        if (isset(self::$cache[$class]) === true) {
            return self::$cache[$class];
        }
        
        self::$cache[$class] = new self($object);
        return self::$cache[$class];
    }

    /**
     * extract informations about properties
     */
    protected function extractProperties()
    {
        $properties = $this->refClass->getPropertiesByMatcher(self::$methodAndPropertyMatcher);
        $matcher    = null;
        if ($this->refClass->hasAnnotation('XMLProperties')) {
            $matcher = $this->refClass->getAnnotation('XMLProperties');
        }

        foreach ($properties as $property) {
            $data = $this->extractFromAnnotatableElement($property);
            if (null !== $data) {
                $this->properties[$property->getName()] = $data;
            } else {
                if (null !== $matcher) {
                    $tagName = $matcher->getTagnameForProperty($property);
                    if (false === $tagName) {
                        continue;
                    }
                    
                    $mustSerialize = true;
                } else {
                    $mustSerialize = false;
                    $tagName       = $property->getName();
                }
                
                $this->properties[$property->getName()] = array('type'          => 'tag',
                                                                'tagName'       => $tagName,
                                                                'elementName'   => null,
                                                                'mustSerialize' => $mustSerialize
                                                          );
            }
        }
    }

    /**
     * extract informations about methods
     */
    protected function extractMethods()
    {
        $methods = $this->refClass->getMethodsByMatcher(self::$methodAndPropertyMatcher);
        $matcher = null;
        if ($this->refClass->hasAnnotation('XMLMethods')) {
            $matcher = $this->refClass->getAnnotation('XMLMethods');
        }

        foreach ($methods as $method) {
            $data = $this->extractFromAnnotatableElement($method);
            if (null !== $data) {
                $this->methods[$method->getName()] = $data;
            } else {
                if (null !== $matcher) {
                    $tagName = $matcher->getTagnameForMethod($method);
                    if (false === $tagName) {
                        continue;
                    }
                    
                    $mustSerialize = true;
                } else {
                    $mustSerialize = false;
                    $tagName = $method->getName();
                }
                
                $this->methods[$method->getName()] = array('type'          => 'tag',
                                                           'tagName'       => $tagName,
                                                           'elementName'   => null,
                                                           'mustSerialize' => $mustSerialize
                                                     );
            }
        }
    }

    /**
     * extracts informations about annotated element
     *
     * @param   stubAnnotatable  $annotatable  the annotatable element to serialize
     * @return  array
     */
    protected function extractFromAnnotatableElement(stubAnnotatable $annotatable)
    {
        if ($annotatable->hasAnnotation('XMLAttribute') === true) {
            $xmlAttribute = $annotatable->getAnnotation('XMLAttribute');
            return array('type'            => 'attribute',
                         'attributeName'   => $xmlAttribute->getAttributeName(),
                         'shouldSkipEmpty' => $xmlAttribute->shouldSkipEmpty()
                   );
        } elseif ($annotatable->hasAnnotation('XMLFragment') === true) {
            return array('type'    => 'fragment',
                         'tagName' => $annotatable->getAnnotation('XMLFragment')->getTagName()
                   );
        } elseif ($annotatable->hasAnnotation('XMLTag') === true) {
            $xmlTag = $annotatable->getAnnotation('XMLTag');
            return array('type'          => 'tag',
                         'tagName'       => $xmlTag->getTagName(),
                         'elementName'   => $xmlTag->getElementTagName(),
                         'mustSerialize' => true
                   );
        }
        
        return null;
    }

    /**
     * returns the tagname to be used for the object
     *
     * The given argument resambles the tagname requested by the serializer.
     * Only if this is NULL the extracted tagname will be returned.
     *
     * @param   string  $tagName
     * @return  string
     */
    public function getTagName($tagName)
    {
        if (null !== $tagName) {
            return $tagName;
        }
        
        return $this->classData['tagName'];
    }

    /**
     * returns the strategy for serialization
     *
     * If no strategy is annotated to the class the default value will be returned.
     *
     * @param   int  $default
     * @return  int
     */
    public function getStrategy($default)
    {
        if (null !== $this->classData['strategy']) {
            return $this->classData['strategy'];
        }
        
        return $default;
    }

    /**
     * returns informations about properties to serialize
     *
     * @return  array<string,array<string,scalar>>
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * returns informations about methods to serialize
     *
     * @return  array<string,array<string,scalar>>
     */
    public function getMethods()
    {
        return $this->methods;
    }
}
?>