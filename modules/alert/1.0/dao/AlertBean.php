<?php
/**
 * The AlertBean class maps the 'alerts' table in database.
 *
 */
class AlertBean extends DBM_Object 
{
	/**
	 * The getter for the "id" column.
	 *
	 * @return string
	 */
	public function getId(){
		return $this->id;
	}
	
	/**
	 * The setter for the "id" column.
	 *
	 * @param string $id
	 */
	public function setId($id) {
		$this->id = $id;
	}
	/**
	 * The getter for the "title" column.
	 *
	 * @return string
	 */
	public function getTitle(){
		return $this->title;
	}
	
	/**
	 * The setter for the "title" column.
	 *
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}
	/**
	 * The getter for the "message" column.
	 *
	 * @return string
	 */
	public function getMessage(){
		return $this->message;
	}
	
	/**
	 * The setter for the "message" column.
	 *
	 * @param string $message
	 */
	public function setMessage($message) {
		$this->message = $message;
	}
	/**
	 * The getter for the "url" column.
	 *
	 * @return string
	 */
	public function getUrl(){
		return $this->url;
	}
	
	/**
	 * The setter for the "url" column.
	 *
	 * @param string $url
	 */
	public function setUrl($url) {
		$this->url = $url;
	}
	/**
	 * The getter for the "category" column.
	 *
	 * @return string
	 */
	public function getCategory(){
		return $this->category;
	}
	
	/**
	 * The setter for the "category" column.
	 *
	 * @param string $category
	 */
	public function setCategory($category) {
		$this->category = $category;
	}
	/**
	 * The getter for the "date" column.
	 *
	 * @return string
	 */
	public function getDate(){
		return $this->date;
	}
	
	/**
	 * The getter for the "date" column, as a timestamp.
	 *
	 * @return timestamp
	 */
	public function getDateAsTimestamp() {
		return strtotime($this->date);
	}
	
	/**
	 * The setter for the "date" column.
	 *
	 * @param string $date
	 */
	public function setDate($date) {
		$this->date = $date;
	}
	
	/**
	 * The setter for the "date" column.
	 *
	 * @param timestamp $date
	 */
	public function setDateAsTimeStamp($date) {
		$this->date = date("Y-m-d H:i:s", $date);
	}
	
	/**
	 * The getter for the "level" column.
	 *
	 * @return string
	 */
	public function getLevel(){
		return $this->level;
	}
	
	/**
	 * The setter for the "level" column.
	 *
	 * @param string $level
	 */
	public function setLevel($level) {
		$this->level = $level;
	}
	/**
	 * The getter for the "validated" column.
	 *
	 * @return string
	 */
	public function getValidated(){
		return $this->validated;
	}
	
	/**
	 * The setter for the "validated" column.
	 *
	 * @param string $validated
	 */
	public function setValidated($validated) {
		$this->validated = $validated;
	}
	/**
	 * The getter for the "validation_date" column.
	 *
	 * @return string
	 */
	public function getValidationDate(){
		return $this->validation_date;
	}
	
	/**
	 * The getter for the "validation_date" column as a timestamp.
	 *
	 * @return timestamp
	 */
	public function getValidationDateAsTimeStamp(){
		return strtotime($this->validation_date);
	}
	
	/**
	 * The setter for the "validation_date" column.
	 *
	 * @param string $validation_date
	 */
	public function setValidationDate($validation_date) {
		$this->validation_date = $validation_date;
	}
	
	/**
	 * The setter for the "validation_date" column.
	 *
	 * @param timestamp $validation_date
	 */
	public function setValidationDateAsTimeStamp($validation_date) {
		$this->validation_date = date("Y-m-d H:i:s", $validation_date);
	}
}
?>