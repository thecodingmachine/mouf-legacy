<?php

/**
 * The LogMessage class represents a message to be logged with all the metadata associated (criticity, etc...)
 * 
 * @author David Negrier
 */
class LogMessage {
	
	public $level;
	
	/**
	 * The message string (can also be an exception).
	 * 
	 * @var string | Exception
	 */
	public $message;
	
	/**
	 * 
	 * @var Exception
	 */
	public $exception;
	
	/**
	 * An array of additional params
	 * 
	 * @var array
	 */
	public $additional_parameters;
	
	public function __construct($level = null, $message=null, $e=null, array $additional_parameters) {
		$this->level = $level;
		$this->message = $message;
		$this->exception = $e;
		$this->additional_parameters = $additional_parameters;
	}
}