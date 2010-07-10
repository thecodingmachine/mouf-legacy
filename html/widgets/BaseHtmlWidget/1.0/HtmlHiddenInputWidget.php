<?php

/**
 * This class represent a simple HTML input tag for typing text.
 *
 * @Component
 */
class HtmlHiddenInputWidget implements HtmlElementInterface {

	/**
	 * The id of the attribute to be used (if any).
	 *
	 * @Property
	 * @var string
	 */
	public $id;
	
	/**
	 * The name attribute of the select box.
	 *
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $name;
	
	/**
	 * The default value to use.
	 * 
	 * @Property
	 * @var string
	 */
	public $defaultValue;
	
	/**
	 * Whether the selected value should be read directly from the request or not.
	 *
	 * @Property
	 * @var bool
	 */
	public $selectDefaultFromRequest = true;
	
	/**
	 * Renders the object in HTML.
	 * The Html is echoed directly into the output.
	 *
	 */
	function toHtml() {
		
		echo "<input type='hidden'";
		if ($this->id) {
			echo " id='".plainstring_to_htmlprotected($this->id)."'";
		}

		if ($this->selectDefaultFromRequest) {
			$defaultSelect = get($this->name, "string", false, null);
			if ($defaultSelect !== null) {
				echo " value='".plainstring_to_htmlprotected($defaultSelect)."'";
			} elseif ($this->defaultValue !== null) {
				echo " value='".plainstring_to_htmlprotected($this->defaultValue)."'";
			}
		} elseif ($this->defaultValue !== null) {
			echo " value='".plainstring_to_htmlprotected($this->defaultValue)."'";
		}
		
		echo " name='".plainstring_to_htmlprotected($this->name)."'>\n";

		echo "</input>\n";
		
		if (BaseWidgetUtils::isWidgetEditionEnabled()) {
			$manager = MoufManager::getMoufManager();
			$instanceName = $manager->findInstanceName($this);
			if ($instanceName != false) {
				echo " <a href='".ROOT_URL."mouf/mouf/displayComponent?name=".urlencode($instanceName).BaseWidgetUtils::getBackToParameter()."'>Edit hidden field: '".plainstring_to_htmlprotected($this->name)."'</a>\n";
			}
		}
		
	}
}
?>