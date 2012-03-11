<?php

/**
 * The interface implemented by objects representing a property (with or without a @Property annotation).
 * 
 * @author David Negrier
 */
interface MoufReflectionPropertyInterface {
	/**
	 * Returns the property name
	 *
	 * @return string
	 */
	public function getName();
	
	/**
	 * Returns the default value
	 *
	 * @return mixed
	 */
	public function getDefault();
	
	/**
	 * Returns the full comment for the method
	 *
	 * @return string
	 */
	public function getDocComment();
	
	/**
	 * Returns the MoufPhpDocComment instance
	 *
	 * @return MoufPhpDocComment
	 */
	public function getMoufPhpDocComment();
	
	/**
	 * Returns the number of declared annotations of type $annotationName in the class comment.
	 *
	 * @param string $annotationName
	 * @return int
	 */
	public function hasAnnotation($annotationName);
	
	/**
	 * Returns the annotation objects associated to $annotationName in an array.
	 * For instance, if there is one annotation "@Filter toto", there will be an array of one element.
	 * The element will contain an object of type FilterAnnotation. If the class FilterAnnotation is not defined,
	 * a string is returned instead of an object.
	 *
	 * @param string $annotationName
	 * @return array<$objects>
	 */
	public function getAnnotations($annotationName);
	
	/**
	 * Returns a map associating the annotation title to an array of objects representing the annotation.
	 *
	 * @var array("annotationClass"=>array($annotationObjects))
	 */
	public function getAllAnnotations();
}