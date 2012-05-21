<?php

/**
 * Classes implementing the ErrorHandlerInterface interface can be used to catch
 * any error, or any uncatched exception.
 * Instances of those classes should be registered in the defaultGlobalErrorHandler instance.
 * 
 * @author David Negrier
 */
interface ErrorHandlerInterface {
	
	/**
	 * This function is called each time on error is triggered by PHP.
	 * 
	 * @param PhpError $error
	 */
	public function handleError(PhpError $error);
	
	/**
	 * This function is called each time an uncatched exception is thrown by the application.
	 *
	 * @param Exception $e
	 */
	public function handleException(Exception $e);
}