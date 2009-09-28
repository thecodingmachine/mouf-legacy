<?php

/**
 * Writes the script that includes the JQuery-validate library.
 * Warning! The JQuery library must be inserted before this library!
 *
 * @Component
 */
class HtmlJSJQueryValidate implements HtmlElementInterface {

	public function toHtml() {
		echo '<script type="text/javascript" src="'.ROOT_URL.'plugins/javascript/jquery/jquery-validate/1.5.5/jquery.validate.js"></script>'."\n";
		
	}
}

?>