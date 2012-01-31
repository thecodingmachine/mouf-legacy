<?php

/**
 * This class, that extends the Mail class adds required methods to benefit from all the power of the DBMailService.
 * Mails extending the DBMailInterface have categories and types that can be used to sort them.
 * 
 * @author David Négrier
 * @Component
 */
class DBMail extends Mail implements DBMailInterface {

	private $category;
	private $type;
	private $dbId;
	private $dateSent;
	private $hashKey;
	
	/**
	 * Returns the category of the mail.
	 * Use the category to sort mails stored in database.
	 *
	 * @return string
	 */
	public function getCategory() {
		return $this->category;
	}
	
	/**
	 * Sets the category for the mail (stored in DB)
	 * 
	 * @Property
	 * @param string $category
	 */
	public function setCategory($category) {
		$this->category = $category;
	}
	
	/**
	 * Returns the type of the mail.
	 * Use the type to sort mails stored in database.
	 *
	 * @return string
	 */
	function getType() {
		return $this->type;
	}

	/**
	 * Sets the type for the mail (stored in DB)
	 *
	 * @Property
	 * @param string $type
	 */
	public function setType($type) {
		$this->type = $type;
	}
	
	/**
	* Sets the date the mail was sent, as a PHP timestamp.
	* Set to null of the mail was not yet sent.
	*
	* @return string
	*/
	function setDateSent($dateSent) {
		$this->dateSent = $dateSent;
	}
	
	/**
	 * Returns the date the mail was sent, as a PHP timestamp.
	 * Returns null of the mail was not yet sent.
	 *
	 * @return string
	 */
	function getDateSent() {
		return $this->dateSent;
	}
	
	/**
	 * This function is called by the DBMailService to set the ID of the mail in database (when it is stored or retrieved from the DB).
	 * 
	 * @param int $dbId
	 */
	public function setDbId($dbId) {
		$this->dbId = $dbId;
	}
	
	/**
	 * Returns the ID of the mail in DB (or null if the mail has not yet been stored in DB).
	 * 
	 * @return number
	 */
	public function getDbId() {
		return $this->dbId;
	}
	
	/**
	 * Sets the hash key for this mail.
	 * This key should be random enough to not be guessable.
	 *
	 * @param int $dbId
	 */
	public function setHashKey($hashKey) {
		$this->hashKey = $hashKey;
	}
	
	/**
	 * Gets the hash key for this mail.
	 * This key should be random enough to not be guessable.
	 * Returns null if there is no such key.
	 *
	 * @return number
	 */
	public function getHashKey() {
		return $this->hashKey;
	}
}