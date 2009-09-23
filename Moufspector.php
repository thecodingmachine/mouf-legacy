<?php

require_once 'reflection/MoufReflectionClass.php';
require_once 'MoufPropertyDescriptor.php';

/**
 * This class is used internally by Mouf and is specialized in analysing classes to find properties, etc... depending on the annotations. 
 *
 */
class Moufspector {
	
	/**
	 * Returns a list of all the classes that have the @Component attribute.
	 *
	 */
	public static function getComponentsList() {
		$classesList = get_declared_classes();
		$componentsList = array();
		
		foreach ($classesList as $className) {
			$refClass = new MoufReflectionClass($className);
			if ($refClass->hasAnnotation("Component")) {
				$componentsList[] = $className;
			}
		}
		return $componentsList;
	}
	
	/**
	 * Returns the list of properties the class $className does contain. 
	 *
	 * @param MoufXmlReflectionClass $class
	 * @return array An array containing MoufXmlReflectionProperty objects.
	 */
	public static function getPropertiesForClass(MoufXmlReflectionClass $refClass) {
		//$refClass = new MoufReflectionClass($className);
		
		$propertiesList = array();
		
		foreach($refClass->getProperties() as $attribute) {
			//$t = new stubReflectionProperty();
			if ($attribute->hasAnnotation("Property")) {
				$propertyDescriptor = new MoufPropertyDescriptor($attribute);
				//$propertiesList[] = $attribute;
				$propertiesList[] = $propertyDescriptor;
			}
		}
		
		foreach($refClass->getMethods() as $method) {
			//$t = new stubReflectionProperty();
			if ($method->hasAnnotation("Property")) {
				$propertyDescriptor = new MoufPropertyDescriptor($method);
				//$propertiesList[] = $attribute;
				$propertiesList[] = $propertyDescriptor;
			}
		}
		
		// TODO: transform Property into a MoufProperty object (name + source (getter or public property) + type variable).
		
		
		return $propertiesList;
	}
	
	/**
	 * Returns the property type.
	 * This can be one of: "string", "number", "oneof", "pointer".
	 *
	 * @param string $className
	 * @param string $property
	 */
	public static function getPropertyType($className, $property) {
		$refClass = new MoufReflectionClass($className);
		$refProperty = $refClass->getProperty($property);
		
		if ($refProperty->hasAnnotation('OneOf')) {
			return "oneof";
		}
		//if ($parameter->hasAnnotation('Var') != false) {
		
		return "pas mouf";
	}
	
	private static function analyzeClass($className) {
		$refClass = new ReflectionClass($className);
		
		$docComments = $refClass->getDocComment();
		
		$phpDocComment = new MoufPhpDocComment($docComments);
	}
	
	public static function testComment() {
		/*$refClass = new stubReflectionClass("PaypalConfig");
		$refProperty = $refClass->getProperty("paypalUrl");
		echo implode("\n", self::getDocLinesFromComment($refProperty->getDocComment()));*/
		//self::analyzeClass("PaypalConfig");
		$refClass = new MoufReflectionClass("PaypalConfig");
		$refProperty = $refClass->getProperty("paypalUrl");
		
		var_dump($refProperty->getAllAnnotations());
	}
	
	
}
?>