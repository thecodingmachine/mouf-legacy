<?php

/**
 * A logger class that DOES NOT log anything. Can be useful when you want to completely disable logging.
 *
 * @Component
 */
class NullLogger implements LogInterface {
	
	public function trace($string, Exception $e=null, array $additional_parameters=array()) {
	}
	public function debug($string, Exception $e=null, array $additional_parameters=array()) {
	}
	public function info($string, Exception $e=null, array $additional_parameters=array()) {
	}
	public function warn($string, Exception $e=null, array $additional_parameters=array()) {
	}
	public function error($string, Exception $e=null, array $additional_parameters=array()) {
	}
	public function fatal($string, Exception $e=null, array $additional_parameters=array()) {
	}
}

?>