<?php

/**
 * A component extending the MoufValidationProviderInterface (and added to the MoufValidatorService) can be used
 * to run validation steps that will be displayed on Mouf validation screen (the front page).
 * 
 * @author David
 */
interface MoufValidationProviderInterface {
	
	/**
	 * Returns the name of the validator.
	 * 
	 * @return string
	 */
	public function getName();
	
	/**
	 * Returns the URL that will be called for that validator. The URL is relative to the ROOT_URL.
	 * The URL will return a JSON object with this format:
	 * {
	 * 	code: "ok|warn|error",
	 * 	html: "HTML code to be displayed on the Mouf validate screen"
	 * }
	 * 
	 * @return string
	 */
	public function getUrl();
}

?>