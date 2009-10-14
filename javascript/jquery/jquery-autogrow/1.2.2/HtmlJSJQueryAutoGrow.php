<?php

/**
 * Writes the script that includes the JQuery-autogrow library.
 * Warning! The JQuery library must be inserted before this library!
 *
 * @Component
 */
class HtmlJSJQueryAutoGrow implements HtmlElementInterface {

	public function toHtml() {
		echo '<script type="text/javascript" src="'.ROOT_URL.'plugins/javascript/jquery/jquery-autogrow/1.2.2/jquery.autogrow.js"></script>';
	}
}

?>