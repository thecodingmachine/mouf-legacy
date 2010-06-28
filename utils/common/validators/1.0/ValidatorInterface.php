<?php

/**
 * Interface implemented by validators.
 */
interface ValidatorInterface {

	/**
	 * Validates the value.
	 * Returns true if the value is validated, false if it is not.
	 * 
	 * @param string $value the value to validate
	 * @return true if the value validates, false if it doesn't.
	 */
	public function validate($value);
}

?>