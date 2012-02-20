<?php
/**
 * @Component
 * Enter description here ...
 * @author Kevin
 *
 */
class NumericValidator extends BaseValidator implements ValidatorInterface{
	
	/**
	 * If the value may be a decimal 
	 * @Property
	 * @var bool
	 */
	public $allowDecimal = true;
	
	/**
	 * The min value accepted
	 * @Property
	 * @var float
	 */
	public $minVal;

	/**
	 * The max value accepted
	 * @Property
	 * @var float
	 */
	public $maxVal;
	
	public function validate($value){
		if (!is_numeric($value)){
			$ret = false;
			$message = "Invalid numeric value";
		}else if (!$this->decimal && !is_int($value)){
			$ret = false;
			$message = "Decimal values not acceped";
		}else if ($this->minVal && $this->minVal > $value){
			$ret = false;
			$message = "Value must be over $this->minVal";
		}else if ($this->maxVal && $this->maxVal < $value){
			$ret = false;
			$message = "Value must be less than $this->maxVal";
		}
		else{
			return true;
		}
	}
	
	public function loadRules(){
		parent::loadRules();
		$this->jsRules["number"] = true;
		
		if ($this->minVal){
			$this->jsRules["min"] = $this->minVal;
		}
		if ($this->maxVal){
			$this->jsRules["max"] = $this->maxVal;
		}
	}
	
}