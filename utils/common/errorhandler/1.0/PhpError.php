<?php

/**
 * The PhpError class represents a typical PHP error (catched via set_error_handler).
 * 
 * @author David Negrier
 */
class PhpError {
	
	private $errno;
	private $errstr;
	private $errfile;
	private $errline;
	private $errcontext;
	private $debugTrace;
	
	public function __construct($errno, $errstr, $errfile = null, $errline = null, array $errcontext = array()) {
		$this->errno = $errno;
		$this->errstr = $errstr;
		$this->errfile = $errfile;
		$this->errline = $errline;
		$this->errcontext = $errcontext;
		$this->debugTrace = debug_backtrace();
		array_shift($this->debugTrace);
		array_shift($this->debugTrace);
	}
	
	public function getLevel() {
		return $this->errno;
	}
	
	/**
	 * Returns the code of the error, as a string.
	 * For instance: "E_ERROR"
	 * 
	 * @return string
	 */
	public function getLevelAsString() {
		switch ($this->errno) {
			case E_ERROR:
				return "E_ERROR";
			case E_WARNING:
				return "E_WARNING";
			case E_NOTICE:
				return "E_NOTICE";
			case E_USER_ERROR:
				return "E_USER_ERROR";
			case E_USER_WARNING:
				return "E_USER_WARNING";
			case E_USER_NOTICE:
				return "E_USER_NOTICE";
			case E_RECOVERABLE_ERROR:
				return "E_RECOVERABLE_ERROR";
			case E_STRICT:
				return "E_STRICT";
			default:
				return "Unkown error code ".$this->errno;
		}
	}
	
	public function getStr() {
		return $this->errstr;
	}
	
	public function getFile() {
		return $this->errfile;
	}
	
	public function getLine() {
		return $this->errline;
	}
	
	public function getContext() {
		return $this->errcontext;
	}
	
	public function getDebugTrace() {
		return $this->debugTrace;
	}
}