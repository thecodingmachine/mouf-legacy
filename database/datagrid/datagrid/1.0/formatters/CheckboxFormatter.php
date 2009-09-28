<?php
/**
 * The checkbox formatter is used to display checkboxes in columns instead of 0/1 or true/false.
 * It must be attached to a column in order to be activated.
 *
 * @Component
 */
class CheckboxFormatter implements DataColumnFormatterInterface {

	/**
	 * If set to "true", the checkbox cannot be modified.
	 * Defaults is true.
	 * 
	 * @Property
	 * @var bool
	 */
	public $disabled;
	
	public function __construct($disabled=true) {
		$this->disabled = $disabled;
	}
}
?>