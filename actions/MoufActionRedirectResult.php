<?php

/**
 * The result of an action that says we should redirect to another page.
 * 
 * @author david
 */
class MoufActionRedirectResult {

	private $redirect;
	
	public function __construct($redirect) {
		$this->redirect = $redirect;
	}
	
	/**
	 * Returns the status of the action.
	 * Can be one of: "done", "error", "redirect".
	 * @return string
	 */
	public function getStatus() {
		return "redirect";
	}
	
	/**
	 * Returns the URL we should redirect to.
	 * Returns null if no redirect is requested by the action.
	 * @return string
	 */
	public function getRedirectUrl() {
		return $this->redirect;
	}
}