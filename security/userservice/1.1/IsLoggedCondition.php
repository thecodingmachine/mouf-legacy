<?php

/**
 * The condition is true if the current user is logged, and is false if the current
 * user is not logged.
 * 
 * @Component
 */
class IsLoggedCondition implements ConditionInterface {

	/**
	 * @var UserServiceInterface
	 */
	protected $userService;
	
	/**
	 * This property is the service that will be used to decide whether the user is logged or not.
	 *
	 * @Property
	 * @Compulsory
	 * @param UserServiceInterface $userService
	 */
	public function setUserService(UserServiceInterface $userService) {
		$this->userService = $userService;
	}
	
	/**
	 * Returns true if the current user is logged, false otherwise.
	 *
	 * @param mixed $caller The condition caller. Optional, and not used by this class.
	 * @return bool
	 */
	function isOk($caller = null) {
		return $this->userService->isLogged();
	}
}
?>