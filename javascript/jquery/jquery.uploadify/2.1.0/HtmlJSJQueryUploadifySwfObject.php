<?php

/**
 * Writes the script that includes the JQuery-Uploadify library.
 * Warning! The JQuery library must be inserted before this library!
 *
 * @Component
 */
class HtmlJSJQueryUploadifySwfObject implements HtmlElementInterface {
	
	public function toHtml() {
		echo '<script type="text/javascript" src="'.ROOT_URL.'plugins/javascript/jquery/jquery.uploadify/2.1.0/swfobject.js"></script>';
		
	}
}

?>