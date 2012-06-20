<?php

/**
 * A callback used to access a page.
 * 
 * @author David
 */
class SplashCallback {
	
	public $url;
	
	/**
	 * List of HTTP methods allowed for this callback.
	 * If empty, all methods are allowed.
	 * @var array<string>
	 */
	public $httpMethods;
	
	public $controllerInstanceName;
	
	public $methodName;
	
	public $title;
	
	public $comment;
	
	public $fullComment;
	
	public function __construct($url, $controllerInstanceName, $methodName, $title, $comment, $fullComment = null, $httpMethods = array()) {
		$this->url = $url;
		$this->httpMethods = $httpMethods;
		$this->controllerInstanceName = $controllerInstanceName;
		$this->methodName = $methodName;
		$this->title = $title;
		$this->comment = $comment;
		$this->fullComment = $fullComment;
	}
}

?>