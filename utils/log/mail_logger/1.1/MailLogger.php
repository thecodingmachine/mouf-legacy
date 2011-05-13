<?php
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
	
	public function trace($string, Exception $e=null, array $additional_parameters=array()) {
		if($this->level<=self::$TRACE) {
			$this->logMessage("TRACE", $string, $e, $additional_parameters);
		}
	}
	public function debug($string, Exception $e=null, array $additional_parameters=array()) {
		if($this->level<=self::$DEBUG) {
			$this->logMessage("DEBUG", $string, $e, $additional_parameters);
		}
	}
	public function info($string, Exception $e=null, array $additional_parameters=array()) {
		if($this->level<=self::$INFO) {
			$this->logMessage("INFO", $string, $e, $additional_parameters);
		}
	}
	public function warn($string, Exception $e=null, array $additional_parameters=array()) {
		if($this->level<=self::$WARN) {
			$this->logMessage("WARN", $string, $e, $additional_parameters);
		}
	}
	public function error($string, Exception $e=null, array $additional_parameters=array()) {
		if($this->level<=self::$ERROR) {
			$this->logMessage("ERROR", $string, $e, $additional_parameters);
		}
	}
	public function fatal($string, Exception $e=null, array $additional_parameters=array()) {
		if($this->level<=self::$FATAL) {
			$this->logMessage("FATAL", $string, $e, $additional_parameters);
		}
	}

	private function logMessage($level, $string, $e=null, array $additional_parameters=array()) {
		
		$title = "An error occured in your application. Error level: ".$level.". ".substr($string,0,20);
		if (is_string($string)) {
			if (strlen($string)<20) {
				$title .= $string;
			} else {
				$title .= substr($string, 0, 19)."...";
			}
		}
		
		$this->mail->setTitle($title);
				
		if ($e == null) {
			if (!$string instanceof Exception) {
				$trace = debug_backtrace();
				$msg = $level.': '.$trace[1]['file']."(".$trace[1]['line'].") ".(isset($trace[2])?($trace[2]['class'].$trace[2]['type'].$trace[2]['function']):"")." -> ".$string;
				$msgHtml = $level.': '.$trace[1]['file']."(".$trace[1]['line'].") ".(isset($trace[2])?($trace[2]['class'].$trace[2]['type'].$trace[2]['function']):"")." -> ".$string;
			} else {
				$msg = $level.': '.ExceptionUtils::getTextForException($string);
				$msgHtml = $level.': '.ExceptionUtils::getHtmlForException($string);
			}
		} else {
			$trace = debug_backtrace();
			$msg = $level.': '.$trace[1]['file']."(".$trace[1]['line'].") ".(isset($trace[2])?($trace[2]['class'].$trace[2]['type'].$trace[2]['function']):"")." -> ".$string."\n".ExceptionUtils::getTextForException($e);
			$msgHtml = $level.': '.$trace[1]['file']."(".$trace[1]['line'].") ".(isset($trace[2])?($trace[2]['class'].$trace[2]['type'].$trace[2]['function']):"")." -> ".$string."\n".ExceptionUtils::getHtmlForException($e);
		}

		$this->mail->setBodyText($msg);
		$this->mail->setBodyHtml($msgHtml);
		$this->mailService->send($this->mail);
	}
}

?>