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
	
	public $comment;
	
	public function __construct($url, $controllerInstanceName, $methodName, $comment) {
		$this->url = $url;
		$this->controllerInstanceName = $controllerInstanceName;
		$this->methodName = $methodName;
		$this->comment = $comment;
	}
}

?>