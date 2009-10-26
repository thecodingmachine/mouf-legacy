<?php
/**
 * The AlertRecipientBean class maps the 'alert_recipients' table in database.
 *
 */
class AlertRecipientBean extends DBM_Object 
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
	 * The getter for the "alert_id" column.
	 *
	 * @return string
	 */
	public function getAlertId(){
		return $this->alert_id;
	}
	
	/**
	 * The setter for the "alert_id" column.
	 *
	 * @param string $alert_id
	 */
	public function setAlertId($alert_id) {
		$this->alert_id = $alert_id;
	}
	/**
	 * The getter for the "user_id" column.
	 *
	 * @return string
	 */
	public function getUserId(){
		return $this->user_id;
	}
	
	/**
	 * The setter for the "user_id" column.
	 *
	 * @param string $user_id
	 */
	public function setUserId($user_id) {
		$this->user_id = $user_id;
	}
}
?>