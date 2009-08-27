<?php
define("TRACKING_TRACE", 1);
define("TRACKING_DEBUG", 2);
define("TRACKING_INFO", 3);
define("TRACKING_WARN", 4);
define("TRACKING_ERROR", 5);
define("TRACKING_FATAL", 6);

class Log {
	/**
	 * Logs a message in the error log as a TRACE.
	 * This function takes 1 or 2 arguments:
	 * trace($string) logs the string
	 * trace(Exception $exception) logs the exception
	 * trace($string, Exception $exception) logs the string with the exception
	 */
	static function trace($string, $e=null) {
		if(TRACKING && TRACKING<=TRACKING_TRACE) {
			self::logMessage("TRACE", $string, $e);
		}
	}
	static function debug($string, $e=null) {
		if(TRACKING && TRACKING<=TRACKING_DEBUG) {
			self::logMessage("DEBUG", $string, $e);
		}
	}
	static function info($string, $e=null) {
		if(TRACKING && TRACKING<=TRACKING_INFO) {
			self::logMessage("INFO", $string, $e);
		}
	}
	static function warn($string, $e=null) {
		if(TRACKING && TRACKING<=TRACKING_WARN) {
			self::logMessage("WARN", $string, $e);
		}
	}
	static function error($string, $e=null) {
		if(TRACKING && TRACKING<=TRACKING_ERROR) {
			self::logMessage("ERROR", $string, $e);
		}
	}
	static function fatal($string, $e=null) {
		if(TRACKING && TRACKING<=TRACKING_FATAL) {
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
class DisplayHelper {
	public static function ToggleDisplay($id) {

		$xajaController = XajaController::getInstance();

		$obj = $xajaController->getWidgetById($id);

		if($obj->style->display && $obj->style->display=="block") $obj->style->display = "none";
		else $obj->style->display = "block";
	}
	
	public static function ShowAction($action_id, $div_id) {
		$xajaController = XajaController::getInstance();
		$obj = $xajaController->getWidgetById($div_id);
		
		$action = new Action($action_id);
		
		require_once VIEWS_PATHS.'action/action_details.php';
	}
}

?>