<?php

/**
 * This error handler uses a renderer to send errors directly to the PHP error log.
 * 
 * @author david
 * @Component
 */
class ToPhpErrorLogErrorHandler implements ErrorHandlerInterface {

	/**
	 * The object used to render errors.
	 * 
	 * @Property
	 * @Compulsory
	 * @var ErrorRendererInterface
	 */
	public $errorRenderer;
	
	/**
	 * The object used to render exceptions.
	 * 
	 * @Property
	 * @Compulsory
	 * @var ExceptionRendererInterface
	 */
	public $exceptionRenderer;
	
	/**
	 * This function is called each time on error is triggered by PHP.
	 *
	 * @param PhpError $error
	 */
	public function handleError(PhpError $error) {
		if (error_reporting() & $error->getLevel()) {
			error_log($this->errorRenderer->renderError($error));
		}
	}
	
	/**
	 * This function is called each time an uncatched exception is thrown by the application.
	 *
	 * @param Exception $e
	 */
	public function handleException(Exception $e) {
		error_log($this->exceptionRenderer->renderException($e));
	}
}