<?php

/**
 * Writes the script that includes the prototype library.
 *
 * @Component
 */
class HtmlJSPrototype implements HtmlElementInterface {

	public function toHtml() {
		echo '<script type="text/javascript" src="'.ROOT_URL.'plugins/javascript/prototype/1.6.0.1/prototype.js"></script>';
	}
}

?>