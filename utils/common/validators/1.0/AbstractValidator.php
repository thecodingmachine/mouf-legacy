<?php

/**
 * Abstract class used by most validators.
 */
abstract class AbstractValidator implements ValidatorInterface {

	protected $param;

	public function __construct($param) {
		$this->param = $param;
	}

}

?>
