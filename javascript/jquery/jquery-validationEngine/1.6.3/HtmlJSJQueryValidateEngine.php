<?php

/**
 * Writes the script that includes the JQuery-validatengine library.
 * Warning! The JQuery library must be inserted before this library!
 *
 * @Component
 */
class HtmlJSJQueryValidateEngine implements HtmlElementInterface {

	public function toHtml() {
		global $i18n_lg;
		
		echo '<link rel="stylesheet" type="text/css" media="screen" href="'.ROOT_URL.'plugins/javascript/jquery/jquery-validationengine/1.6.3/css/validationEngine.jquery.css" media="screen" />'."\n";
		
		if ($i18n_lg == "fr") {
			echo '<script type="text/javascript" src="'.ROOT_URL.'plugins/javascript/jquery/jquery-validationengine/1.6.3/js/jquery.validationEngine-fr.js"></script>'."\n";
		} else {
			echo '<script type="text/javascript" src="'.ROOT_URL.'plugins/javascript/jquery/jquery-validationengine/1.6.3/js/jquery.validationEngine-en.js"></script>'."\n";
		}
		echo '<script type="text/javascript" src="'.ROOT_URL.'plugins/javascript/jquery/jquery-validationengine/1.6.3/js/jquery.validationEngine.js"></script>'."\n";
	}
}

?>