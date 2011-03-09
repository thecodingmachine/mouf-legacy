<?php

/**
 * Writes the script that includes the JQuery-FixedHeaderTable library.
 * This library can be used to fix the head table position in each html table
 * Warning! The JQuery library must be inserted before this library!
 *
 * @Component
 */
class HtmlJSJQueryFixedHeaderTable implements HtmlElementInterface {
	
	
	public function toHtml() {
		echo '<script type="text/javascript" src="'.ROOT_URL.'plugins/javascript/jquery/jquery-fixedheadertable/1.3/js/jquery-fixedheadertable.min.js"></script>';
		
	}
}

?>