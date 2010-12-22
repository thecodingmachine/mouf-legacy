<?php

/**
 * Implement this interface if you want one of your components to be exposed as an annotation.
 * When requesting the annotations using the getAnnotations method, Mouf will 
 * associate the @fooBar annotation to the instance whose name is "fooBar".
 * 
 * Please note that Mouf will return a CLONE of the annotation instance, and not the
 * annotation instance itself.
 */
interface MoufAnnotationInterface
{
	/**
	 * Sets the parameters stored in this annotation.
	 * This function is automatically called just after the annotation is created.
	 * 
	 * @param string $value
	 */
	function setValue($value);	
}
?>