<?php

/**
 * The result of actions (in the install process) should implement the MoufActionResultInteface.
 * Actually, there are 2 main classes implementing this interface:
 *  - MoufActionDoneResult : tells everything went all right
 *  - MoufActionRedirectResult: tells we should redirect to another page to perform the action.
 * 
 * @author david
 */
interface MoufActionResultInteface {

	/**
	 * Returns the status of the action.
	 * Can be one of: "done", "error", "redirect".
	 * @return string
	 */
	public function getStatus();
	
	/**
	 * Returns the URL we should redirect to.
	 * Returns null if no redirect is requested by the action.
	 * @return string
	 */
	public function getRedirectUrl();
}