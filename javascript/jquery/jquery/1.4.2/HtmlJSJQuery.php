<?php

/**
 * Writes the script that includes the JQuery library.
 *
 * @Component
 */
class HtmlJSJQuery implements HtmlElementInterface {

	/**
	 * In non-conflict mode, JQuery can be used with other frameworks like Prototype, or Mootools.
	 * The $ function will be the function from the other frameworks. JQuery will be accessible through the jQuery function (instead of $).
	 * You must make sure that jQuery is the last framework loaded.
	 *
	 * @Property
	 * @var boolean
	 */
	public $noConflictMode;
	
	public function toHtml() {
		echo '<script type="text/javascript" src="'.ROOT_URL.'plugins/javascript/jquery/jquery/1.4.2/jquery-1.4.2.min.js"></script>';
		if ($this->noConflictMode) {
			echo '<script type="text/javascript">jQuery.noConflict();</script>';
		}
	}
}

?>