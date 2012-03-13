<?php
/*
 * This file is part of the Mouf core package.
 *
 * (c) 2012 David Negrier <david@mouf-php.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */
 
require_once dirname(__FILE__)."/../annotations/varAnnotation.php";

/**
 * This class contains shared code between MoufXmlReflectionClass and MoufReflectionClass.
 * Gosh, wish I could use those traits...
 * 
 * @author David Negrier
 *
 */
class MoufReflectionHelper {
	
	/**
	 * Returns the Mouf properties for a class.
	 */
	public static function getMoufProperties($refClass) {
		$moufProperties = array();
		 
		foreach($refClass->getProperties() as $attribute) {
			/* @var $attribute MoufXmlReflectionProperty */
			if ($attribute->hasAnnotation("Property")) {
				$propertyDescriptor = new MoufPropertyDescriptor($attribute);
				//$moufProperties[] = $attribute;
				$moufProperties[$attribute->getName()] = $propertyDescriptor;
			}
		}
		 
		foreach($refClass->getMethods() as $method) {
			/* @var $attribute MoufXmlReflectionProperty */
			if ($method->hasAnnotation("Property")) {
				$propertyDescriptor = new MoufPropertyDescriptor($method);
				//$moufProperties[] = $attribute;
				$moufProperties[$method->getName()] = $propertyDescriptor;
			}
		}
		return $moufProperties;	
	}
	
	/**
	 * Returns a PHP array representing the class.
	 *
	 * @return array
	 */
	public function classToJson($refClass) {
		$result = array();
		$result['name'] = $refClass->getName();
		$result['comment'] = $refClass->getMoufDocComment()->getJsonArray();
		$result['implements'] = array();
		/* @var $refClass MoufReflectionClass */
		$interfaces = $refClass->getInterfaces();
		foreach ($interfaces as $interface) {
			/* @var $interface MoufReflectionClass */
			$result['implements'][] = $interface->getName(); 
		}

		/*$extends = array();
		$currentClass = $refClass;
		while ($currentClass->getExtension()) {
			$currentClass = $currentClass->getExtension();
			$extends[] = $currentClass->getName();
		}
		$result['extends'] = $extends;*/
		if ($refClass->getParentClass()) {
			$result['extend'] = $refClass->getParentClass()->getName();
		}
		
		$result['properties'] = array();
		foreach ($refClass->getProperties() as $property) {
			$result['properties'][] = self::propertyToJson($property);
		}
		
		$result['methods'] = array();
		foreach ($refClass->getMethods() as $method) {
			$result['methods'][] = self::methodToJson($method);
		}
		 
		return $result;
	}

	/**
	 * Returns a PHP array representing the property.
	 *
	 * @return array
	 */
	public function propertyToJson(MoufReflectionPropertyInterface $refProperty) {
		$result = array();
		$result['name'] = $refProperty->getName();
		$result['comment'] = $refProperty->getMoufPhpDocComment()->getJsonArray();
		$result['default'] = $refProperty->getDefault();

		$properties = $refProperty->getAnnotations("Property");
		if (!empty($properties)) {
			$result['moufProperty'] = true;
			$moufPropertyDescriptor = new MoufPropertyDescriptor($refProperty);
			$result['type'] = $moufPropertyDescriptor->getType();
			if ($moufPropertyDescriptor->isAssociativeArray()) {
				$result['keytype'] = $moufPropertyDescriptor->getKeyType();
			}
			if ($moufPropertyDescriptor->isArray()) {
				$result['subtype'] = $moufPropertyDescriptor->getSubType();
			}
		}		
				
		return $result;
	}
	
	/**
	 * Returns a PHP array representing the method.
	 *
	 * @return array
	 */
	public function methodToJson($refMethod) {
		$result = array();
		$result['name'] = $refMethod->getName();
		
		$modifier = "";
		if ($refMethod->isPublic()) {
			$modifier = "public";
		} elseif ($refMethod->isProtected()) {
			$modifier = "protected";
		} elseif ($refMethod->isPrivate()) {
			$modifier = "private";
		}
		$result['modifier'] = $modifier;
		$result['static'] = $refMethod->isStatic();
		$result['abstract'] = $refMethod->isAbstract();
		$result['constructor'] = $refMethod->isConstructor();
		$result['final'] = $refMethod->isFinal();
		//$result['comment'] = $refMethod->getDocComment();
		$result['comment'] = $refMethod->getMoufPhpDocComment()->getJsonArray();
		
		$result['parameters'] = array();
		$parameters = $refMethod->getParameters();
		foreach ($parameters as $parameter) {
			$result['parameters'][] = self::parameterToJson($parameter);
		}
		
		$properties = $refMethod->getAnnotations("Property");
		if (!empty($properties)) {
			$result['moufProperty'] = true;
			$moufPropertyDescriptor = new MoufPropertyDescriptor($refMethod);
			$result['type'] = $moufPropertyDescriptor->getType();
			if ($moufPropertyDescriptor->isAssociativeArray()) {
				$result['keytype'] = $moufPropertyDescriptor->getKeyType();
			}
			if ($moufPropertyDescriptor->isArray()) {
				$result['subtype'] = $moufPropertyDescriptor->getSubType();
			}
		}		
		
		return $result;
	}
	
	/**
	 * Returns a PHP array representing the parameter.
	 *
	 * @return array
	 */
	public function parameterToJson($refParameter) {
		$result = array();
		$result['name'] = $refParameter->getName();
		$result['hasDefault'] = $refParameter->isDefaultValueAvailable();
		if ($result['hasDefault']) {
			$result['default'] = $refParameter->getDefaultValue();
		}
		$result['isArray'] = $refParameter->isArray();
		if ($refParameter->getClass() != null) {
			$result['class'] = $refParameter->getClass()->getName();
		}
		return $result;
	}
}