<?php

FilterUtils::registerFilter("Admin");

class stubAdminAnnotation extends AbstractFilter
{

	/**
	 * Function to be called before the action.
	 */
	public function beforeAction() {
		UserService::checkAdmin();
	}

	/**
	 * Function to be called after the action.
	 */
	public function afterAction() {

	}
}
?>