<?php

/**
 * This class represent a simple HTML div.
 * The div can of course contain any element.
 *
 * @Component
 */
class HtmlDivTag extends AbstractHtmlElement {

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
	 * The CSS classes to apply to the div (if any).
	 * 
	 * @Property
	 * @var string
	 */
	public $cssClass;
	
	/**
	 * The HTML elements this div is made of.
	 *
	 * @Property
	 * @var array<HtmlElementInterface>
	 */
	public $content = array();
	
	/**
	 * Renders the object in HTML.
	 * The Html is echoed directly into the output.
	 *
	 */
	function toHtmlElement() {
		
		if ($this->displayCondition != null && !$this->displayCondition->isOk($this)) {
			return "";
		}
		
		echo "<div ";
		
		if ($this->id) {
			echo " id='".plainstring_to_htmlprotected($this->id)."'";
		}
		if ($this->name) {
			echo " name='".plainstring_to_htmlprotected($this->name)."' ";
		}
		if ($this->cssClass) {
			echo " class='".plainstring_to_htmlprotected($this->cssClass)."' ";
		}
		echo ">\n";
		
		foreach ($this->content as $elem) {
			/* @var $elem HtmlElementInterface); */
			$elem->toHtml();
		}
		echo "</div>";
						
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