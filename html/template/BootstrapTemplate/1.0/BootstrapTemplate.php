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
class BootstrapTemplate extends BaseTemplate  {

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
	 * Left column size
	 *
	 * @Property
	 * @var int
	 */
	public $leftColumnSize = 2;
	
	/**
	 * Content size
	 *
	 * @Property
	 * @var int
	 */
	public $contentSize = 10;
	
	/**
	 * Right column size
	 *
	 * @Property
	 * @var int
	 */
	public $rightColumnSize = 2;
	
	
	/**
	 * Default constructor
	 */
	public function __construct() {
		parent::__construct();
		$this->templateRootUrl = PLUGINS_URL."html/template/BootstrapTemplate/1.0/";
		$this->private_css_files = array($this->templateRootUrl."css/bootstrap.css",$this->templateRootUrl."css/style.css");
		$this->logoImg = "mouf/views/images/MoufLogo.png";
	}


	/**
	 * Draws the Gray page by calling the template in /views/template/gray.php
	 */
	public function draw(){
		header('Content-Type: text/html; charset=utf-8');

		include "views/bootstrap.php";
	}

	/**
	 * Draws the Gray page by calling the template in /views/template/gray.php
	 * Without header !!!
	 */
	public function drawSimple(){
		header('Content-Type: text/html; charset=utf-8');
		$this->header = array();
		include "views/bootstrap.php";
	}
}
?>