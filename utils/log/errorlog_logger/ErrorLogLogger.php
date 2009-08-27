<?php
require_once(dirname(__FILE__)."/../log_interface/LogInterface.php");

define("TRACKING_TRACE", 1);
define("TRACKING_DEBUG", 2);
define("TRACKING_INFO", 3);
define("TRACKING_WARN", 4);
define("TRACKING_ERROR", 5);
define("TRACKING_FATAL", 6);

/**
 * A logger class that writes messages into the php error_log.
 *
 * @Component
 */
class ErrorLogLogger implements LogInterface {
	
	private static $TRACE = 1;
	private static $DEBUG = 2;
	private static $INFO = 3;
	private static $WARN = 4;
	private static $ERROR = 5;
	private static $FATAL = 6;
	
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
	
	public function trace($string, Exception $e=null) {
		if($level<=self::$TRACE) {
			self::logMessage("TRACE", $string, $e);
		}
	}
	public function debug($string, Exception $e=null) {
		if($level<=self::$DEBUG) {
			self::logMessage("DEBUG", $string, $e);
		}
	}
	public function info($string, Exception $e=null) {
		if($level<=self::$INFO) {
			self::logMessage("INFO", $string, $e);
		}
	}
	public function warn($string, Exception $e=null) {
		if($level<=self::$WARN) {
			self::logMessage("WARN", $string, $e);
		}
	}
	public function error($string, Exception $e=null) {
		if($level<=self::$ERROR) {
			self::logMessage("ERROR", $string, $e);
		}
	}
	public function fatal($string, Exception $e=null) {
		if($level<=self::$FATAL) {
			self::logMessage("FATAL", $string, $e);
		}
	}

	private static function logMessage($level, $string, $e=null) {
		if ($e == null) {
			if (!$string instanceof Exception) {
				$trace = debug_backtrace();
				error_log($level.': '.$trace[1]['file']."(".$trace[1]['line'].") ".$trace[2]['class'].$trace[2]['type'].$trace[2]['function']." -> ".$string);
			} else {
				error_log($level.': '.ExceptionUtils::getTextForException($string));
			}
		} else {
			$trace = debug_backtrace();
			error_log($level.': '.$trace[1]['file']."(".$trace[1]['line'].") ".$trace[2]['class'].$trace[2]['type'].$trace[2]['function']." -> ".$string."\n".ExceptionUtils::getTextForException($e));
		}

	}
}

?>