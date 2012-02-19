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
	 * @var bool
	 */
	public $decimal;
	
	/**
	 * The min value accepted
	 * @var float
	 */
	public $minVal;

	/**
	 * The max value accepted
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
		}else if ($this->min && $this->min > $value){
			$ret = false;
			$message = "Value must be over $this->min";
		}else if ($this->max && $this->max < $value){
			$ret = false;
			$message = "Value must be less than $this->max";
		}
		else{
			return true;
		}
	}
	
	public function getHtmlAttribute(){
		$this->jsRules["number"] = true;
	}
	
}