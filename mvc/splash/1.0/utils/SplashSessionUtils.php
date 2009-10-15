<?php
/**
 * A utility class to access data stored by Splash in the session.
 */
class SplashSessionUtils {

	/**
	 * Returns the debug mode (stored in session)
	 *
	 * @return boolean
	 */
	public static function isDebugMode() {
		if(isset($_SESSION['splash']['debug_mode'])){
			$debug_mode = $_SESSION['splash']['debug_mode'];
		}else {
			$debug_mode = MoufManager::getMoufManager()->getInstance("splash");
		}
		return MoufManager::getMoufManager()->getInstance("splash");
	}

	/**
	 * Sets the debug mode (stored in session)
	 *
	 * @param boolean $debug_mode
	 * @return boolean
	 */
	public static function setDebugMode($debug_mode) {
		return $_SESSION['splash']['debug_mode']=$debug_mode;
	}

	/**
	 * Returns the debug mode (stored in session)
	 *
	 * @return boolean
	 */
	public static function isMessageEditionMode() {
		if(isset($_SESSION['splash']['message_mode'])){
			return $_SESSION['splash']['message_mode'];
		}else {
			return false;
		}
	}

	/**
	 * Sets the message edition mode (stored in session)
	 *
	 * @param boolean $message_edit
	 * @return boolean
	 */
	public static function setMessageEditionMode($message_edit) {
		return $_SESSION['splash']['message_mode']=$message_edit;
	}

}


?>