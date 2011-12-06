<?php

define("TEMPLATE_ROOT_URL", PLUGINS_URL."html/template/DanexportTemplate/1.0/");

/**
 * Template class for Danexport.
 * This class relies on /views/template/danexport.php for the design
 * 
 * @Component
 */
class DanexportTemplate extends BaseTemplate  {

	/**
	 * Default constructor
	 */
	public function __construct() {
		parent::__construct();
		$this->private_css_files = array(TEMPLATE_ROOT_URL."css/reset.css", TEMPLATE_ROOT_URL."css/danexport.css", TEMPLATE_ROOT_URL."css/styles.css");
		$this->addJsFile(TEMPLATE_ROOT_URL."js/script.js") ;
		$this->logoImg = "plugins/html/template/DanexportTemplate/1.0/css/images/header.jpg";
	}


	/**
	 * Draws the Danexport page by calling the template in /views/template/danexport.php
	 */
	public function draw(){
		header('Content-Type: text/html; charset=utf-8');
		include "views/danexport.php";
	}
	
}
?>