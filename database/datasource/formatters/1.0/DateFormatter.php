<?php
/**
 * The date formatter is used to format a date in a column.
 * It must be attached to a column in order to be activated.
 *
 * @Component
 */
class DateFormatter implements FormatterInterface {

	/**
	 * The format of the source. The format uses the PHP notation (for instance: "Y-m-d").
	 * You can also enter the special value "timestamp" if the source is a timestamp.
	 * 
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $sourceFormat;
	
	/**
	 * The format of the destination. The format uses the PHP notation (for instance: "Y-m-d").
	 * Please note that if the $useI18nForDest variable is set to true, then instead of specifying
	 * the format, you must put the key to a label that contains the format.
	 * This is useful to correctly render the date depending on the locale. 
	 * 
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $destFormat;
	
	/**
	 * If set to true, the $destFormat property must contain a key to a label that contains the format.
	 * This is useful to correctly render the date depending on the locale. 
	 * 
	 * @Property
	 * @var bool
	 */
	public $useI18nForDest;
	
	public function __construct($sourceFormat=null, $destFormat=null, $useI18nForDest=null) {
		$this->sourceFormat = $sourceFormat;
		$this->destFormat = $destFormat;
		$this->useI18nForDest = $useI18nForDest;
	}

	/**
	 * Returns the real dest format (using translation if necessary). 
	 *
	 * @return string
	 */
	public function getDestFormat() {
		if ($this->useI18nForDest) {
			return iMsg($this->destFormat);
		} else {
			return $this->destFormat;
		}
	}
	
	/**
	 * Formats the value.
	 *
	 * @param string $value
	 */
	public function format($value) {
    	if ($this->sourceFormat == "timestamp") {
    		return date($this->getDestFormat(), $value);
    	} else {
    		$dateTime = DateTime::createFromFormat($this->sourceFormat, $value);
    		
    		if ($dateTime != null) {
    			return $dateTime->format($this->getDestFormat());
    		} else {
    			return null;
    		}
    	}
		
	}

}
?>