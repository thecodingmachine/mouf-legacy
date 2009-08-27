<?php
/**
 * A utility class to access data in the session.
 */
class SessionUtils {

	public static $ADMINGROUPID=1;
	public static $USERGROUPID=2;

	/**
	 * Sets the user's login
	 */
	public static function setUserLogin($login) {
		$_SESSION[SESSION_NAME]['user']['__login'] = $login;
	}

	/**
	 * Returns the user's login
	 */
	public static function getUserLogin() {
		return $_SESSION[SESSION_NAME]['user']['__login'];
	}

	/**
	 * Sets the user's id
	 */
	public static function setUserId($id) {
		$_SESSION[SESSION_NAME]['user']['__userid'] = $id;
	}

	/**
	 * Returns the user's id
	 */
	public static function getUserId() {
		return $_SESSION[SESSION_NAME]['user']['__userid'];
	}

	/**
	 * Sets the users's group id
	 */
	public static function setGroupId($id) {
		$_SESSION[SESSION_NAME]['user']['__groupid'] = $id;
	}

	/**
	 * Returns the user's id
	 */
	public static function getGroupId() {
		return $_SESSION[SESSION_NAME]['user']['__groupid'];
	}

	/**
	 * Returns true if the user is admin, false otherwise.
	 *
	 * @return bool
	 */
	public static function isAdmin() {
		return $_SESSION[SESSION_NAME]['user']['__groupid'] == self::$ADMINGROUPID;
	}

	/**
	 * Sets the users's account id
	 */
	public static function setAccountId($id) {
		$_SESSION[SESSION_NAME]['user']['__accountid'] = $id;
	}

	/**
	 * Returns the user's account id
	 */
	public static function getAccountId() {
		return $_SESSION[SESSION_NAME]['user']['__accountid'];
	}

	/**
	 * @return bool True if a user is logged, false otherwise.
	 */
	public static function isLogged() {
		return isset($_SESSION[SESSION_NAME]['user']['__login']) && $_SESSION[SESSION_NAME]['user']['__login']!="";
	}

	/**
	 * @return bool Returns true if the user has logged via a token
	 */
	public static function isTokenLogin() {
		return $_SESSION[SESSION_NAME]['user']['__tokenlogin'];
	}

	/**
	 * Set to true if the user has logged via a token
	 */
	public static function setTokenLogin($isTokenLogin) {
		$_SESSION[SESSION_NAME]['user']['__tokenlogin'] = $isTokenLogin;
	}

	/**
	 * Supress the session, thereby disconnecting the user.
	 */
	public static function logout() {
		unset($_SESSION[SESSION_NAME]['user']);
	}

	/**
	 * Returns the debug mode (stored in session)
	 *
	 * @return boolean
	 */
	public static function isDebugMode() {
		if(isset($_SESSION[SESSION_NAME]['debug_mode'])){
			$debug_mode = $_SESSION[SESSION_NAME]['debug_mode'];
		}else {
			$debug_mode = DEBUG_MODE;
		}
		return $debug_mode;
	}

	/**
	 * Sets the debug mode (stored in session)
	 *
	 * @param boolean $debug_mode
	 * @return boolean
	 */
	public static function setDebugMode($debug_mode) {
		return $_SESSION[SESSION_NAME]['debug_mode']=$debug_mode;
	}

	/**
	 * Returns the debug mode (stored in session)
	 *
	 * @return boolean
	 */
	public static function isMessageEditionMode() {
		if(isset($_SESSION[SESSION_NAME]['message_mode'])){
			return $_SESSION[SESSION_NAME]['message_mode'];
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
		return $_SESSION[SESSION_NAME]['message_mode']=$message_edit;
	}

	//sets the redirect Url after login
	public static function setRedirectUrl($redirectUrl){
		$_SESSION[SESSION_NAME]['__redirect'] = $redirectUrl;
	}

}


?>