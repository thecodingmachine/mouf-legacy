<?php

/**
 * Validators that validates number (int or floats).
 */
class NumberValidator extends AbstractValidator {


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
			return true;
		} else {
			return false;
		}
	}
}

?>
