<?php

/**
 * Classes implementing this interface can be used to filter logs using the FilterLogger class.
 * 
 * @author David Negrier
 */
interface LogFilterInterface {
	
	/**
	 * Filters the message $logMessage and returns a new LogMessage object.
	 * Note that the function can return null. In this case, the log will be completely discarded.
	 * 
	 * @param LogMessage $logMessage
	 * @return LogMessage
	 */
	function filter(LogMessage $logMessage);
}