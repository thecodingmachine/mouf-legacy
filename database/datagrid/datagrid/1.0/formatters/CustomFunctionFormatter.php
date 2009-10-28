<?php
/**
 * The custom function formatter is used when a special function must be used
 * to perform the formatting.
 * It must be attached to a column in order to be activated.
 *
 * @Component
 */
class CustomFunctionFormatter implements DataColumnFormatterInterface {

	/**
	 * The name of the function to be called.
	 * The function will take one argument in parameter (the row) and return one string.
	 *
	 * Here is a sample function:
	 * 
	 * 	function myFunction($row) {
	 * 		return $row->firstname." ".$row->lastname;
	 *  }
	 * 
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $functionName;
	
	/**
	 * If any parameter is passed, the function will be a static function of the class
	 * passed in this parameter.
	 * 
	 * @Property
	 * @var string
	 */
	public $className;
	
	public function __construct($functionName=null, $className=null) {
		$this->functionName = $functionName;
		$this->className = $className;
	}
}
?>