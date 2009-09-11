<?php

define("TEMPLATE_ROOT_URL", PLUGINS_URL."html/template/SplashTemplate/1.0/");

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
		$this->private_css_files = array(TEMPLATE_ROOT_URL."css/reset.css", TEMPLATE_ROOT_URL."css/splash.css", TEMPLATE_ROOT_URL."css/styles.css");
		$this->logoImg = "plugins/mvc/splash/1.0/themes/Splash/css/images/logo.png";
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