<?php

/**
 * Objects implementing this interface can render errors.
 * 
 * @author David Negrier
 */
interface ErrorRendererInterface {
	
	/**
	 * Renders the error and returns the text for this rendered error.
	 * 
	 * @param PhpError $error
	 * @return string
	 */
	public function renderError(PhpError $error);
}