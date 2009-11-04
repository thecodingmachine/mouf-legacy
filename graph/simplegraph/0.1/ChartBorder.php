<?php

/**
 * This class represents the border of a chart.
 * 
 * @author david
 * @Component
 */
class ChartBorder {
	
	/**
	 * The color of the border (hexadecimal format).
	 * 
	 * @Property
	 * @var string
	 */
	public $color;
	
	
	/**
	 * The style of the border.
	 * 
	 * @Property
	 * @OneOf("1", "2", "3")
	 * @OneOfText("Solid", "Dotted", "Dashed")
	 * @var int
	 */
	public $style;
	
	/**
	 * If true, the border is hidden.
	 * 
	 * @Property
	 * @var bool
	 */
	public $hide;
	
	/**
	 * Returns the artichow object representing the border.
	 * 
	 * @return awBorder
	 */
	public function getAwBorder() {
		$border = new awBorder();
		if ($this->color != null && $this->color != "") {
			$border->setColor(SimpleGraphUtils::getAwColorFromHex($this->color));
		}
		if (!empty($this->style)) {
			$border->setStyle($this->style);
		}
		
		if ($this->hide == true) {
			$border->hide();
		}
		return $border;
	}
}
?>