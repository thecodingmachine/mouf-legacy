<?php

/**
 * This class is used to start a Drupal session when we arenot in the Drupal scope.
 * 
 * @author David Negrier
 * @Component
 */
class DrupalSessionManager implements SessionManagerInterface {
	
	/**
	 * Starts the session.
	 *
	 * @see session_start
	 * @return bool
	 */
	public function start() {
		if (isset($_SESSION)) {
			return false;
		}

		// Let's lie to Drupal and pretend it started the app
		$oldScriptName = $_SERVER['SCRIPT_NAME'];
		$_SERVER['SCRIPT_NAME'] = ROOT_URL.'/index.php';
		
		$olddir = getcwd();
		chdir(dirname(__FILE__)."/../../../../../");
		
		define('DRUPAL_ROOT', getcwd());
		require_once dirname(__FILE__)."/../../../../../includes/bootstrap.inc";
		drupal_bootstrap(DRUPAL_BOOTSTRAP_SESSION);
		
		chdir($olddir);
		$_SERVER['SCRIPT_NAME'] = $oldScriptName;
	}
	
	/**
	 * Writes and closes the session
	 *
	 * @see session_write_close
	 */
	public function write_close() {
		throw new Exception("write_close is not implemented in DrupalSessionManager");
	}
	
	/**
	 * Destroys the session
	 *
	 * @see session_destroy
	 * @return bool
	 */
	public function destroy() {
		throw new Exception("destroy is not implemented in DrupalSessionManager");
	}
}