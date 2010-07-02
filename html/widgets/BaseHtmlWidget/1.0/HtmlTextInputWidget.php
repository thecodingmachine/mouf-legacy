<?php

/**
 * This class represent a simple HTML input tag for typing text.
 *
 * @Component
 */
class HtmlTextInputWidget extends AbstractHtmlInputWidget {
		
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
	function toHtml() {
		self::$count++;
		$id = $this->id;
		if (!$id) {
			$id = "mouf_textinput_".self::$count;
		}
		
		echo "<label for='".plainstring_to_htmlprotected($id)."'>\n";
		if ($this->enableI18nLabel) {
			eMsg($this->label);
		} else {
			echo $this->label;
		}
		echo "</label>\n";
		echo "<input type='text'";
		echo " id='".plainstring_to_htmlprotected($id)."'";

		if ($this->css || $this->required) {
			$requiredClass = "";
			if ($this->required) {
				$requiredClass = "validate[required] ";
			}
			echo " class='".$requiredClass.plainstring_to_htmlprotected($this->css)."'";
		}

		$defaultSelect = null;
		if ($this->selectDefaultFromRequest) {
			$defaultSelect = get($this->name, "string", false, null);
		}
		
		if ($defaultSelect !== null) {
			echo " value='".plainstring_to_htmlprotected($defaultSelect)."'";
		} else if ($this->defaultValue) {
			echo " value='".plainstring_to_htmlprotected($this->defaultValue)."'";
		}
		echo " name='".plainstring_to_htmlprotected($this->name)."'>\n";

		echo "</input>\n";
		
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