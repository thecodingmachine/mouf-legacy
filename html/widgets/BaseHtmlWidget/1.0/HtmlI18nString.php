<?php
/**
 * An Html string that can be embedded in any container accepting HtmlElements.
 *
 * @Component
 */
class HtmlI18nString extends AbstractHtmlElement {
	
	/**
	 * The name of the HTML i18n string that will be embedded in the container.
	 *
	 * @Property
	 * @Compulsory 
	 * @var string
	 */
	public $htmlString;
	
	public function toHtmlElement() {
		eMsg($this->htmlString); 
	}
}
?>