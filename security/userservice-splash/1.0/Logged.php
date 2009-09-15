<?php

FilterUtils::registerFilter("Logged");

/**
 * The @Logged filter should be used to check whether a user is logged or not.
 * It will try to do so by querying the "userService" instance, that should
 * be an instance of the "UserService" class (or a class extending the UserServiceInterface).
 * 
 * You can pass an additional parameter to overide the name of the instance.
 * For instance: @Logged("myUserService") will verify that the user is logged or not
 * using the "myUserService" instance.
 *
 */
class Logged extends AbstractFilter
{
	/**
	 * The value passed to the filter.
	 */
	protected $value;

	public function setValue($value) {
		$this->value = $value;
	}
	
	
	/**
	 * Function to be called before the action.
	 */
	public function beforeAction() {
		
		if (!empty($this->value)) {
			$value = $this->value;
		} else {
			$value = "userService"; 
		}
		try {
			$userService = MoufManager::getMoufManager()->getInstance($value);
		} catch (MoufInstanceNotFoundException $e) {
			if (!empty($this->value))
				throw new MoufException("Error using the @Logged annotation: unable to find the UserService instance named: ".$this->value, $e);
			else
				throw new MoufException("Error using the @Logged annotation: by default, this annotation requires a component named 'userService', and that extends the UserServiceInterface interface.", $e);
		}
		
		$is_logged = $userService->isLogged();
		if(!$is_logged){
			$loginUrl = $userService->loginPageUrl;
			header("Location:".ROOT_URL.$loginUrl."/?redirect=".urlencode($_SERVER['REQUEST_URI']));
			exit;
		}
	}

	/**
	 * Function to be called after the action.
	 */
	public function afterAction() {

	}
}
?>