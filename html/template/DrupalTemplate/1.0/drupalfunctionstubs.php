<?php

if (!function_exists("t")) {
	/**
	 * Replacement for the "t" function in Drupal.
	 *
	 */
	function t($label, $paramsArr=null, $langCode = null) {
		// Ignoring $paramsArr and $langCode
		return iMsg($label);
	}
}
?>