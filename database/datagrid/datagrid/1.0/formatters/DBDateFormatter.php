<?php
require_once 'DataColumnFormatterInterface.php';
/**
 * The link formatter is used to add links to datagrid columns.
 * It must be attached to a column in order to be activated.
 *
 * @Component
 */
class DBDateFormatter implements DataColumnFormatterInterface {

	/**
	 * The Format that should be used to render the date.
	 *
	 * @var String
	 */
	public $format;
	
	public function __construct($format = null) {
		$this->format = $format?$format:STR_DATETIME_FORMAT;
	}
	
	public function format($value){
		return date($this->format, strtotime($value));
	}	
}
?>