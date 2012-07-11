<?php

/**
 * The GlobalErrorHandler class is in charge of dispatching errors and exceptions to the
 * error handlers instances (that implement the ErrorHandlerInterface interface).
 * 
 * There should be only one instance of this class, and its name should be "defaultGlobalErrorHandler"
 * 
 * @author David Negrier
 * @Component
 */
class GlobalErrorHandler implements ErrorHandlerInterface {
	
	/**
	 * The list of error handlers to be called when an error is received.
	 * 
	 * @Property
	 * @Compulsory
	 * @var array<ErrorHandlerInterface>
	 */
	public $errorHandlers = array();
	
	/**
	 * Set to true if you want PHP to handle errors normally (after your error handler has been launched).
	 * Set to false to override PHP error handling with your mechanism.
	 * 
	 * @Property
	 * @var bool
	 */
	public $defaultErrorHandling = true;
	
	/**
	 * This function is called each time on error is triggered by PHP.
	 * 
	 * @param PhpError $error
	 */
	public function handleError(PhpError $error) {
		foreach ($this->errorHandlers as $errorHandler) {
			$errorHandler->handleError($error);
		}
		return !$this->defaultErrorHandling;
	}
	
	/**
	 * This function is called each time an uncatched exception is thrown by the application.
	 *
	 * @param Exception $e
	 */
	public function handleException(Exception $e) {
		foreach ($this->errorHandlers as $errorHandler) {
			$errorHandler->handleException($e);
		}
	}
	
	public static function handleGlobalError($errno, $errstr, $errfile, $errline, array $errcontext=array()) {
		$defaultGlobalErrorHandler = MoufManager::getMoufManager()->getInstance("defaultGlobalErrorHandler");
		/* @var $defaultGlobalErrorHandler GlobalErrorHandler */
		$defaultGlobalErrorHandler->handleError(new PhpError($errno , $errstr, $errfile, $errline, $errcontext));
	}
	
	public static function handleGlobalException($e) {
		$defaultGlobalErrorHandler = MoufManager::getMoufManager()->getInstance("defaultGlobalErrorHandler");
		/* @var $defaultGlobalErrorHandler GlobalErrorHandler */
		$defaultGlobalErrorHandler->handleException($e);
	}
}