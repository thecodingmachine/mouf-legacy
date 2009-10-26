<?php

require_once 'AlertBean.php';

/**
 * The AlertDaoInterface must be implemented by any class that
 * will allow access to alerts stored in database.
 * 
 * @Component
 */
class AlertDaoInterface 
{
	
	/**
	 * Return a new instance of AlertBean object, that will be persisted in database.
	 *
	 * @return AlertBean
	 */
	public function getNewAlert();
	
	/**
	 * Persist the AlertBean instance
	 *
	 */
	public function saveAlert(AlertBean $obj);
	
	/**
	 * Get all Alerts records. 
	 *
	 * @return array<AlertBean>
	 */
	public function getAlerts();
	
	/**
	 * Get VideoadsCampaignBean specified by its ID
	 *
	 * @param string $id
	 * @return VideoadsCampaignBean
	 */
	public function getAlertById($id);
}
?>