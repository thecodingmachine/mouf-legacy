<?php

/**
 * This class represent a color picker.
 *
 * @Component
 */
class MiniColorsPickerWidget extends AbstractHtmlInputWidget {
		
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
	 * Renders the object in HTML.
	 * The Html is echoed directly into the output.
	 *
	 */
	function toHtmlElement() {
		self::$count++;
		$id = $this->id;
		if (!$id) {
			$id = "mouf_colorpicker_".self::$count;
		}
		
		echo "<label for='".plainstring_to_htmlprotected($id)."'>\n";
		if ($this->enableI18nLabel) {
			eMsg($this->label);
		} else {
			echo $this->label;
		}
		echo "</label>\n";
		
		$defaultSelect = $this->defaultValue;
		if ($this->selectDefaultFromRequest) {
			$select = get($this->name, "string", false, null);
			if ($select) {
				$defaultSelect = trim($select, "#");
			}
		}
		if (empty($defaultSelect)) {
			$defaultSelect = "ffffff";
		}
		
		echo "<input type='text'";
		echo " id='".plainstring_to_htmlprotected($id)."'";
		echo " value='#$defaultSelect'";
		echo " name='".plainstring_to_htmlprotected($this->name)."' />\n";
		
		echo '<script type="text/javascript">jQuery(function() {
			jQuery( "#'.plainstring_to_htmlprotected($id).'" ).miniColors();
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