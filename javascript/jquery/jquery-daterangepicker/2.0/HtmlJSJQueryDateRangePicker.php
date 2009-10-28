<?php

/**
 * Writes the script that includes the JQuery-daterangepicker library.
 * Warning! The JQuery library and JQuery-UI must be inserted before this library!
 *
 * @Component
 */
class HtmlJSJQueryDateRangePicker implements HtmlElementInterface {

	public function toHtml() {
		echo '<link type="text/css" href="'.ROOT_URL.'plugins/javascript/jquery/jquery-daterangepicker/2.0/css/ui.daterangepicker.css" rel="stylesheet" />'."\n";
		echo '<script type="text/javascript" src="'.ROOT_URL.'plugins/javascript/jquery/jquery-daterangepicker/2.0/js/daterangepicker.jQuery.js"></script>'."\n";
	}
}

?>