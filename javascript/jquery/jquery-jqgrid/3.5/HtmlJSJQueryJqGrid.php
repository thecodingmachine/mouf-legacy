<?php

/**
 * Writes the script that includes the JQuery-jqGrid library.
 * Warning! The JQuery library and JQuery UI library must be inserted before this library!
 *
 * @Component
 */
class HtmlJSJQueryJqGrid implements HtmlElementInterface {

	public function toHtml() {
		// TODO: select right language automatically
		echo '<link rel="stylesheet" type="text/css" media="screen" href="'.ROOT_URL.'plugins/javascript/jquery/jquery-jqgrid/3.5/css/ui.jqgrid.css" media="screen" />'."\n";
		echo '<script type="text/javascript" src="'.ROOT_URL.'plugins/javascript/jquery/jquery-jqgrid/3.5/js/i18n/grid.locale-en.js"></script>'."\n";
		echo '<script type="text/javascript" src="'.ROOT_URL.'plugins/javascript/jquery/jquery-jqgrid/3.5/js/jquery.jqGrid.min.js"></script>'."\n";
	}
}

?>