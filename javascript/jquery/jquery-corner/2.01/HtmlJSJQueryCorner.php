<?php

/**
 * Writes the script that includes the JQuery-corner library.
 * Warning! The JQuery library must be inserted before this library!
 *
 * @Component
 */
class HtmlJSJQueryCorner implements HtmlElementInterface {

	public function toHtml() {
		echo '<script type="text/javascript" src="'.ROOT_URL.'plugins/javascript/jquery/jquery-corner/2.01/jquery.corner.js"></script>';
	}
}

?>