<?php

FilterUtils::registerFilter("RequiresRight");

/**
 * The @RequiresRight filter should be used to check whether a user has
 * a certain right or not.
 * 
 * It will try to do so by querying the "rightsService" instance, that should
 * be an instance of the "MoufRightService" class (or a class extending the RightsServiceInterface).
 * 
 * This filter requires at least one parameter: "name"
 * 
 * So @RequiresRight(name="Admin") will require the Admin right to be logged.
 * Otherwise, the user is redirected to an error page.
 * 
 * You can pass an additional parameter to overide the name of the instance.
 * For instance: @RequiresRight(name="Admin",instance="myRightService") will 
 * verify that the user has the correct right using the "myRightService" instance.
 *
 */
class RequiresRight extends AbstractFilter
{
	/**
	 * The name of the right to check
	 */
	protected $name;
	
	public function setName($name) {
		$this->name = $name;
	}
	
	/**
	 * The name of the rightsService instance.
	 */
	protected $instanceName;

	public function setInstanceName($instanceName) {
		$this->instanceName = $instanceName;
	}
	
	/**
	 * Function to be called before the action.
	 */
	public function beforeAction() {
		
		if (!empty($this->instanceName)) {
			$instanceName = $this->instanceName;
		} else {
			$instanceName = "rightsService"; 
		}
		try {
			$rightsService = MoufManager::getMoufManager()->getInstance($instanceName);
		} catch (MoufInstanceNotFoundException $e) {
			if (!empty($this->name))
				throw new MoufException("Error using the @RequiresRight annotation: unable to find the RightsService instance named: ".$this->value, $e);
			else
				throw new MoufException("Error using the @RequiresRight annotation: by default, this annotation requires a component named 'rightsService', and that extends the RightsServiceInterface interface.", $e);
		}
		
		$rightsService->redirectNotAuthorized($this->name);
	}

	/**
	 * Function to be called after the action.
	 */
	public function afterAction() {

	}
}
?>