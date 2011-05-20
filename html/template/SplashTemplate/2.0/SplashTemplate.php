<?php

/**
 * Template class for Splash.
 * This class relies on /views/template/splash.php for the design
 * 
 * @Component
 */
class SplashTemplate extends BaseTemplate  {

	/**
	 * Default constructor
	 */
	public function __construct() {
		parent::__construct();
		$this->private_css_files = array(ROOT_URL."plugins/html/template/SplashTemplate/2.0/css/reset.css", ROOT_URL."plugins/html/template/SplashTemplate/2.0/css/splash.css", ROOT_URL."plugins/html/template/SplashTemplate/2.0/css/styles.css");
		$this->logoImg = "plugins/html/template/SplashTemplate/2.0/css/images/logo.png";
	}


	/**
	 * Draws the Splash page by calling the template in /views/template/splash.php
	 */
	public function draw(){
		header('Content-Type: text/html; charset=utf-8');

		include "views/splash.php";
	}
	
}
?>