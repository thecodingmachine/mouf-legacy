<?php

/**
 * Writes the script that includes the JQuery-UI library.
 * Warning! The JQuery library must be inserted before this library!
 *
 * @Component
 */
class HtmlJSJQueryUI implements HtmlElementInterface {

	/**
	 * If set to true, the default CSS (lightness) will be included with the Javascript.
	 * 
	 * @Property
	 * @Compulsory
	 * @var bool
	 */
	public $includeDefaultCss;
	
	public function toHtml() {
		if ($this->includeDefaultCss) {
			echo '<link type="text/css" href="'.ROOT_URL.'plugins/javascript/jquery/jquery-ui/1.8.2/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" media="screen" />'."\n";
		}
		echo '<script type="text/javascript" src="'.ROOT_URL.'plugins/javascript/jquery/jquery-ui/1.8.2/js/jquery-ui-1.8.2.custom.min.js"></script>';
		
	}
}

?>