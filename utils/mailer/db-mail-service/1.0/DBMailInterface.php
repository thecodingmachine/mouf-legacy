<?php

interface DBMailInterface extends MailInterface {

	/**
	 * Returns the category of the mail.
	 * Use the category to sort mails stored in database.
	 *
	 * @return string
	 */
	function getCategory();
	
	/**
	 * Returns the type of the mail.
	 * Use the type to sort mails stored in database.
	 *
	 * @return string
	 */
	function getType();
	
	/**
	 * Returns the date the mail was sent, as a PHP timestamp.
	 * Returns null if the mail was not yet sent.
	 *
	 * @return string
	 */
	function getDateSent();
	
	/**
	 * Returns the unique key identifying the mail.
	 * This key should be random enough to not be guessable.
	 * Returns null if there is no such key.
	 *
	 * @return string
	 */
	function getHashKey();
	
	/**
	 * This function is called by the DBMailService.
	 * 
	 * @param int $dbId
	 */
	function setDbId($dbId);
}