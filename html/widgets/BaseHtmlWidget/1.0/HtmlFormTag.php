<?php

/**
 * This class represent a simple HTML form tag for submitting forms.
 * The form can of course contain many fields.
 *
 * @Component
 */
class HtmlFormTag implements HtmlElementInterface {

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
	 * @var string
	 */
	public $name;
	
	/**
	 * The method used to submit this form.
	 *
	 * @Property
	 * @Compulsory
	 * @OneOf("get", "post")
	 * @var string
	 */
	public $method;
	
	/**
	 * The URL this form will be submitted to (relative to the current page).
	 *
	 * @Property
	 * @var string
	 */
	public $action;
	
	/**
	 * The HTML fields (or any kind of HTML) this form is made of.
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
	function toHtml() {
		
		echo "<form ";
		if ($this->id) {
			echo " id='".plainstring_to_htmlprotected($this->id)."'";
		}		
		if ($this->name) {
			echo " name='".plainstring_to_htmlprotected($this->name)."'>\n";
		}
		if ($this->action) {
			echo " action='".plainstring_to_htmlprotected($this->action)."'>\n";
		} else {
			echo " action='.'";
		}
		echo " method='".plainstring_to_htmlprotected($this->method)."'>\n";

		foreach ($this->htmlFields as $elem) {
			/* @var $elem HtmlElementInterface); */
			$elem->toHtml();
		}
		
		echo "</form>\n";
		
		if (BaseWidgetUtils::isWidgetEditionEnabled()) {
			$manager = MoufManager::getMoufManager();
			$instanceName = $manager->findInstanceName($this);
			if ($instanceName != false) {
				echo " <a href='".ROOT_URL."mouf/mouf/displayComponent?name=".urlencode($instanceName).BaseWidgetUtils::getBackToParameter()."'>Edit this form ('".plainstring_to_htmlprotected($this->name)."')</a>\n";
			}
		}
		
	}
}
?>