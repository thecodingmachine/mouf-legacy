<?php
/**
 * The number formatter is used to format a number in a column.
 * It must be attached to a column in order to be activated.
 *
 * @Component
 */
class NumberFormatter implements DataColumnFormatterInterface {

	/**
	 * Determines the separator for the decimals.
	 * 
	 * @Property
	 * @var string
	 */
	public $decimalSeparator;
	
	/**
	 * Determines the separator for the thousands.
	 * 
	 * @Property
	 * @var string
	 */
	public $thousandsSeparator;
	
	/**
	 * Determines how many decimal places we should have for the number
	 * 
	 * @Property
	 * @var string
	 */
	public $decimalPlaces;
	
	/**
	 * Sets the default value if nothing in the data
	 * 
	 * @Property
	 * @var string
	 */
	public $defaultValue;
	
	public function __construct($decimalSeparator=".", $thousandsSeparator=" ", $decimalPlaces=2) {
		$this->decimalSeparator = $decimalSeparator;
		$this->thousandsSeparator = $thousandsSeparator;
		$this->decimalPlaces = $decimalPlaces;
		$this->defaultValue = $defaultValue;
	}
}
?>