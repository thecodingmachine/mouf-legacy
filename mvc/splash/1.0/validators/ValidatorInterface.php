<?php

/**
 * Interface implemented by validators.
 */
interface ValidatorInterface {


	/**
	 * Validated the value
	 * @attr value the value to validate
	 * @return true if the value validates, false if it doesn't.
	 */
	public function validate($value);
}

?>
