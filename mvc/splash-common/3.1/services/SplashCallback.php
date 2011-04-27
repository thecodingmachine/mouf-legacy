<?php

/**
 * A callback used to access a page.
 * 
 * @author David
 */
class SplashCallback {
	
	public $url;
	
	public $controllerInstanceName;
	
	public $methodName;
	
	public $title;
	
	public $comment;
	
	public $fullComment;
	
	public function __construct($url, $controllerInstanceName, $methodName, $title, $comment, $fullComment = null) {
		$this->url = $url;
		$this->controllerInstanceName = $controllerInstanceName;
		$this->methodName = $methodName;
		$this->title = $title;
		$this->comment = $comment;
		$this->fullComment = $fullComment;
	}
}

?>