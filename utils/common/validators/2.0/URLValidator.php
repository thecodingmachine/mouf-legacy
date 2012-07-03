<?php
/**
 * Numeric validator: validates a input to be a numeric value.
 * Validation may be specified if decimal values are accepted
 * 
 * @Component
 */
class URLValidator extends AbstractValidator implements JsValidatorInterface{
	
	
	/**
	 * Tells if the URL may be a FTP protocol
	 * @Property
	 * @var bool
	 */
	public $allowFtp = true;
	
	/**
	 * Tells if the URL may be a HTTPS protocol
	 * @Property
	 * @var bool
	 */
	public $allowHttps = true;
	
	
	/**
	 * (non-PHPdoc)
	 * @see ValidatorInterface::validate()
	 */
	function doValidate($value){
		$httpsAdd = $this->allowHttps ? "?" : "";
		$ftpAdd = $this->allowFtp ? "|ftp" : "";

		$regex = "(.*)";//TODO find a php regex (the JS one isn't supported)
		return preg_match($regex, $value);
	}

	/**
	 * (non-PHPdoc)
	 * @see JsValidatorInterface::getScript()
	 */
	function getScript(){
		$httpsAdd = $this->allowHttps ? "?" : "";
		$ftpAdd = $this->allowFtp ? "|ftp" : "";

		$regex = "/^(https$httpsAdd"."$ftpAdd):\\/\\/(((([a-z]|\\d|-|\\.|_|~|[\\u00A0-\\uD7FF\\uF900-\\uFDCF\\uFDF0-\\uFFEF])|(%[\\da-f]{2})|[!\\$&'\\(\\)\\*\\+,;=]|:)*@)?(((\\d|[1-9]\\d|1\\d\\d|2[0-4]\\d|25[0-5])\\.(\\d|[1-9]\\d|1\\d\\d|2[0-4]\\d|25[0-5])\\.(\\d|[1-9]\\d|1\\d\\d|2[0-4]\\d|25[0-5])\\.(\\d|[1-9]\\d|1\\d\\d|2[0-4]\\d|25[0-5]))|((([a-z]|\\d|[\\u00A0-\\uD7FF\\uF900-\\uFDCF\\uFDF0-\\uFFEF])|(([a-z]|\\d|[\\u00A0-\\uD7FF\\uF900-\\uFDCF\\uFDF0-\\uFFEF])([a-z]|\\d|-|\\.|_|~|[\\u00A0-\\uD7FF\\uF900-\\uFDCF\\uFDF0-\\uFFEF])*([a-z]|\\d|[\\u00A0-\\uD7FF\\uF900-\\uFDCF\\uFDF0-\\uFFEF])))\\.)+(([a-z]|[\\u00A0-\\uD7FF\\uF900-\\uFDCF\\uFDF0-\\uFFEF])|(([a-z]|[\\u00A0-\\uD7FF\\uF900-\\uFDCF\\uFDF0-\\uFFEF])([a-z]|\\d|-|\\.|_|~|[\\u00A0-\\uD7FF\\uF900-\\uFDCF\\uFDF0-\\uFFEF])*([a-z]|[\\u00A0-\\uD7FF\\uF900-\\uFDCF\\uFDF0-\\uFFEF])))\\.?)(:\\d*)?)(\\/((([a-z]|\\d|-|\\.|_|~|[\\u00A0-\\uD7FF\\uF900-\\uFDCF\\uFDF0-\\uFFEF])|(%[\\da-f]{2})|[!\\$&'\\(\\)\\*\\+,;=]|:|@)+(\\/(([a-z]|\\d|-|\\.|_|~|[\\u00A0-\\uD7FF\\uF900-\\uFDCF\\uFDF0-\\uFFEF])|(%[\\da-f]{2})|[!\\$&'\\(\\)\\*\\+,;=]|:|@)*)*)?)?(\\?((([a-z]|\\d|-|\\.|_|~|[\\u00A0-\\uD7FF\\uF900-\\uFDCF\\uFDF0-\\uFFEF])|(%[\\da-f]{2})|[!\\$&'\\(\\)\\*\\+,;=]|:|@)|[\\uE000-\\uF8FF]|\\/|\\?)*)?(\\#((([a-z]|\\d|-|\\.|_|~|[\\u00A0-\\uD7FF\\uF900-\\uFDCF\\uFDF0-\\uFFEF])|(%[\\da-f]{2})|[!\\$&'\\(\\)\\*\\+,;=]|:|@)|\\/|\\?)*)?$/i";
		return "function(value, element){
			return $regex.test(value);
		}";
	}
	
	/**
	 * (non-PHPdoc)
	 * @see JsValidatorInterface::getErrorMessage()
	 */
	function getErrorMessage(){
		return ValidatorUtils::translate("validate.url");
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