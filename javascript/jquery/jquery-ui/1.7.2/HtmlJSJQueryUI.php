<?php

/**
 * Writes the script that includes the JQuery-UI library.
 * Warning! The JQuery library must be inserted before this library!
 *
 * @Component
 */
class HtmlJSJQueryUI implements HtmlElementInterface {

	public function toHtml() {
		echo '<link type="text/css" href="'.ROOT_URL.'plugins/javascript/jquery/jquery-ui/1.7.2/css/ui-lightness/jquery-ui-1.7.2.custom.css" rel="stylesheet" />';
		echo '<script type="text/javascript" src="'.ROOT_URL.'plugins/javascript/jquery/jquery-ui/1.7.2/js/jquery-ui-1.7.2.custom.min.js"></script>';
		
	}
}

?>