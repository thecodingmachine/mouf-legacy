<?php
/**
 * Classes that implement this interface are in charge of validating a value on client side.
 * Those classes should define: 
 *  - a js validation script (function using [value] and [message] parameters), 
 *  - an error message that will be used in case of validation error 
 */
interface JsValidatorInterface{
	
	/**
	 * The Javascript validation message : function(value, element){...}
	 * @return string 
	 */
	public function getScript();
	
	/**
	 * The arguments of the validation function 
	 * For example:
	 *   - a range validation will return array($min, $max)
	 *   - a simple validation method will take simply "true"
	 * @return mixed
	 */
	public function getJsArguments();
	
}
?>