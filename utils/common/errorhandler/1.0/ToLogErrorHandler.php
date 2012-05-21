<?php

/**
 * This error handler sends errors directly to the logger passed in parameter.
 * 
 * @author david
 * @Component
 */
class ToLogErrorHandler implements ErrorHandlerInterface {

	/**
	 * The logger pointing to .
	 * 
	 * @Property
	 * @Compulsory
	 * @var LogInterface
	 */
	public $logger;
	
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
	 * By default, this package will log errors to the PHP error log only if the "log_errors" parameter
	 * is set to "on".
	 * Set this parameter to "true" to completely bypass the "log_errors" parameter.
	 *
	 * @Property
	 * @Compulsory
	 * @var boolean
	 */
	public $alwaysLogErrors = false;
	
	/**
	 * This function is called each time on error is triggered by PHP.
	 *
	 * @param PhpError $error
	 */
	public function handleError(PhpError $error) {
		if (ini_get("log_errors") || $this->alwaysLogErrors) {		
			if (error_reporting() & $error->getLevel()) {
				$errorMsg = $this->errorRenderer->renderError($error);
				
				switch ($error->getLevel()) {
					case E_ERROR:
						$this->logger->error($errorMsg);
						break;
					case E_DEPRECATED:
						$this->logger->info($errorMsg);
						break;
					case E_NOTICE:
						$this->logger->info($errorMsg);
						break;
					case E_RECOVERABLE_ERROR:
						$this->logger->error($errorMsg);
						break;
					case E_STRICT:
						$this->logger->info($errorMsg);
						break;
					case E_USER_DEPRECATED:
						$this->logger->info($errorMsg);
						break;
					case E_USER_ERROR:
						$this->logger->error($errorMsg);
						break;
					case E_USER_NOTICE:
						$this->logger->info($errorMsg);
						break;
					case E_USER_WARNING:
						$this->logger->warn($errorMsg);
						break;
					case E_WARNING:
						$this->logger->warn($errorMsg);
						break;
					default:
						$this->logger->error($errorMsg);
						break;
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
		if (ini_get("log_errors") || $this->alwaysLogErrors) {
			//$errorMsg = $this->exceptionRenderer->renderException($e);
			$this->logger->error($e);
		}
	}
}