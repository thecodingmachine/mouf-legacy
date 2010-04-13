<?php

/**
 * Validators that validates account id.
 * An account ID is valid only if the account contains a user and the account is valid.
 */
class AccountValidator extends AbstractValidator {


	public function __construct($param) {
		AbstractValidator::__construct($param);
	}

	/**
	 * Validated the value
	 * @attr value the value to validate
	 * @return true if the value validates, false if it doesn't.
	 */
	public function validate($value) {
		if (is_numeric($value)) {
			// Check Access Rights
			if(!UserService::isMyAccount($value)) {
				return false;
			}
			return true;
		} else {
			return false;
		}
	}
}

?>
