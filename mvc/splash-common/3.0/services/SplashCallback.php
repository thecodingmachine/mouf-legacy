<?php

/**
 * A callback used to access 
 * 
 * @author David
 */
class SplashCallback {
	
	public $url;
	
	public $controllerInstanceName;
	
	public $methodName;
	
	public function __construct($url, $controllerInstanceName, $methodName) {
		$this->url = $url;
		$this->controllerInstanceName = $controllerInstanceName;
		$this->methodName = $methodName;
	}
}

?>