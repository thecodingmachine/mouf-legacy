<?php

/**
 * This class represents a font that is loaded from a TTF file.
 * 
 * @author david
 * @Component
 */
class ChartTrueTypeFont implements ChartFontInterface {
	
	/**
	 * The font file, relative to the root path of the project.
	 * The path should not start with a /.
	 * Note: Special values supported: Tuffy, TuffyBold, TuffyBoldItalic, TuffyItalic
	 * 
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $file;
	
	/**
	 * The size of the font, in pixels.
	 * 
	 * @Property
	 * @Compulsory
	 * @var int
	 */
	public $size;
	
	/**
	 * Returns the Artichow font object.
	 * @return awFont
	 */
	function getAwFont() {
		return new awTTFFont($this->file, $this->size);
	}
}
?>