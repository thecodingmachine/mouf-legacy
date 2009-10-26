<?php

class Alert implements AlertInterface {
	
	private $id;
	private $title;
	private $message;
	private $url;
	private $category;
	private $date;
	private $level;
	private $validated;
	private $validationDate;
	private $recipients;
	
	/**
	 * Returns the title of the alert
	 *
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}
	
	/**
	 * Sets the title of the alert
	 *
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}
	
	/**
	 * The message of the alert.
	 *
	 * @return string
	 */
	public function getMessage() {
		return $this->message;
	}
	
	/**
	 * Sets the message of the alert
	 *
	 * @param string $message
	 */
	public function setMessage($message) {
		$this->message = $message;
	}
	
	/**
	 * The "url" linked to the alert, if any.
	 *
	 * @return string
	 */
	public function getUrl() {
		return $this->url;
	}
	
	/**
	 * Sets the URL of the alert
	 *
	 * @param string $message
	 */
	public function setUrl($url) {
		$this->url = $url;
	}
	
	/**
	 * The "category" of the alert.
	 *
	 * @return string
	 */
	public function getCategory() {
		return $this->category;
	}
	
	/**
	 * Sets the category of the alert (if any)
	 *
	 * @param string $category
	 */
	public function setCategory($category) {
		$this->category = $category;
	}
	
	/**
	 * The date the alert occured.
	 *
	 * @return timestamp
	 */
	public function getDate() {
		return $this->date;
	}
	
	/**
	 * Sets the date of the alert
	 *
	 * @param timestamp $date
	 */
	public function setDate($date) {
		$this->date = $date;
	}
	
	/**
	 * The "level" of the alert.
	 *
	 * @return int
	 */
	public function getLevel() {
		return $this->level;
	}
	
	/**
	 * Sets the level of the alert
	 *
	 * @param int $level
	 */
	public function setLevel($level) {
		$this->level = $level;
	}
	
	/**
	 * Whether the alert was validated or not.
	 *
	 * @return bool
	 */
	public function getValidated() {
		return $this->validated;
	}
	
	/**
	 * Sets whether the alert is validated or not
	 *
	 * @param int $validated
	 */
	public function setValidated($validated) {
		$this->validated = $validated;
	}
	
	/**
	 * The "validation_date" for the alert.
	 *
	 * @return timestamp
	 */
	public function getValidationDate() {
		return $this->validationDate;
	}
	
	/**
	 * Sets the validation date for the alert
	 *
	 * @param timestamp $validationDate
	 */
	public function setValidatationDate($validationDate) {
		$this->validationDate = $validationDate;
	}
	
	/**
	 * The list of recipients that received this alert.
	 *
	 * @return array<UserWithMailInterface>
	 */
	public function getRecipients();
	
	/**
	 * Sets the recipients
	 *
	 * @param array<UserWithMailInterface> $recipients
	 */
	public function setRecipients($recipients) {
		$this->recipients = $recipients;
	}
}
?>