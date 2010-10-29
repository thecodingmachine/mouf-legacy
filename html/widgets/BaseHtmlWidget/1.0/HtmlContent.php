<?php

/**
 * This class represents a simple container that can contain more
 * HTML elements.
 *
 * @Component
 */
class HtmlContent extends AbstractHtmlElement {

	/**
	 * The HTML elements.
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
		
		foreach ($this->content as $elem) {
			/* @var $elem HtmlElementInterface); */
			$elem->toHtml();
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