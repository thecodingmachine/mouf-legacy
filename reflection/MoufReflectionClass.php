<?php
/*
 * This file is part of the Mouf core package.
 *
 * (c) 2012 David Negrier <david@mouf-php.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */
 
require_once 'MoufReflectionClassInterface.php';
require_once 'MoufPhpDocComment.php';
require_once 'MoufReflectionMethod.php';
require_once 'MoufReflectionProperty.php';

/**
 * Reflection class extending ReflectionClass in order to access annotations.
 * 
 */
class MoufReflectionClass extends ReflectionClass implements MoufReflectionClassInterface {
	
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
	public function __construct($className) {
		parent::__construct($className);
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
    public function getMethod($name)
    {
        if (parent::hasMethod($name) == false) {
            return null;
        }
        
        $moufRefMethod = new MoufReflectionMethod($this, $name);
        return $moufRefMethod;
    }

    /**
     * returns a list of all methods
     *
     * @return  array<MoufReflectionMethod>
     */
    public function getMethods()
    {
        $methods    = parent::getMethods();
        $moufMethods = array();
        foreach ($methods as $method) {
            $moufMethods[] = new MoufReflectionMethod($this, $method->getName());
        }
        
        return $moufMethods;
    }

    /**
     * returns a list of all methods which satify the given matcher
     *
     * @param   MoufMethodMatcher            $methodMatcher
     * @return  array<MoufReflectionMethod>
     */
    /*public function getMethodsByMatcher(MoufMethodMatcher $methodMatcher)
    {
        $methods     = parent::getMethods();
        $moufMethods = array();
        foreach ($methods as $method) {
            if ($methodMatcher->matchesMethod($method) === true) {
                $moufMethod = new MoufReflectionMethod($this, $method->getName());
                if ($methodMatcher->matchesAnnotatableMethod($moufMethod) === true) {
                    $moufMethods[] = $moufMethod;
                }
            }
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
        if (parent::hasProperty($name) == false) {
            return null;
        }
        
        $moufRefProperty = new MoufReflectionProperty($this, $name);
        return $moufRefProperty;
    }

    /**
     * returns a list of all properties
     *
     * @return  array<MoufReflectionProperty>
     */
    public function getProperties()
    {
        $properties     = parent::getProperties();
        $moufProperties = array();
        foreach ($properties as $property) {
            $moufProperties[] = new MoufReflectionProperty($this, $property->getName());
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
    public function getInterfaces()
    {
        $interfaces     = parent::getInterfaces();
        $moufRefClasses = array();
        foreach ($interfaces as $interface) {
            $moufRefClasses[] = new self($interface->getName());
        }
        
        return $moufRefClasses;
    }

    /**
     * returns a list of all interfaces
     *
     * @return  MoufReflectionClass
     */
    public function getParentClass()
    {
        $parentClass  = parent::getParentClass();
        if (null === $parentClass || false === $parentClass) {
            return null;
        }
        
        $moufRefClass = new self($parentClass->getName());
        return $moufRefClass;
    }

    /**
     * returns the extension to where this class belongs too
     *
     * @return  MoufReflectionExtension
     */
    public function getExtension()
    {
        $extensionName  = $this->getExtensionName();
        if (null === $extensionName || false === $extensionName) {
            return null;
        }
        
        $moufRefExtension = new MoufReflectionExtension($extensionName);
        return $moufRefExtension;
    }
    
    /**
    * The list of Mouf properties this class contains.
    * This is initialized by a call to getMoufProperties()
    *
    * @var array<MoufPropertyDescriptor> An array containing MoufXmlReflectionProperty objects.
    */
    private $moufProperties = null;
    
    /**
     * Returns a list of properties that have the @Property annotation (and a list of setter that have the @Property annotation)
     *
     * @return array<string, MoufPropertyDescriptor> An array containing MoufXmlReflectionProperty objects.
     */
    public function getMoufProperties() {
    	require_once 'MoufReflectionHelper.php';
    	if ($this->moufProperties === null) {
    		$this->moufProperties = MoufReflectionHelper::getMoufProperties($this);
    	}
    	 
    	return $this->moufProperties;
    }
    
    /**
     * Returns the Mouf property whose name is $name
     * The property name is the "name" of the public property, or the "setter function name" of the setter-based property.
     *
     * @param string $name
     * @return MoufPropertyDescriptor
     */
    public function getMoufProperty($name) {
    	$moufProperties = $this->getMoufProperties();
    	if (isset($moufProperties[$name])) {
    		return $moufProperties[$name];
    	} else {
    		return null;
    	}
    	 
    }
    
    /**
     * Returns the full MoufPhpDocComment
     * 
     * @return MoufPhpDocComment
     */
    public function getMoufDocComment() {
    	$this->analyzeComment();
    	return $this->docComment;
    }
    
    public function toXml() {
    	$root = simplexml_load_string("<class name=\"".$this->getName()."\"></class>");
    	$comment = $root->addChild("comment", $this->getDocComment());

    	foreach ($this->getProperties() as $property) {
    		$property->toXml($root);
    	}

       	foreach ($this->getMethods() as $method) {
    		$method->toXml($root);
    	}
    	
    	$xml = $root->asXml();
    	return $xml;
    }
    
    /**
     * Returns a PHP array representing the class.
     * 
     * @return array
     */
    public function toJson() {
    	require_once __DIR__."/MoufReflectionHelper.php";
    	
    	return MoufReflectionHelper::classToJson($this);
    }

}
?>