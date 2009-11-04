<?php

/**
 * This class represents one of the 5 standard fonts bundled with PHP.
 * 
 * @author david
 * @Component
 */
class ChartStandardFont implements ChartFontInterface {
	
	/**
	 * The number of the standard font.
	 * 
	 * @Property
	 * @Compulsory
	 * @OneOf("1", "2", "3", "4", "5")
	 * @var string
	 */
	public $fontNumber = 1;
	
	/**
	 * Returns the Artichow font object.
	 * @return awFont
	 */
	function getAwFont() {
		return new awPHPFont($this->fontNumber);
	}
}
?>