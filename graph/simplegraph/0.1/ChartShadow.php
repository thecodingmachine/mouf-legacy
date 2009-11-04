<?php

/**
 * This class represents the shadow effect of a chart.
 * 
 * @author david
 * @Component
 */
class ChartShadow {
	
	/**
	 * The position of the shadow.
	 * This is compulsory and will default to Right-bottom.
	 * 
	 * @Property
	 * @Compulsory
	 * @OneOf("1", "2", "3", "4")
	 * @OneOfText("Left-top", "Left-bottom", "Right-top", "Right-bottom")
	 * @var string
	 */
	public $position = 4;
	
	
	/**
	 * The size of the shadow.
	 * 
	 * @Property
	 * @var string
	 */
	public $size;
	
	/**
	 * The color of the shadow (hexadecimal format).
	 * 
	 * @Property
	 * @var string
	 */
	public $color;
		
	/**
	 * If true, the shadow is not displayed.
	 * 
	 * @Property
	 * @var bool
	 */
	public $hide;
	
	/**
	 * If true, the shadow is smoothed.
	 * 
	 * @Property
	 * @var bool
	 */
	public $smooth;
	
	/**
	 * Returns the artichow object representing the shadow.
	 * 
	 * @return awShadow
	 */
	public function getAwShadow() {
		$shadow = new awShadow($this->position);
		if ($this->color != null && $this->color != "") {
			$shadow->setColor(SimpleGraphUtils::getAwColorFromHex($this->color));
		}
		if (!empty($this->size)) {
			$shadow->setSize($this->size);
		}
		if ($this->hide == true) {
			$shadow->hide();
		}
		if ($this->smooth === true) {
			$shadow->smooth(true);
		} elseif ($this->smooth === false) {
			$shadow->smooth(false);
		}
		
		return $shadow;
	}
}
?>