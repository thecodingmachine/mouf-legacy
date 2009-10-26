<?php

require_once 'AlertBean.php';

/**
 * The AlertDao class will maintain the persistance of AlertBean class into the alerts table.
 * 
 * @Component
 */
class AlertDao 
{
	
	/**
	 * Return a new instance of AlertBean object, that will be persisted in database.
	 *
	 * @return AlertBean
	 */
	public function getNewAlert() {
		return DBM_Object::getNewObject('alerts', true, 'AlertBean');
	}
	
	/**
	 * Persist the AlertBean instance
	 *
	 */
	public function saveAlert(AlertBean $obj) {
		$obj->save();
	}
	
	/**
	 * Get all Alerts records. 
	 *
	 * @return array<AlertBean>
	 */
	public function getAlerts() {
		return DBM_Object::getObjects('alerts', null, null, null, null, 'AlertBean');
	}
	
	/**
	 * Get VideoadsCampaignBean specified by its ID
	 *
	 * @param string $id
	 * @return VideoadsCampaignBean
	 */
	public function getAlertById($id) {
		return DBM_Object::getObject('alerts', $id, 'AlertBean');
	}
}
?>