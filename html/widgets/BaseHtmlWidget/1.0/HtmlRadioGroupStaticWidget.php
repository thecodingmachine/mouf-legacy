<?php

/**
 * This class represent a group of radio buttons.
 * The data in the select tag is directly entered in the widget.
 * Use HtmlSelectWidget if you want to bind the data to a datasource.
 *
 * @Component
 */
class HtmlRadioGroupStaticWidget extends AbstractHtmlInputWidget {
		
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
	 * If the labels are internationalized, this prefix will be applied before internationalization.
	 *
	 * @Property
	 * @var string
	 */
	public $i18nValuesPrefix;
		
	/**
	 * Whether to sort the values or not
	 *
	 * @Property
	 * @var boolean
	 */
	public $sort = true;
	
	/**
	 * If this is set, the list of all radio buttons will be wrapped into a div with the class set.
	 *
	 * @Property
	 * @var string
	 */
	public $radioBoxListContainerCss;
	
	/**
	 * If this is set, each radio button will be wrapped into a div with the class set.
	 *
	 * @Property
	 * @var string
	 */
	public $radioButtonContainerCss;
	
	/**
	 * If this is set, each radio button label will be wrapped into a span with the class set.
	 *
	 * @Property
	 * @var string
	 */
	public $radioButtonLabelCss;
	
	
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
		
		echo "<label>\n";
		if ($this->enableI18nLabel) {
			eMsg($this->label);
		} else {
			echo $this->label;
		}
		echo "</label>\n";
		
				
		// Let's start by translating the content, if needed.
		$values = array();
		foreach ($this->options as $key=>$value) {
			if ($this->enableI18nValues) {
				$values[$key] = iMsg($this->i18nValuesPrefix.$value);
			} else {
				$values[$key] = $value;
			}
		}
		
		if ($this->sort) {
			asort($values);
		}
		
		$defaultSelect = $this->defaultValue;
		if ($this->selectDefaultFromRequest) {
			$selectValue = get($this->name);
			if ($selectValue != null) {
				$defaultSelect = $selectValue;
			}
		}
		
		if ($this->radioBoxListContainerCss) {
				echo "<div class='".$this->radioBoxListContainerCss."'>\n";
		}
		
		foreach ($values as $key=>$value) {
			if ($this->radioButtonContainerCss) {
				echo "<div class='".$this->radioButtonContainerCss."'>\n";
			}
			
			echo "<input type='radio' name='".plainstring_to_htmlprotected($this->name)."' value='".plainstring_to_htmlprotected($key)."'";
			if ($this->css) {
				echo " class='".plainstring_to_htmlprotected($this->css)."'";
			}
		
			if ($this->disabled) {
				echo ' disabled="disabled"';
			}
			
			if ($defaultSelect != false) {
				if ($key == $defaultSelect) {
					echo " checked='checked' ";	
				}
			}
			echo "/>";
			if ($this->css) {
				echo "<span class='".plainstring_to_htmlprotected($this->radioButtonLabelCss)."'>".plainstring_to_htmlprotected($value)."</span>";
			} else {
				echo plainstring_to_htmlprotected($value);
			}
			if ($this->radioButtonContainerCss) {
				echo "</div>\n";
			}
		}
		
		if ($this->radioBoxListContainerCss) {
			echo "</div>\n";
		}
	
		
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