<?php
/*
 * Copyright (c) 2012 David Negrier
 * 
 * See the file LICENSE.txt for copying permission.
 */


/**
 * Template class for Mouf.
 * This class relies on /views/mouf.php for the design
 * 
 * @Component
 */
class DarkTemplate extends BaseTemplate  {

	/**
	 * The URL of the favicon, relative to the ROOT_URL.
	 * If empty, no favicon is passed.
	 * 
	 * @Property
	 * @var string
	 */
	public $favIconUrl = "plugins/html/template/MoufTemplate/1.0/images/favicon.png";
	
	protected $templateRootUrl;
	
	/**
	 * Default constructor
	 */
	public function __construct() {
		parent::__construct();
		$this->templateRootUrl = PLUGINS_URL."html/template/DarkTemplate/1.0/";
		$this->private_css_files = array($this->templateRootUrl."css/bootstrap.css",$this->templateRootUrl."css/style.css");
		$this->logoImg = "mouf/views/images/MoufLogo.png";
	}


	/**
	 * Draws the Gray page by calling the template in /views/template/gray.php
	 */
	public function draw(){
		header('Content-Type: text/html; charset=utf-8');

		include "views/dark.php";
	}

	/**
	 * Draws the Gray page by calling the template in /views/template/gray.php
	 * Without header !!!
	 */
	public function drawSimple(){
		header('Content-Type: text/html; charset=utf-8');
		
		$this->header = array();
		include "views/dark.php";
	}
}
?>