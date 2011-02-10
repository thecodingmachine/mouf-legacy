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
class RequiresRightAnnotation extends AbstractFilter
{
	
	public function __construct($value) {
		// Let's get the 2 first words:
		$tmpStr = trim($value);
		
		$vars = strtok($tmpStr, "()");
		if ($vars === false) {
			throw new Exception('Error while reading the @RequiresRight annotation. At least, an annotation must be in the form: \'@RequiresRight (name="MY_RIGHT")\'. But the annotation encountered is: @RequiresRight '.$value);
		}
		
		// Ok, there are additional parameters if we start with a (.
		$splitParamsStrings = explode(",", $vars);
		$splitParamsArray = array();
		foreach ($splitParamsStrings as $string) {
			//$splitParamsStringsTrim[] = trim($string);
			$equalsArr = explode('=', $string);
			if (count($equalsArr) != 2) {
				throw new Exception('Error while reading the @param annotation. Wrong syntax: @param '.$value);
			}
			$splitParamsArray[trim($equalsArr[0])] = trim($equalsArr[1]);
		}

		$this->name = trim($splitParamsArray['name'], '"\'');
		$this->instanceName = $splitParamsArray['instance'];
	}
	
	/**
	 * The name of the right to check
	 */
	protected $name;
	
	/**
	 * The name of the rightsService instance.
	 */
	protected $instanceName;

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
				throw new MoufException("Error using the @RequiresRight annotation: unable to find the RightsService instance named: ".$this->value, null, $e);
			else
				throw new MoufException("Error using the @RequiresRight annotation: by default, this annotation requires a component named 'rightsService', and that extends the RightsServiceInterface interface.", null, $e);
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