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
	 * The layout for the form elements.
	 * In "list mode", every HTML element in this list will be wrapped in a OL-LI HTML list.
	 * Use "list mode" is the form widgets are directly at the root of the widget.
	 *
	 * @Property
	 * @OneOf("none","list")
	 * @OneOfText("No layout","List layout (using li tags)")
	 * @var string
	 */
	public $layoutMode;
	
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
			echo " action='".plainstring_to_htmlprotected($_SERVER['REQUEST_URI'])."'";
		}
		echo " method='".plainstring_to_htmlprotected($this->method)."'>\n";

		if ($this->layoutMode == 'list') {
			echo "<ol>\n";
		}
		foreach ($this->htmlFields as $elem) {
			/* @var $elem HtmlElementInterface); */
			if ($this->layoutMode == 'list') {
				echo "<li>\n";
			}
			$elem->toHtml();
			if ($this->layoutMode == 'list') {
				echo "</li>\n";
			}
		}
		if ($this->layoutMode == 'list') {
			echo "</ol>\n";
		}
		
		echo "</form>\n";
		
		if (BaseWidgetUtils::isWidgetEditionEnabled()) {
			$manager = MoufManager::getMoufManager();
			$instanceName = $manager->findInstanceName($this);
			if ($instanceName != false) {
				echo " <a href='".ROOT_URL."mouf/mouf/displayComponent?name=".urlencode($instanceName).BaseWidgetUtils::getBackToParameter()."'>Edit this form ('".plainstring_to_htmlprotected($instanceName)."')</a>\n";
			}
		}
		
	}
}
?>