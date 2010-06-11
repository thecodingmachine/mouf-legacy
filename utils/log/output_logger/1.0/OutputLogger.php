<?php

/**
 * A logger class that writes messages directly to the screen (in the HTML if we are in a webpage, in the output if we are on the command line).
 * Therefore, the output is displayed in the HTML if we are in a webpage, in the output if we are on the command line.
 * This is very useful in development. For production servers, please prefer a logger that writes in a file (like the ErrorLogLogger).
 * 
 * @Component
 */
class OutputLogger implements LogInterface {
	
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
	
	public function trace($string, Exception $e=null) {
		if($this->level<=self::$TRACE) {
			self::logMessage("TRACE", $string, $e);
		}
	}
	public function debug($string, Exception $e=null) {
		if($this->level<=self::$DEBUG) {
			self::logMessage("DEBUG", $string, $e);
		}
	}
	public function info($string, Exception $e=null) {
		if($this->level<=self::$INFO) {
			self::logMessage("INFO", $string, $e);
		}
	}
	public function warn($string, Exception $e=null) {
		if($this->level<=self::$WARN) {
			self::logMessage("WARN", $string, $e);
		}
	}
	public function error($string, Exception $e=null) {
		if($this->level<=self::$ERROR) {
			self::logMessage("ERROR", $string, $e);
		}
	}
	public function fatal($string, Exception $e=null) {
		if($this->level<=self::$FATAL) {
			self::logMessage("FATAL", $string, $e);
		}
	}

	private static function logMessage($level, $string, $e=null) {
		if ($e == null) {
			if (!$string instanceof Exception) {
				$trace = debug_backtrace();
				echo($level.': '.$trace[1]['file']."(".$trace[1]['line'].") ".$trace[2]['class'].$trace[2]['type'].$trace[2]['function']." -> ".$string."\n");
			} else {
				echo($level.': '.ExceptionUtils::getTextForException($string)."\n");
			}
		} else {
			$trace = debug_backtrace();
			echo($level.': '.$trace[1]['file']."(".$trace[1]['line'].") ".$trace[2]['class'].$trace[2]['type'].$trace[2]['function']." -> ".$string."\n".ExceptionUtils::getTextForException($e)."\n");
		}

	}
}

?>