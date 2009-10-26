<?php

require_once 'AlertRecipientBean.php';

/**
 * The AlertRecipientDao class will maintain the persistance of AlertRecipientBean class into the alert_recipients table.
 * 
 * @Component
 */
class AlertRecipientDao 
{
	
	/**
	 * Return a new instance of AlertRecipientBean object, that will be persisted in database.
	 *
	 * @return AlertRecipientBean
	 */
	public function getNewAlertRecipients() {
		return DBM_Object::getNewObject('alert_recipients', true, 'AlertRecipientBean');
	}
	
	/**
	 * Persist the AlertRecipientBean instance
	 *
	 */
	public function saveAlertRecipients(AlertRecipientBean $obj) {
		$obj->save();
	}
	
	/**
	 * Get all AlertRecipients records. 
	 *
	 * @return array<AlertRecipientBean>
	 */
	public function getAlertRecipients() {
		return DBM_Object::getObjects('alert_recipients', null, null, null, null, 'AlertRecipientBean');
	}
	
	/**
	 * Get VideoadsCampaignBean specified by its ID
	 *
	 * @param string $id
	 * @return VideoadsCampaignBean
	 */
	public function getAlertRecipientsById($id) {
		return DBM_Object::getObject('alert_recipients', $id, 'AlertRecipientBean');
	}
	
	public function getAlertRecipientsForAlert(AlertBean $alertBean) {
		$alertRecipients = DBM_Object::getObjects("alert_recipients", $alertBean, null, null, 'AlertRecipientBean');
		return $alertRecipients;
	}
	
	public function deleteRecipientsForAlert(AlertBean $alertBean) {
		$alertRecipients = $this->deleteRecipientsForAlert($alertBean);
		foreach ($alertRecipients as $alertRecipient) {
			DBM_Object::deleteObject($alertRecipient);
		}
	}
}
?>