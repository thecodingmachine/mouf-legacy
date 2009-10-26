<?php
/**
 * This interface must be extended by any object representing an Alert.
 *
 */
interface AlertInterface {
	
	/**
	 * Returns the title of the alert
	 *
	 * @return string
	 */
	function getTitle();
	
	/**
	 * The message of the alert.
	 *
	 * @return string
	 */
	function getMessage();
	
	/**
	 * The "url" linked to the alert, if any.
	 *
	 * @return string
	 */
	function getUrl();
	
	/**
	 * The "category" of the alert.
	 *
	 * @return string
	 */
	function getCategory();
	
	/**
	 * The date the alert occured.
	 *
	 * @return timestamp
	 */
	function getDate();
	
	/**
	 * The "level" of the alert.
	 *
	 * @return int
	 */
	function getLevel();
	
	/**
	 * Whether the alert was validated or not.
	 *
	 * @return bool
	 */
	function getValidated();
	
	/**
	 * The "validation_date" for the alert.
	 *
	 * @return timestamp
	 */
	function getValidationDate();
	
	/**
	 * The list of recipients that received this alert.
	 *
	 * @return array<UserWithMailInterface>
	 */
	function getRecipients();
}
?>