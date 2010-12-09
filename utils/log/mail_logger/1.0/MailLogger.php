<?php
//require_once(dirname(__FILE__)."/../../log_interface/1.0/LogInterface.php");

/**
 * A logger class that writes messages into the php error_log.
 *
 * @Component
 */
class MailLogger implements LogInterface {
	
	/**
	 * The service used to send mails.
	 * 
	 * @Property
	 * @Compulsory
	 * @var MailServiceInterface
	 */
	public $mailService;
	
	/**
	 * The model of the mail sent when an error occurs.
	 * This is in this object that you will specify the mail address.
	 * 
	 * @Property
	 * @Compulsory
	 * @var ErrorMail
	 */
	public $mail;
	
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
		
		$this->mail->setTitle("An error occured in your application. Error level: ".$level);
				
		if ($e == null) {
			if (!$string instanceof Exception) {
				$trace = debug_backtrace();
				$msg = $level.': '.$trace[1]['file']."(".$trace[1]['line'].") ".$trace[2]['class'].$trace[2]['type'].$trace[2]['function']." -> ".$string;
				$msgHtml = $level.': '.$trace[1]['file']."(".$trace[1]['line'].") ".$trace[2]['class'].$trace[2]['type'].$trace[2]['function']." -> ".$string;
			} else {
				$msg = $level.': '.ExceptionUtils::getTextForException($string);
				$msgHtml = $level.': '.ExceptionUtils::getHtmlForException($string);
			}
		} else {
			$trace = debug_backtrace();
			$msg = $level.': '.$trace[1]['file']."(".$trace[1]['line'].") ".$trace[2]['class'].$trace[2]['type'].$trace[2]['function']." -> ".$string."\n".ExceptionUtils::getTextForException($e);
			$msgHtml = $level.': '.$trace[1]['file']."(".$trace[1]['line'].") ".$trace[2]['class'].$trace[2]['type'].$trace[2]['function']." -> ".$string."\n".ExceptionUtils::getHtmlForException($e);
		}

		$this->mail->setBodyText($msg);
		$this->mail->setBodyHtml($msgHtml);
		$this->mailService->send($this->mail);
	}
}

?>