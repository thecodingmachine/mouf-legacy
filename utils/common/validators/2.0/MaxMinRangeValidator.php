<?php
/**
 * Max, min or both (range) value validator
 * 
 * @Component
 */
class MaxMinRangeValidator extends AbstractValidator implements JsValidatorInterface{
	
	/**
	 * The max value of the field
	 * @Property
	 * @Mandatory
	 * @var int
	 */
	public $maxValue = null;
	
	/**
	* The min value of the field
	* @Property
	* @Mandatory
	* @var int
	*/
	public $minValue = null;
	
	/**
	 * (non-PHPdoc)
	 * @see ValidatorInterface::validate()
	 */
	function doValidate($value){
		if (!is_numeric($value)) return false;
		if ($this->minValue && !$this->maxValue)
			return $value >= $this->minValue;
		else if ($this->maxValue && !$this->minValue)
			return $value <= $this->minValue;
		else if ($this->minValue && $this->maxValue)
			return $value >= $this->minValue && $value <= $this->maxValue;
	}

	/**
	 * (non-PHPdoc)
	 * @see JsValidatorInterface::getScript()
	 */
	function getScript(){
		if ($this->minValue && !$this->maxValue)
			return "
		function(value, element){
			return value >= $this->minValue;
		}";
		else if ($this->maxValue && !$this->minValue)
			return "
		function(value, element){
			return value <= $this->maxValue;
		}";
		else if ($this->minValue && $this->maxValue)
			return "
		function(value, element){
			return value >= $this->minValue && value <= $this->maxValue;
		}";
	}
	
	/**
	 * (non-PHPdoc)
	 * @see JsValidatorInterface::getErrorMessage()
	 */
	function getErrorMessage(){
		if ($this->minValue && !$this->maxValue)
			return iMsg("validate.min");
		else if ($this->maxValue && !$this->minValue)
			return iMsg("validate.max");
		else if ($this->minValue && $this->maxValue)
			return iMsg("validate.range");
	}
	
	/**
	 * (non-PHPdoc)
	 * @see JsValidatorInterface::getJsArguments()
	 */
	function getJsArguments(){
		if ($this->minValue && !$this->maxValue)
			return array($this->minValue);
		else if ($this->maxValue && !$this->minValue)
			return array($this->maxValue);
		else if ($this->minValue && $this->maxValue)
			return array($this->minValue, $this->maxValue);
	}
}
?>