<?php 

/**
 * Classes implementing this interface can be queried for messages using the getMessages method.
 * A message is an object implementing the UserMessageInterface.
 * A message has:
 * - an HTML text
 * - a type (SUCCESS, INFO, WARNING, ERROR)
 * - a category
 * 
 * If the category is null, this is the global category and the message should be displayed at the top of
 * the page. If the category is set, the message applies to a part of the page (usually to a field that was not
 * correctly 
 * 
 * @author David Negrier
 */
interface MessageProviderInterface {
	
	/**
	 * Returns the list of messages to display, as an array of UserMessageInterface objects.
	 * 
	 * @param string $category The category of the messages to retrieve, or null for the global category.
	 * @return array<UserMessageInterface>
	 */
	function getMessages($category = NULL);
}

?>