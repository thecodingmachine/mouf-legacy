<?php
/**
 * Numeric validator: validates a input to be a numeric value.
 * Validation may be specified if decimal values are accepted
 * 
 * @Component
 */
class NumericValidator implements ValidatorInterface, JsValidatorInterface{
	
	/**
	 * Whether or not decimal values are accepted
	 * @Property
	 * @var bool
	 */
	public $allowDecimals = true;
	
	/**
	 * (non-PHPdoc)
	 * @see PhpValidatorInterface::validate()
	 */
	function validate($value){
		$test = $this->allowDecimals ? is_numeric($value) : is_int($value);
		if ($test){
			return true;
		}
		else{
			return array(false, $this->getErrorMessage());
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see JsValidatorInterface::getScript()
	 */
	function getScript(){
		$numericFunction = $this->allowDecimals ? "parseFloat(value)" : "(parseFloat(value) == parseInt(value))";
		return "function(value, element){
			if($numericFunction && !isNaN(value)){
				return true;
			} else {
				return false;
			} 
		}";
	}
	
	/**
	 * (non-PHPdoc)
	 * @see JsValidatorInterface::getErrorMessage()
	 */
	function getErrorMessage(){
		return $this->allowDecimals ? iMsg("validate.numeric") : iMsg("validate.integer");
	}
	
	/**
	 * (non-PHPdoc)
	 * @see JsValidatorInterface::getJsArguments()
	 */
	function getJsArguments(){
		return true;
	}
}
?>