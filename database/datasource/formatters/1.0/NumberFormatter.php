<?php
/**
 * The number formatter is used to format a number in a column.
 * It must be attached to a column in order to be activated.
 *
 * @Component
 */
class NumberFormatter implements FormatterInterface {

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
	
	public function __construct($decimalSeparator=".", $thousandsSeparator=" ", $decimalPlaces=2) {
		$this->decimalSeparator = $decimalSeparator;
		$this->thousandsSeparator = $thousandsSeparator;
		$this->decimalPlaces = $decimalPlaces;
	}
	
	/**
	 * Formats the value.
	 *
	 * @param string $value
	 */
	public function format($value) {
		return number_format($value, $this->decimalPlaces, $this->decimalSeparator, $this->thousandsSeparator);
	}
	
}
?>