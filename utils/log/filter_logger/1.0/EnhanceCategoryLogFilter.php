<?php

/**
 * This log filter takes a log and enhances the "category" additional parameter of the log with the first characters of the log message.
 * This is very useful used with the DB_Logger (because the DB_Logger uses the "category" additional parameter to sort the logs.
 * Using this filter, you can automatically classify your logs in categories based on the message received. 
 * 
 * @author david
 */
class EnhanceCategoryLogFilter implements LogFilterInterface {
	
	/**
	 * The DB_Logger accepts three category fields. Choose the field to be used.
	 * Which category will be filled by this filter?
	 * 
	 * @Property
	 * @Compulsory
	 * @OneOf ("category1", "category2", "category3")
	 * @var int
	 */
	public $useCategory = "category1";
	
	/**
	 * The message will be automatically split at position $splitPosition.
	 * The substring will be used as a category.
	 * 
	 * @Property
	 * @Compulsory
	 * @var int
	 */
	public $splitPosition = 30;
	
	/**
	 * Filters the message $logMessage and returns a new LogMessage object.
	 * Note that the function can return null. In this case, the log will be completely discarded.
	 * 
	 * @param LogMessage $logMessage
	 * @return LogMessage
	 */
	function filter(LogMessage $logMessage) {
		if ($logMessage->message instanceof Exception) {
			$logMessage->additional_parameters[$this->useCategory] = substr($logMessage->message->getMessage(), 0, $this->splitPosition);
		} else {
			$logMessage->additional_parameters[$this->useCategory] = substr($logMessage->message, 0, $this->splitPosition);
		}
		
		return $logMessage;
	}
}