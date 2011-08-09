<?php

/**
 * A user message is an object implementing the UserMessageInterface.
 * A user message is a message that is displayed to the user.
 * 
 * A message has:
 * - an HTML text
 * - a type (SUCCESS, INFO, WARNING, ERROR)
 * - a category
 * 
 * If the category is null, the user message is "global". This means the message should be displayed at the top of
 * the page. If the category is set, the message applies to a part of the page (usually to a field that was not
 * correctly completed).
 * 
 * @author David Negrier
 */
interface UserMessageInterface {
	const SUCCESS = "success";
	const INFO = "info";
	const WARNING = "warning";
	const ERROR = "error";
	
	/**
	 * Returns the message, as an HTML string to be displayed.
	 * 
	 * @return string
	 */
	function getMessage();
	
	/**
	 * Returns the type of the message.
	 * Can be one of UserMessageInterface::SUCCESS, UserMessageInterface::INFO, UserMessageInterface::WARNING, or UserMessageInterface::ERROR.
	 *
	 * @return string
	 */
	function getType();
	
	/**
	 * Returns the category for this message (or null if this is a global message).
	 * 
	 * @return string
	 */
	function getCategory();
}
