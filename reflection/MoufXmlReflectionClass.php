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
	
	public function isPublic() {
		return $this->xmlRoot['modifier']=="public";
	}

	public function isProtected() {
		return $this->xmlRoot['modifier']=="protected";
	}
	
	public function isPrivate() {
		return $this->xmlRoot['modifier']=="private";
	}
	
	public function isStatic() {
		return $this->xmlRoot['static']=="true";
	}
	
	public function isAbstract() {
		return $this->xmlRoot['abstract']=="true";
	}
	
	public function isFinal() {
		return $this->xmlRoot['final']=="true";
	}
	
	public function isConstructor() {
		return $this->xmlRoot['constructor']=="true";
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
     * @return  MoufXmlReflectionMethod
     */
    public function getMethod($name)
    {
        foreach ($this->xmlRoot->method as $method) {
    		if ($method['name'] == $name) {
		        $moufRefMethod = new MoufXmlReflectionMethod($this, $method);
		        return $moufRefMethod;
    		}
    	}
    	return null;
    }
    
	/**
     * returns methods mathcing the given pattern
     *
     * @param   string $regex the regular expression to match (without trailing slashes)
     * @return  array<MoufXmlReflectionMethod>
     */
    public function getMethodsByPattern($regex)
    {
    	$methods = array();
        foreach ($this->xmlRoot->method as $method) {
    		if (preg_match("/$regex/", $method['name'])) {
		        $moufRefMethod = new MoufXmlReflectionMethod($this, $method);
		        $methods[] = $moufRefMethod;
    		}
    	}
    	return $methods;
    }

    /**
     * returns a list of all methods
     *
     * @return  array<MoufXmlReflectionMethod>
     */
    public function getMethods()
    {
        $moufMethods = array();
        foreach ($this->xmlRoot->method as $method) {
            $moufMethods[] = new MoufXmlReflectionMethod($this, $method);
        }
        
        return $moufMethods;
    }

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
     * @return  array<MoufXmlReflectionProperty>
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
    	if ($this->moufProperties === null) {
    		$this->moufProperties = array();
    		 
    		foreach($this->getProperties() as $attribute) {
    			/* @var $attribute MoufXmlReflectionProperty */
    			if ($attribute->hasAnnotation("Property")) {
    				$propertyDescriptor = new MoufPropertyDescriptor($attribute);
    				//$this->moufProperties[] = $attribute;
    				$this->moufProperties[$attribute->getName()] = $propertyDescriptor;
    			}
    		}
    		 
    		foreach($this->getMethods() as $method) {
    			/* @var $attribute MoufXmlReflectionProperty */
    			if ($method->hasAnnotation("Property")) {
    				$propertyDescriptor = new MoufPropertyDescriptor($method);
    				//$this->moufProperties[] = $attribute;
    				$this->moufProperties[$method->getName()] = $propertyDescriptor;
    			}
    		}
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