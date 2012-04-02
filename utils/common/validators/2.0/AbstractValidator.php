<?php

/**
 * Abstract class used by most validators.
 */
abstract class AbstractValidator implements ValidatorInterface {

	protected $param;

	public function validate($value){
		$test = $this->doValidate($value);
		if ($test){
			return true;
		}
		else{
			return array(false, $this->getErrorMessage());
		}
	}
	
}

?>
