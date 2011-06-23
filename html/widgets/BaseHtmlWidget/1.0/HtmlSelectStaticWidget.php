<?php

/**
 * This class represent a select HTML tag.
 * The data in the select tag is directly entered in the widget.
 * Use HtmlSelectWidget if you want to bind the data to a datasource.
 *
 * @Component
 */
class HtmlSelectStaticWidget extends AbstractHtmlInputWidget {

	/**
	 * Number of fields displayed
	 *
	 * @var int
	 */
	private static $count = 0;
	
	/**
	 * The list of options to populate the select box.
	 *
	 * @Property
	 * @var array<string, string>
	 */
	public $options;
	
	/**
	 * Whether the values displayed inside the select box should be internationalized or not.
	 *
	 * @Property
	 * @var boolean
	 */
	public $enableI18nValues;
	
	/**
	 * Whether we should propose a default value that is NOT part of the datasource.
	 * This is useful to add a -- PICK ONE -- label at the beginning of the list. 
	 *
	 * @Property
	 * @var bool
	 */
	public $hasDefaultValue;
	
	/**
	 * The string displayed for the default value (the top value).
	 *
	 * @Property
	 * @var string
	 */
	public $defaultValue = "-- PICK ONE --";
		
	/**
	 * Whether the default value should be internationalized or not.
	 * 
	 * @Property
	 * @var bool
	 */
	public $enableI18nDefaultValue;
	
	/**
	 * The selected value.
	 * This is honored except if the request contains a value.
	 * In this case, the value from the request is used instead.
	 * 
	 * @var string
	 */
	public $selectedValue;
	
	/**
	 * Renders the object in HTML.
	 * The Html is echoed directly into the output.
	 *
	 */
	function toHtmlElement() {
		self::$count++;
		$id = $this->id;
		if (!$id) {
			$id = "mouf_select_".self::$count;
		}
		
		echo "<label for='".plainstring_to_htmlprotected($id)."'>\n";
		if ($this->enableI18nLabel) {
			eMsg($this->label);
		} else {
			echo $this->label;
		}
		echo "</label>\n";
		
		echo "<select";
		echo " id='".plainstring_to_htmlprotected($id)."'";
		if ($this->css || $this->required) {
			$requiredClass = "";
			if ($this->required) {
				$requiredClass = "validate[required] ";
			}
			echo " class='".$requiredClass.plainstring_to_htmlprotected($this->css)."'";
		}
	
		if ($this->disabled) {
			echo ' disabled="disabled"';
		}
		
		echo " name='".plainstring_to_htmlprotected($this->name)."'>\n";

		if ($this->hasDefaultValue) {
			//echo "<option value='".plainstring_to_htmlprotected($this->defaultKey)."'>";
			echo "<option value=''>";
			if ($this->enableI18nDefaultValue) {
				echo plainstring_to_htmlprotected(iMsg($this->defaultValue));
				
			} else {
				echo plainstring_to_htmlprotected($this->defaultValue);
			}
			echo "</option>\n";
		}
		
		// Let's start by translating the content, if needed.
		$values = array();
		foreach ($this->options as $key=>$value) {
			if ($this->enableI18nValues) {
				$values[$key] = iMsg($value);
			} else {
				$values[$key] = $value;
			}
		}
		
		$defaultSelect = null;
		if ($this->selectDefaultFromRequest) {
			$defaultSelect = get($this->name, "string", false, null);
		}
		if ($defaultSelect == null && $this->selectedValue != null) {
			$defaultSelect = $this->selectedValue;
		}
	
		foreach ($values as $key=>$value) {
			echo "<option value='".plainstring_to_htmlprotected($key)."'";
			if ($defaultSelect != null && $key == $defaultSelect) {
				echo " selected='selected' ";	
			}
			echo ">";
			echo plainstring_to_htmlprotected($value);
			echo "</option>\n";
		}
		
		echo "</select>\n";
		
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