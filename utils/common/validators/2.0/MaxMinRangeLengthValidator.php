<?php
/**
 * Numeric validator: validates a input to be a numeric value.
 * Validation may be specified if decimal values are accepted
 * 
 * @Component
 */
class MaxMinRangeLengthValidator extends AbstractValidator implements JsValidatorInterface{
	
	/**
	 * The max Length of the field
	 * @Property
	 * @Mandatory
	 * @var int
	 */
	public $maxLength = null;
	
	/**
	* The min Length of the field
	* @Property
	* @Mandatory
	* @var int
	*/
	public $minLength = null;
	
	/**
	 * (non-PHPdoc)
	 * @see ValidatorInterface::validate()
	 */
	function doValidate($value){
		if ($this->minLength && !$this->maxLength)
			return strlen($value) <= $this->minLength;
		else if ($this->maxLength && !$this->minLength)
			return strlen($value) <= $this->minLength;
		else if ($this->minLength && $this->maxLength)
			return strlen($value) >= $this->minLength && strlen($value) <= $this->maxLength;
	}

	/**
	 * (non-PHPdoc)
	 * @see JsValidatorInterface::getScript()
	 */
	function getScript(){
		if ($this->minLength && !$this->maxLength)
			return "
		function(value, element){
			return _getLength($.trim(value), element) >= $this->minLength;
		}";
		else if ($this->maxLength && !$this->minLength)
			return "
		function(value, element){
			return _getLength($.trim(value), element) <= $this->maxLength;
		}";
		else if ($this->minLength && $this->maxLength)
			return "
		function(value, element){
			var length = _getLength($.trim(value), element);
			return length >= $this->minLength && length <= $this->maxLength;
		}";
	}
	
	/**
	 * (non-PHPdoc)
	 * @see JsValidatorInterface::getErrorMessage()
	 */
	function getErrorMessage(){
		if ($this->minLength && !$this->maxLength)
			return ValidatorUtils::translate("validate.min-length", $this->minLength, $this->maxLength);
		else if ($this->maxLength && !$this->minLength)
			return ValidatorUtils::translate("validate.max-length", $this->minLength, $this->maxLength);
		else if ($this->minLength && $this->maxLength)
			return ValidatorUtils::translate("validate.range-length", $this->minLength, $this->maxLength);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see JsValidatorInterface::getJsArguments()
	 */
	function getJsArguments(){
		if ($this->minLength && !$this->maxLength)
			return array($this->minLength);
		else if ($this->maxLength && !$this->minLength)
			return array($this->maxLength);
		else if ($this->minLength && $this->maxLength)
			return array($this->minLength, $this->maxLength);
	}
}
?>