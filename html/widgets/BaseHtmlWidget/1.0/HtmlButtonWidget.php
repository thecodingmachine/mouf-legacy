<?php

/**
 * This class represent a simple HTML button.
 *
 * @Component
 */
class HtmlButtonWidget extends AbstractHtmlElement {
	
	/**
	 * The label of the button.
	 *
	 * @Property
	 * @var string
	 */
	public $label;
	
	/**
	 * The id to be used (if any).
	 *
	 * @Property
	 * @var string
	 */
	public $id;
	
	/**
	 * The name attribute of the button.
	 *
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $name;
	
	/**
	 * The value of the button (set if the button is clicked).
	 *
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $value = "ON";

	/**
	 * The name of the CSS class to be used (if any).
	 *
	 * @Property
	 * @var string
	 */
	public $css;
	
	/**
	 * The type of the button.
	 * 
	 * @Property
	 * @OneOf("submit","button","reset")
	 * @var string
	 */
	public $type;
		
	/**
	 * Whether the label should be internationalized or not.
	 *
	 * @Property
	 * @var boolean
	 */
	public $enableI18nLabel;
	
	/**
	 * Some HTML innern nodes, if any (rarely used...)
	 *
	 * @Property
	 * @var array<HtmlElementInterface>
	 */
	public $htmlFields = array();
	
	/**
	 * Renders the object in HTML.
	 * The Html is echoed directly into the output.
	 *
	 */
	function toHtmlElement() {
		
		echo "<button type='".$this->type."'";
		if ($this->id) {
			echo " id='".plainstring_to_htmlprotected($this->id)."'";
		}
		if ($this->name) {
			echo " name='".plainstring_to_htmlprotected($this->name)."'";
		}
		if ($this->value) {
			echo " value='".plainstring_to_htmlprotected($this->value)."'";
		}
		if ($this->css) {
			echo " class='".plainstring_to_htmlprotected($this->css)."'";
		}
		echo ">";
		if ($this->enableI18nLabel) {
			eMsg($this->label);
		} else {
			echo $this->label;
		}
		
		foreach ($this->htmlFields as $elem) {
			/* @var $elem HtmlElementInterface); */
			$elem->toHtml();
		}
		
		echo "</button>\n";
		
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