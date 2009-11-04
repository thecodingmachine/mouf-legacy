<?php

/**
 * This class represents a single label to be displayed.
 * 
 * @author david
 * @Component
 */
class ChartLabel {
	
	/**
	 * The text to be displayed.
	 * 
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $text;
	
	/**
	 * The font to use.
	 * 
	 * @Property
	 * @Compulsory
	 * @var ChartFontInterface
	 */
	public $font;
	
	/**
	 * The angle to use.
	 * 
	 * @Property
	 * @Compulsory
	 * @OneOf("0", "90")
	 * @var int
	 */
	public $angle;
	
	/**
	 * The color of the text (hexadecimal format).
	 * 
	 * @Property
	 * @var string
	 */
	public $color;
	
	/**
	 * The background color of the text (hexadecimal format).
	 * 
	 * @Property
	 * @var string
	 */
	public $backgroundColor;
	
	/**
	 * Left padding, in pixels.
	 * 
	 * @Property
	 * @var int
	 */
	public $paddingLeft = 5;
	
	/**
	 * Top padding, in pixels.
	 * 
	 * @Property
	 * @var int
	 */
	public $paddingTop = 5;
	
	/**
	 * Right padding, in pixels.
	 * 
	 * @Property
	 * @var int
	 */
	public $paddingRight = 5;

	/**
	 * Bottom padding, in pixels.
	 * 
	 * @Property
	 * @var int
	 */
	public $paddingBottom = 5;
	
	/**
	 * Moves the text from X pixels on the X axis, compared to its default position.
	 * 
	 * @Property
	 * @var int
	 */
	public $moveX = 0;
	
	/**
	 * Moves the text from X pixels on the Y axis, compared to its default position.
	 * 
	 * @Property
	 * @var int
	 */
	public $moveY = 0;
	
	/**
	 * The horizontal alignment of the text
	 * 
	 * @Property
	 * @OneOf("1","2","3")
	 * @OneOfText("Left","Right","Center")
	 * @var int
	 */
	public $horizontalAlign;
	
	/**
	 * The vertical alignment of the text
	 * 
	 * @Property
	 * @OneOf("4","5","6")
	 * @OneOfText("Top","Bottom","Middle")
	 * @var int
	 */
	public $verticalAlign;
		
	/**
	 * If true, the text is not displayed.
	 * 
	 * @Property
	 * @var bool
	 */
	public $hide;
	
	
	
	
	/**
	 * Returns the artichow object representing the shadow.
	 * 
	 * @return awLabel
	 */
	public function getAwLabel() {
		$label = new awLabel(utf8_decode($this->text));
		if ($this->color != null && $this->color != "") {
			$label->setColor(SimpleGraphUtils::getAwColorFromHex($this->color));
		}
		if ($this->backgroundColor != null && $this->backgroundColor != "") {
			$label->setBackgroundColor(SimpleGraphUtils::getAwColorFromHex($this->backgroundColor));
		}
		if ($this->font != null) {
			$label->setFont($this->font->getAwFont());
		}
		if (!empty($this->angle)) {
			$label->setAngle($this->angle);
		}
		$label->setPadding($this->paddingLeft, $this->paddingRight, $this->paddingTop, $this->paddingBottom);
		$label->move($this->moveX, $this->moveY);
		$label->setAlign($this->horizontalAlign, $this->verticalAlign);
		
		
		return $label;
	}
}
?>