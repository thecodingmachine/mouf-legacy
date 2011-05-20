<?php
//require_once(dirname(__FILE__)."/../../log_interface/1.0/LogInterface.php");

/**
 * A logger class that filters logs before passing the log to a logger.
 *
 * @Component
 */
class FilterLogger implements LogInterface {

	/**
	 * The logger used to log the message once it has been filtered.
	 * 
	 * @Property
	 * @Compulsory
	 * @var LogInterface
	 */
	public $logger;
	
	/**
	 * The list of filters to apply to the message before logging it.
	 * 
	 * @Property
	 * @Compulsory
	 * @var array<LogFilterInterface>
	 */
	public $filters = array();
	
	public function trace($string, Exception $e=null, array $additional_parameters=array()) {
		$this->logMessage("TRACE", $string, $e, $additional_parameters);
	}
	public function debug($string, Exception $e=null, array $additional_parameters=array()) {
		$this->logMessage("DEBUG", $string, $e, $additional_parameters);
	}
	public function info($string, Exception $e=null, array $additional_parameters=array()) {
		$this->logMessage("INFO", $string, $e, $additional_parameters);
	}
	public function warn($string, Exception $e=null, array $additional_parameters=array()) {
		$this->logMessage("WARN", $string, $e, $additional_parameters);
	}
	public function error($string, Exception $e=null, array $additional_parameters=array()) {
		$this->logMessage("ERROR", $string, $e, $additional_parameters);
	}
	public function fatal($string, Exception $e=null, array $additional_parameters=array()) {
		$this->logMessage("FATAL", $string, $e, $additional_parameters);
	}

	private function logMessage($level, $string, $e=null, array $additional_parameters) {
		$logMessage = new LogMessage($level, $string, $e, $additional_parameters);
		foreach ($this->filters as $filter) {
			/* @var $filter LogFilterInterface */
			$logMessage = $filter->filter($logMessage);
			if ($logMessage == null) {
				return;
			}
		}
		
		switch ($level) {
			case "TRACE":
				$this->logger->trace($logMessage->message, $logMessage->exception, $logMessage->additional_parameters);
				break;
			case "DEBUG":
				$this->logger->debug($logMessage->message, $logMessage->exception, $logMessage->additional_parameters);
				break;
			case "INFO":
				$this->logger->info($logMessage->message, $logMessage->exception, $logMessage->additional_parameters);
				break;
			case "WARN":
				$this->logger->warn($logMessage->message, $logMessage->exception, $logMessage->additional_parameters);
				break;
			case "ERROR":
				$this->logger->error($logMessage->message, $logMessage->exception, $logMessage->additional_parameters);
				break;
			case "FATAL":
				$this->logger->fatal($logMessage->message, $logMessage->exception, $logMessage->additional_parameters);
				break;
		}
	}
}

?>