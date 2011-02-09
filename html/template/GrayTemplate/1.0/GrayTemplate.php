<?php

/**
 * Template class for Gray.
 * This class relies on /views/template/gray.php for the design
 * 
 * @Component
 */
class GrayTemplate extends BaseTemplate  {

	protected $templateRootUrl;
	
	/**
	 * Default constructor
	 */
	public function __construct() {
		parent::__construct();
		$this->templateRootUrl = PLUGINS_URL."html/template/GrayTemplate/1.0/";
		$this->private_css_files = array($this->templateRootUrl."css/styles.css");
		$this->logoImg = "plugins/html/template/GrayTemplate/1.0/css/images/logo.png";
	}


	/**
	 * Draws the Gray page by calling the template in /views/template/gray.php
	 */
	public function draw(){
		header('Content-Type: text/html; charset=utf-8');

		include "views/gray.php";
	}
	
}
?>