<?php

FilterUtils::registerFilter("Logged");

class Logged extends AbstractFilter
{

	/**
	 * Function to be called before the action.
	 */
	public function beforeAction() {
		UserService::checkLogged();
	}

	/**
	 * Function to be called after the action.
	 */
	public function afterAction() {

	}
}
?>