<?php
require_once 'MoufPhpDocComment.php';
require_once 'MoufXmlReflectionMethod.php';
require_once 'MoufXmlReflectionProperty.php';

/**
 * This class behaves like MoufReflectionClass, except it is completely based on a Xml message.
 * It does not try to access the real class.
 * Therefore, you can use this class to perform reflection in a class that is not loaded, which can
 * be useful.
 * 
 */
class MoufXmlReflectionClass {
	
	/**
	 * The XML message we will analyse
	 *
	 * @var SimpleXmlElement
	 */
	private $xmlRoot;
	
	/**
	 * The phpDocComment we will use to access annotations.
	 *
	 * @var MoufPhpDocComment
	 */
	private $docComment;
	
	/**
	 * Default constructor
	 *
	 * @param string $className The name of the class to analyse.
	 */
	public function __construct($xmlStr) {
		$this->xmlRoot = simplexml_load_string($xmlStr);
		
		if ($this->xmlRoot == null) {
			throw new Exception("An error occured while retrieving message: ".$xmlStr);
		}
	}
	
	/**
	 * Returns the class name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->xmlRoot['name'];
	}
	
	
	
	/**
	 * Returns the comment for the class.
	 *
	 * @return string
	 */
	public function getDocComment() {
		return (string)($this->xmlRoot->comment);
	}
	
	/**
	 * Analyzes and parses the comment (if it was not previously done).
	 *
	 */
	private function analyzeComment() {
		if ($this->docComment == null) {
			$this->docComment = new MoufPhpDocComment($this->getDocComment());
		}
	}
	
	/**
	 * Returns the comment text, without the annotations.
	 *
	 * @return string
	 */
	public function getDocCommentWithoutAnnotations() {
		$this->analyzeComment();
		
		return $this->docComment->getComment();
	}
	
	/**
	 * Returns the number of declared annotations of type $annotationName in the class comment.
	 *
	 * @param string $annotationName
	 * @return int
	 */
	public function hasAnnotation($annotationName) {
		$this->analyzeComment();
		
		return $this->docComment->getAnnotationsCount($annotationName);
	}
	
	/**
	 * Returns the annotation objects associated to $annotationName in an array.
	 * For instance, if there is one annotation "@Filter toto", there will be an array of one element.
	 * The element will contain an object of type FilterAnnotation. If the class FilterAnnotation is not defined,
	 * a string is returned instead of an object.  
	 *
	 * @param string $annotationName
	 * @return array<$objects>
	 */
	public function getAnnotations($annotationName) {
		$this->analyzeComment();
		
		return $this->docComment->getAnnotations($annotationName);
	}
	
	/**
	 * Returns a map associating the annotation title to an array of objects representing the annotation.
	 * 
	 * @var array("annotationClass"=>array($annotationObjects))
	 */
	public function getAllAnnotations() {
		$this->analyzeComment();
		
		return $this->docComment->getAllAnnotations();
	}
	
	/**
     * returns the specified method or null if it does not exist
     *
     * @param   string                $name  name of method to return
     * @return  MoufReflectionMethod
     */
    /*public function getMethod($name)
    {
        if (parent::hasMethod($name) == false) {
            return null;
        }
        
        $moufRefMethod = new MoufReflectionMethod($this, $name);
        return $moufRefMethod;
    }*/

    /**
     * returns a list of all methods
     *
     * @return  array<MoufReflectionMethod>
     */
    /*public function getMethods()
    {
        $methods    = parent::getMethods();
        $moufMethods = array();
        foreach ($methods as $method) {
            $moufMethods[] = new MoufReflectionMethod($this, $method->getName());
        }
        
        return $moufMethods;
    }*/

    /**
     * returns the specified property or null if it does not exist
     *
     * @param   string                  $name  name of property to return
     * @return  MoufReflectionProperty
     */
    public function getProperty($name)
    {
    	foreach ($this->xmlRoot->property as $property) {
    		if ($property['name'] == $name) {
		        $moufRefProperty = new MoufXmlReflectionProperty($this, $property);
		        return $moufRefProperty;
    		}
    	}
    	return null;
        /*if (parent::hasProperty($name) == false) {
            return null;
        }
        
        $moufRefProperty = new MoufReflectionProperty($this, $name);
        return $moufRefProperty;*/
    }

    /**
     * returns a list of all properties
     *
     * @return  array<MoufReflectionProperty>
     */
    public function getProperties()
    {
        //$properties     = parent::getProperties();
        
        $moufProperties = array();
        foreach ($this->xmlRoot->property as $property) {
            $moufProperties[] = new MoufXmlReflectionProperty($this, $property);
        }
        
        return $moufProperties;
    }

    /**
     * returns a list of all properties which satify the given matcher
     *
     * @param   MoufPropertyMatcher            $propertyMatcher
     * @return  array<MoufReflectionProperty>
     */
    /*public function getPropertiesByMatcher(MoufPropertyMatcher $propertyMatcher)
    {
        $properties     = parent::getProperties();
        $moufProperties = array();
        foreach ($properties as $property) {
            if ($propertyMatcher->matchesProperty($property) === true) {
                $moufProperty = new MoufReflectionProperty($this, $property->getName());
                if ($propertyMatcher->matchesAnnotatableProperty($moufProperty) === true) {
                    $moufProperties[] = $moufProperty;
                }
            }
        }
        
        return $moufProperties;
    }*/

    /**
     * returns a list of all interfaces
     *
     * @return  array<MoufReflectionClass>
     */
    /*public function getInterfaces()
    {
        $interfaces     = parent::getInterfaces();
        $moufRefClasses = array();
        foreach ($interfaces as $interface) {
            $moufRefClasses[] = new self($interface->getName());
        }
        
        return $moufRefClasses;
    }*/

    /**
     * returns a list of all interfaces
     *
     * @return  MoufReflectionClass
     */
    /*public function getParentClass()
    {
        $parentClass  = parent::getParentClass();
        if (null === $parentClass || false === $parentClass) {
            return null;
        }
        
        $moufRefClass = new self($parentClass->getName());
        return $moufRefClass;
    }*/

    /**
     * returns the extension to where this class belongs too
     *
     * @return  MoufReflectionExtension
     */
    /*public function getExtension()
    {
        $extensionName  = $this->getExtensionName();
        if (null === $extensionName || false === $extensionName) {
            return null;
        }
        
        $moufRefExtension = new MoufReflectionExtension($extensionName);
        return $moufRefExtension;
    }*/
    
}
?>