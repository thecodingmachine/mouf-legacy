<?php

/**
 * Writes the script that includes the JQuery-Tools UI library.
 * Warning! The JQuery library must be inserted before this library!
 *
 * @Component
 */
class HtmlJSJQueryToolsUI implements HtmlElementInterface {

	public function toHtml() {
		echo '<script type="text/javascript" src="'.ROOT_URL.'plugins/javascript/jquery/jquery-tools/1.2.3/js/jquery.tools.ui.min.js"></script>'."\n";
	}
}

?>