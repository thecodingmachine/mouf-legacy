<?php

/**
 * This class represent a simple HTML input tag for typing text.
 *
 * @Component
 */
class HtmlTextInputWidget extends AbstractHtmlInputWidget {
		
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
		
		echo "<label>\n";
		if ($this->enableI18nLabel) {
			eMsg($this->label);
		} else {
			echo $this->label;
		}
		echo "<input type='text'";
		if ($this->id) {
			echo " id='".plainstring_to_htmlprotected($this->id)."'";
		}
		if ($this->css || $this->required) {
			$requiredClass = "";
			if ($this->required) {
				$requiredClass = "required ";
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
		echo "</label>\n";
		
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