<?php
/**
 * The null transformation formatter will replace a NULL value with the value specified in the formatter.
 *
 * @Component
 */
class NullTransformationFormatter implements FormatterInterface {

	/**
	 * The value to replace the NULL value with.
	 * 
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $value;
	
	public function __construct($value=null) {
		$this->value = $value;
	}

	/**
	 * Formats the value.
	 *
	 * @param string $value
	 */
	public function format($value) {
    	if ($value ===  null) {
    		return $this->value;
    	} else {
    		return $value;
    	}
	}

}
?>