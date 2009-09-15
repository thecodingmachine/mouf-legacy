<?php

/**
 * This class can be used to login or logoff users, and get their object.
 *
 * @Component
 */
class MoufUserService implements UserServiceInterface {
	
	/**
	 * The path to the login page, relative to the root of the application.
	 *
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $loginPageUrl;
	
	/**
	 * The user DAO
	 *
	 * @Property
	 * @Compulsory
	 * @var UserDaoInterface
	 */
	public $userDao;
	
	/**
	 * The logger for this service
	 *
	 * @Property
	 * @Compulsory
	 * @var LogInterface
	 */
	public $log;
	
	/**
	 * Logs the user using the provided login and password.
	 * Returns true on success, false if the user or password is incorrect.
	 * 
	 * @param string $login
	 * @param string $password
	 * @return boolean.
	 */
	public function login($login, $password) {
		$user = $this->userDao->getUserByCredentials($login, $password);
		if ($user != null) {
			$this->log->trace("User '".$user->getLogin()."' logs in.");
			$_SESSION['MoufUserId'] = $user->getId();
			$_SESSION['MoufUserLogin'] = $user->getLogin();
			return true;
		} else {
			$this->log->trace("Identication failed for login '".$user."'");
			return false;
		}
	}
	
	/**
	 * Logs a user using a token. The token should be discarded as soon as it
	 * was used.
	 *
	 * @param string $token
	 */
	public function loginViaToken($token) {
		// TODO
	}
	
	/**
	 * Returns "true" if the user is logged, "false" otherwise.
	 *
	 * @return boolean
	 */
	public function isLogged() {
		if (isset($_SESSION['MoufUserId'])) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Redirects the user to the login page if he is not logged.
	 *
	 * @return boolean
	 */
	public function redirectNotLogged() {
		// TODO
	}
	
	/**
	 * Logs the user off.
	 *
	 */
	public function logoff() {
		$this->log->trace("User ".$_SESSION['MoufUserLogin']." logs out.");
		unset($_SESSION['MoufUserId']);
		unset($_SESSION['MoufUserLogin']);
	}
	
	/**
	 * Returns the current user ID.
	 *
	 * @return string
	 */
	public function getUserId() {
		if (isset($_SESSION['MoufUserId']))
			return $_SESSION['MoufUserId'];
		else
			return null; 
	}
	
	/**
	 * Returns the current user login.
	 *
	 * @return string
	 */
	public function getUserLogin() {
		if (isset($_SESSION['MoufUserLogin']))
			return $_SESSION['MoufUserLogin'];
		else
			return null; 
	}
	
	/**
	 * Returns the user that is logged (or null if no user is logged).
	 *
	 * return UserInterface
	 */
	public function getLoggedUser() {
		if (isset($_SESSION['MoufUserId'])) {
			return $this->userDao->getUserById($_SESSION['MoufUserId']);
		} else {
			return null;
		}
	}
	
}
?>