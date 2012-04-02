<?php
/**
 * Basic validator that defines a value as required
 * @Component
 */
class RequiredValidator extends AbstractValidator implements JsValidatorInterface{
	
	/**
	 * (non-PHPdoc)
	 * @see PhpValidatorInterface::validate()
	 */
	function doValidate($value){
		return $value !==0 && !empty($value);
	}

	/**
	 * (non-PHPdoc)
	 * @see JsValidatorInterface::getScript()
	 */
	function getScript(){
		return "
			function (value, element){
				switch( element.nodeName.toLowerCase() ) {
				case 'select':
					// could be an array for select-multiple or a string, both are fine this way
					var val = $(element).val();
					return val && val.length > 0;
				case 'input':
					if ( _checkable(element) )
						return _getLength(value, element) > 0;
				default:
					return $.trim(value).length > 0;
				}
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