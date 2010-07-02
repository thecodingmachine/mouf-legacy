<?php

/**
 * This class represent a simple HTML list (unordered ul tag or ordered ol tag).
 * The list can of course contain any element.
 *
 * @Component
 */
class HtmlListTag implements HtmlElementInterface {

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
	 * The HTML fields (or any kind of HTML) this form is made of.
	 *
	 * @Property
	 * @var array<HtmlElementInterface>
	 */
	public $htmlFields = array();
	
	/**
	 * The kind of list to display (can be ordered or unordered)
	 *
	 * @Property
	 * @OneOf("ul","ol")
	 * @OneOfText("Unordered","Ordered")
	 * @var string
	 */
	public $layoutMode = "ul";
	
	/**
	 * Renders the object in HTML.
	 * The Html is echoed directly into the output.
	 *
	 */
	function toHtml() {
		
		if ($this->layoutMode == 'ul') {
			echo "<ul ";
		} else {
			echo "<ol ";
		}
		
		if ($this->id) {
			echo " id='".plainstring_to_htmlprotected($this->id)."'";
		}
		if ($this->name) {
			echo " name='".plainstring_to_htmlprotected($this->name)."' ";
		}
		echo ">\n";
		
		foreach ($this->htmlFields as $elem) {
			/* @var $elem HtmlElementInterface); */
			echo "<li>\n";
			$elem->toHtml();
			echo "</li>\n";
		}
		if ($this->layoutMode == 'ul') {
			echo "</ul>";
		} else {
			echo "</ol>";
		}
						
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