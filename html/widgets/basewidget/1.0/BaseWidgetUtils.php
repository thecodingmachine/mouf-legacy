<?php

/**
 * Class to enable or disable the widget edition mode for the application.
 *
 */
class BaseWidgetUtils {
	
	/**
	 * Enables widget edition.
	 * This will add an "edit" link close to all widget that will enable widget edition.
	 *
	 */
	public static function enableWidgetEdition() {
		$_SESSION["WIDGET_EDITION_MODE"] = true;
	}
	
	/**
	 * Disables widget edition.
	 *
	 */
	public static function disableWidgetEdition() {
		unset($_SESSION["WIDGET_EDITION_MODE"]);
	}
	
	/**
	 * Returns true if widget edition is enabled.
	 *
	 * @return bool
	 */
	public static function isWidgetEditionEnabled() {
		if (!isset($_SESSION["WIDGET_EDITION_MODE"]))
			return false;
		else 
			return $_SESSION["WIDGET_EDITION_MODE"];
	}	
}
?>