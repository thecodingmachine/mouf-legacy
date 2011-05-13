<?php
//require_once(dirname(__FILE__)."/../../log_interface/1.0/LogInterface.php");

/**
 * A logger class that writes messages into the php error_log.
 * Note: any parameter passed in third parameter (in the $additional_parameters array) will be ignored
 * by this logger.
 *
 * @Component
 */
class ErrorLogLogger implements LogInterface {
	
	public static $TRACE = 1;
	public static $DEBUG = 2;
	public static $INFO = 3;
	public static $WARN = 4;
	public static $ERROR = 5;
	public static $FATAL = 6;
	
	/**
	 * The minimum level that will be tracked by this logger.
	 * Any log with a level below this level will not be logger.
	 *
	 * @Property
	 * @Compulsory 
	 * @OneOf "1","2","3","4","5","6"
	 * @OneOfText "TRACE","DEBUG","INFO","WARN","ERROR","FATAL"
	 * @var int
	 */
	public $level;
	
	public function trace($string, Exception $e=null, array $additional_parameters=array()) {
		if($this->level<=self::$TRACE) {
			self::logMessage("TRACE", $string, $e);
		}
	}
	public function debug($string, Exception $e=null, array $additional_parameters=array()) {
		if($this->level<=self::$DEBUG) {
			self::logMessage("DEBUG", $string, $e);
		}
	}
	public function info($string, Exception $e=null, array $additional_parameters=array()) {
		if($this->level<=self::$INFO) {
			self::logMessage("INFO", $string, $e);
		}
	}
	public function warn($string, Exception $e=null, array $additional_parameters=array()) {
		if($this->level<=self::$WARN) {
			self::logMessage("WARN", $string, $e);
		}
	}
	public function error($string, Exception $e=null, array $additional_parameters=array()) {
		if($this->level<=self::$ERROR) {
			self::logMessage("ERROR", $string, $e);
		}
	}
	public function fatal($string, Exception $e=null, array $additional_parameters=array()) {
		if($this->level<=self::$FATAL) {
			self::logMessage("FATAL", $string, $e);
		}
	}

	private static function logMessage($level, $string, $e=null) {
		if ($e == null) {
			if (!$string instanceof Exception) {
				$trace = debug_backtrace();
				error_log($level.': '.$trace[1]['file']."(".$trace[1]['line'].") ".(isset($trace[2])?($trace[2]['class'].$trace[2]['type'].$trace[2]['function']):"")." -> ".$string);
			} else {
				error_log($level.': '.ExceptionUtils::getTextForException($string));
			}
		} else {
			$trace = debug_backtrace();
			error_log($level.': '.$trace[1]['file']."(".$trace[1]['line'].") ".(isset($trace[2])?($trace[2]['class'].$trace[2]['type'].$trace[2]['function']):"")." -> ".$string."\n".ExceptionUtils::getTextForException($e));
		}

	}
}

?>