<?php

/**
 * This class represent a simple HTML input tag for typing text.
 *
 * @Component
 */
class SliderWidget extends AbstractHtmlInputWidget {
		
	/**
	 * Number of fields displayed
	 *
	 * @var int
	 */
	private static $count = 0;
	
	/**
	 * The default value to use.
	 * 
	 * @Property
	 * @var string
	 */
	public $defaultValue;
	
	/**
	 * The minimum value of the slider.
	 * 
	 * @Property
	 * @var int
	 */
	public $minValue;
	
	/**
	 * The maximum value of the slider.
	 * 
	 * @Property
	 * @var int
	 */
	public $maxValue;
	
	/**
	 * The increment value for the slider.
	 * 
	 * @Property
	 * @var int
	 */
	public $step;
	
	
	/**
	 * Renders the object in HTML.
	 * The Html is echoed directly into the output.
	 *
	 */
	function toHtmlElement() {
		self::$count++;
		$id = $this->id;
		if (!$id) {
			$id = "mouf_slider_".self::$count;
		}
		
		echo "<label for='".plainstring_to_htmlprotected($id)."'>\n";
		if ($this->enableI18nLabel) {
			eMsg($this->label);
		} else {
			echo $this->label;
		}
		echo "</label>\n";
		echo "<div id='".plainstring_to_htmlprotected($id)."' class='widget {$this->css}'";
		
		echo "></div>";
		
		echo "<input type='hidden'";
		echo " id='".plainstring_to_htmlprotected($id)."_value'";
	
		if ($this->disabled) {
			echo ' disabled="disabled"';
		}
		
		$defaultSelect = null;
		if ($this->selectDefaultFromRequest) {
			$defaultSelect = get($this->name, "string", false, null);
		}
		
		if ($this->disabled) {
			echo ' disabled="disabled"';
		}
		
		$myDefaultValue = $this->minValue;
		if ($defaultSelect !== null) {
			$myDefaultValue = $defaultSelect;
		} else if ($this->defaultValue) {
			$myDefaultValue = $this->defaultValue;
		}
		echo " name='".plainstring_to_htmlprotected($this->name)."' />\n";

		echo '<script type="text/javascript">jQuery(function() {
			jQuery( "#'.plainstring_to_htmlprotected($id).'" ).slider({
				value: '.$myDefaultValue.',
				min: '.$this->minValue.',
				max: '.$this->maxValue.',
				step: '.$this->step.',
				slide: function( event, ui ) {
					jQuery( "#'.plainstring_to_htmlprotected($id).'" ).val( ui.value );
				}
			});
			jQuery( "#'.plainstring_to_htmlprotected($id).'" ).val( jQuery( "#'.plainstring_to_htmlprotected($id).'_div" ).slider( "value" ) );
		});</script>';
		
		if (BaseWidgetUtils::isWidgetEditionEnabled()) {
			$manager = MoufManager::getMoufManager();
			$instanceName = $manager->findInstanceName($this);
			if ($instanceName != false) {
				echo " <a href='".ROOT_URL."mouf/mouf/displayComponent?name=".urlencode($instanceName).BaseWidgetUtils::getBackToParameter()."'>Edit</a>\n";
			}
		}
		
	}
}
?>