<?php

/**
 * Writes the script that includes the JQuery-Uploadify library.
 * Warning! The JQuery library must be inserted before this library!
 *
 * @Component
 */
class HtmlJSJQueryUploadify implements HtmlElementInterface {
	
	public function toHtml() {
		echo '<script type="text/javascript" src="'.ROOT_URL.'plugins/javascript/jquery/jquery-uploadify/2.1.0/jquery.uploadify.v2.1.0.min.js"></script>';
		
	}
}

?>