<?php
require_once 'HtmlElementInterface.php';

/**
 * An Html string that can be embedded in any container accepting HtmlElements.
 *
 * @Component
 */
class HtmlString implements HtmlElementInterface {
	
	/**
	 * The HTML string that will be embedded in the container.
	 *
	 * @Property
	 * @Compulsory 
	 * @var string
	 */
	public $htmlString;
	
	public function toHtml() {
		echo $this->htmlString; 
		
	}
}
?>