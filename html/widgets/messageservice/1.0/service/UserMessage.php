<?php

/**
 * The UserMessage class represents a message that is displayed to the user.
 * This is the most simple implementation of the UserMessageInterface interface.
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
class UserMessage implements UserMessageInterface {
	
	private $message;
	private $type;
	private $category;	

	/**
	 * Cosntructor initializing all the fields.
	 * 
	 * @param string $message
	 * @param string $type
	 * @param string $category
	 */
	public function __construct($message = null, $type = null, $category = null) {
		$this->message = $message;
		$this->type = $type;
		$this->category = $category;
	}
	
	/**
	 * Sets the message, as an HTML string to be displayed.
	 *
	 * @param string $message
	 */
	function setMessage($message) {
		$this->message = $message;
	}
	
	/**
	 * Returns the message, as an HTML string to be displayed.
	 * 
	 * @return string
	 */
	function getMessage() {
		return $this->message;
	}
	
	/**
	 * Sets the type of the message.
	 * Can be one of UserMessageInterface::SUCCESS, UserMessageInterface::INFO, UserMessageInterface::WARNING, or UserMessageInterface::ERROR.
	 *
	 * @param string $type
	 */
	function setType($type) {
		if (!in_array($this->type, array(UserMessageInterface::SUCCESS, UserMessageInterface::INFO, UserMessageInterface::WARNING, UserMessageInterface::ERROR))) {
			throw new Exception("The type of a message must be one of UserMessageInterface::SUCCESS, UserMessageInterface::INFO, UserMessageInterface::WARNING, UserMessageInterface::ERROR");
		}
		
		$this->type = $type;
	}
	
	/**
	 * Returns the type of the message.
	 * Can be one of UserMessageInterface::SUCCESS, UserMessageInterface::INFO, UserMessageInterface::WARNING, or UserMessageInterface::ERROR.
	 *
	 * @return string
	 */
	function getType() {
		return $this->type;
	}
	
	/**
	 * Sets the category for this message (or null if this is a global message).
	 *
	 * @param string $category
	 */
	function setCategory($category) {
		$this->category = $category;
	}
	
	/**
	 * Returns the category for this message (or null if this is a global message).
	 * 
	 * @return string
	 */
	function getCategory() {
		return $this->category;
	}
}
