<?php
/**
 * The currency formatter is used to format a currency in a column.
 * It must be attached to a column in order to be activated.
 * It extends the NumberFormatter and adds a prefix or a suffix.
 * 
 * @Component
 */
class CurrencyFormatter extends NumberFormatter {

	/**
	 * The prefix for the currency ($, €, ...)
	 * 
	 * @Property
	 * @var string
	 */
	public $prefix;
	
	/**
	 * The suffix for the currency ($, €, ...)
	 * 
	 * @Property
	 * @var string
	 */
	public $suffix;
	
	public function __construct($decimalSeparator=".", $thousandsSeparator=" ", $decimalPlaces=2, $defaultValue=null, $prefix=null, $suffix=null) {
		parent::__construct($decimalSeparator, $thousandsSeparator, $decimalPlaces, $defaultValue);
		$this->prefix = $prefix;
		$this->suffix = $suffix;
	}
}
?>