<?php
//require_once(dirname(__FILE__)."/../../log_interface/1.0/LogInterface.php");

/**
 * A logger class that forwards the log to multiple loggers.
 * Use this class if you want a logger that logs to several places at the same time.*
 * Using a MultiLogger, you can append several loggers. For instance, you might want to write in the
 * error log using the ErrorLogLogger and to send a mail to the admin using the MailLogger...
 *
 * @Component
 */
class MultiLogger implements LogInterface {
	
	/**
	 * The list of loggers that will be called when the MultiLogger is called. 
	 *
	 * @Property
	 * @Compulsory 
	 * @var array<LogInterface>
	 */
	public $loggers = array();
	
	public function trace($string, Exception $e=null, array $additional_parameters=array()) {
		foreach ($this->loggers as $logger) {
			/* @var $logger LogInterface */
			$logger->trace($string, $e, $additional_parameters);
		}
	}
	public function debug($string, Exception $e=null, array $additional_parameters=array()) {
		foreach ($this->loggers as $logger) {
			/* @var $logger LogInterface */
			$logger->debug($string, $e, $additional_parameters);
		}
	}
	public function info($string, Exception $e=null, array $additional_parameters=array()) {
		foreach ($this->loggers as $logger) {
			/* @var $logger LogInterface */
			$logger->info($string, $e, $additional_parameters);
		}
	}
	public function warn($string, Exception $e=null, array $additional_parameters=array()) {
		foreach ($this->loggers as $logger) {
			/* @var $logger LogInterface */
			$logger->warn($string, $e, $additional_parameters);
		}
	}
	public function error($string, Exception $e=null, array $additional_parameters=array()) {
		foreach ($this->loggers as $logger) {
			/* @var $logger LogInterface */
			$logger->error($string, $e, $additional_parameters);
		}
	}
	public function fatal($string, Exception $e=null, array $additional_parameters=array()) {
		foreach ($this->loggers as $logger) {
			/* @var $logger LogInterface */
			$logger->fatal($string, $e, $additional_parameters);
		}
	}
}

?>