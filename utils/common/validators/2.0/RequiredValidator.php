<?php
/**
 * Basic validator that defines a value as required
 * @Component
 */
class RequiredValidator implements ValidatorInterface, JsValidatorInterface{
	
	/**
	 * (non-PHPdoc)
	 * @see PhpValidatorInterface::validate()
	 */
	function validate($value){
		if ($value !==0 && !empty($value)){
			return true;
		}else{
			return array(false, $this->getErrorMessage());
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see JsValidatorInterface::getScript()
	 */
	function getScript(){
		return "
			function (value, element){
				if (value) return true;
				else return false;
			}
		";
	}
	
	/**
	 * (non-PHPdoc)
	 * @see JsValidatorInterface::getErrorMessage()
	 */
	function getErrorMessage(){
		return iMsg("validate.required");
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