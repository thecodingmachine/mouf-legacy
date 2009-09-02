<?php

/**
 * Writes the script that includes the JQuery-FileTree library.
 * Warning! The JQuery library must be inserted before this library!
 *
 * @Component
 */
class HtmlJSJQueryFileTree implements HtmlElementInterface {

	public function toHtml() {
		echo '<link type="text/css" href="'.ROOT_URL.'plugins/javascript/jquery/jqueryFileTree/1.01/jqueryFileTree.css" rel="stylesheet" />';
		echo '<script type="text/javascript" src="'.ROOT_URL.'plugins/javascript/jquery/jqueryFileTree/1.01/jqueryFileTree.js"></script>';		
	}
}

?>