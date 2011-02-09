<?php

/**
 * This class can be used to login or logoff users, and get their object.
 * Please see documentation at: <a href="http://www.thecodingmachine.com/ext/mouf/doc/manage_users/userservice_package.html">http://www.thecodingmachine.com/ext/mouf/doc/manage_users/userservice_package.html</a>
 *
 * @Component
 */
class MoufUserService implements UserServiceInterface {
	
	/**
	 * The path to the login page, relative to the root of the application.
	 * The path is relative to the ROOT of the web application.
	 * It should not start with a "/" and should not end with a "/".
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
	 * This is an array containing all components that should be notified
	 * when a user logs in or logs out.
	 * All components in this array should implement the AuthenticationListenerInterface
	 * interface.
	 * For instance, the MoufRightsService, that manages the rights of users is
	 * one of those. 
	 *
	 * @Property
	 * @Compulsory
	 * @var array<AuthenticationListenerInterface>
	 */
	public $authenticationListeners;

	/**
	 * In case you have several Mouf applications using the UserService running on the same server, in the same domain, you
	 * should use a different session prefix for each application in order to avoid "melting" the sessions.
	 * 
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $sessionPrefix;
	
	/**
	 * Logs the user using the provided login and password.
	 * Returns true on success, false if the user or password is incorrect.
	 * 
	 * @param string $login
	 * @param string $password
	 * @return boolean.
	 */
	public function login($login, $password) {
		// Is a session mechanism available?
		if (!session_id()) {
			throw new MoufException("The session must be initialized before trying to login. Please use session_start().");
		}
		
		// First, if we are logged, let's unlog the user.
		if ($this->isLogged()) {
			$this->logoff();
		}
		
		$user = $this->userDao->getUserByCredentials($login, $password);
		if ($user != null) {
			$this->log->trace("User '".$user->getLogin()."' logs in.");
			$_SESSION[$this->sessionPrefix.'MoufUserId'] = $user->getId();
			$_SESSION[$this->sessionPrefix.'MoufUserLogin'] = $user->getLogin();
			
			if (is_array($this->authenticationListeners)) {
				foreach ($this->authenticationListeners as $listener) {
					$listener->afterLogIn($this);
				}
			}
			return true;
		} else {
			$this->log->trace("Identication failed for login '".$user."'");
			return false;
		}
	}
	
	/**
	 * Logs the user using the provided login.
	 * The password is not needed if you use this function.
	 * Of course, you should use this functions sparingly.
	 * For instance, it can be useful if you want an administrator to "become" another
	 * user without requiring the administrator to provide the password. 
	 * 
	 * @param string $login
	 */
	public function loginWithoutPassword($login) {
		// First, if we are logged, let's unlog the user.
		if ($this->isLogged()) {
			$this->logoff();
		}
		
		$user = $this->userDao->getUserByLogin($login);
		if ($user == null) {
			throw new UserServiceException("Unable to find user whose login is ".$login);
		}
		$this->log->trace("User '".$user->getLogin()."' logs in, without providing a password.");
		$_SESSION[$this->sessionPrefix.'MoufUserId'] = $user->getId();
		$_SESSION[$this->sessionPrefix.'MoufUserLogin'] = $user->getLogin();
		
		if (is_array($this->authenticationListeners)) {
			foreach ($this->authenticationListeners as $listener) {
				$listener->afterLogIn($this);
			}
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
		if (isset($_SESSION[$this->sessionPrefix.'MoufUserId'])) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Redirects the user to the login page if he is not logged.
	 * The URL will be added a "redirect" GET parameter that can be used to return to the current page.
	 * The function will exit the program, so do not expect any return value :)
	 */
	public function redirectNotLogged() {
		// TODO: only if GET request!
		header("Location:".ROOT_URL.$this->loginPageUrl."/?redirect=".urlencode($_SERVER['REQUEST_URI']));
		exit;
	}
	
	/**
	 * Logs the user off.
	 *
	 */
	public function logoff() {
		if (isset($_SESSION[$this->sessionPrefix.'MoufUserLogin'])) {
			if (is_array($this->authenticationListeners)) {
				foreach ($this->authenticationListeners as $listener) {
					$listener->beforeLogOut($this);
				}
			}
			
			$this->log->trace("User ".$_SESSION[$this->sessionPrefix.'MoufUserLogin']." logs out.");
			unset($_SESSION[$this->sessionPrefix.'MoufUserId']);
			unset($_SESSION[$this->sessionPrefix.'MoufUserLogin']);
		}
	}
	
	/**
	 * Returns the current user ID.
	 *
	 * @return string
	 */
	public function getUserId() {
		if (isset($_SESSION[$this->sessionPrefix.'MoufUserId']))
			return $_SESSION[$this->sessionPrefix.'MoufUserId'];
		else
			return null; 
	}
	
	/**
	 * Returns the current user login.
	 *
	 * @return string
	 */
	public function getUserLogin() {
		if (isset($_SESSION[$this->sessionPrefix.'MoufUserLogin']))
			return $_SESSION[$this->sessionPrefix.'MoufUserLogin'];
		else
			return null; 
	}
	
	/**
	 * Returns the user that is logged (or null if no user is logged).
	 *
	 * return UserInterface
	 */
	public function getLoggedUser() {
		if (isset($_SESSION[$this->sessionPrefix.'MoufUserId'])) {
			return $this->userDao->getUserById($_SESSION[$this->sessionPrefix.'MoufUserId']);
		} else {
			return null;
		}
	}
	
}
?>