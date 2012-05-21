<?php

/**
 * This error handler uses a renderer to output errors directly to the output.
 * 
 * @author david
 * @Component
 */
class ToOutputErrorHandler implements ErrorHandlerInterface {

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
	 * By default, this package will render errors to the output only if the "display_errors" parameter
	 * is set to "on".
	 * Set this parameter to "true" to completely bypass the "display_errors" parameter.
	 * 
	 * @Property
	 * @Compulsory
	 * @var boolean
	 */
	public $alwaysDisplayErrors = false;
	
	
	/**
	 * An optional condition. If this is set, the condition must be fulfilled
	 * for the error handler to be used.
	 *
	 * @Property
	 * @var ConditionInterface
	 */
	public $condition;
	
	/**
	 * This function is called each time on error is triggered by PHP.
	 *
	 * @param PhpError $error
	 */
	public function handleError(PhpError $error) {
		if ($this->condition == null || $this->condition->isOk()) {
			if (ini_get("display_errors") || $this->alwaysDisplayErrors) {
				if (error_reporting() & $error->getLevel()) {
					echo $this->errorRenderer->renderError($error);
				}
			}
		}
	}
	
	/**
	 * This function is called each time an uncatched exception is thrown by the application.
	 *
	 * @param Exception $e
	 */
	public function handleException(Exception $e) {
		if ($this->condition == null || $this->condition->isOk()) {
			if (ini_get("display_errors") || $this->alwaysDisplayErrors) {
				echo $this->exceptionRenderer->renderException($e);
			}
		}
	}
}