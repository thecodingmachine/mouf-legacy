<?php

/**
 * This class is an adapter that allows you to use any Drupal template right into your application.
 * The DrupalTemplate component acts like a wrapper around the template and offers a clear, clean object-oriented interface
 * to use.
 * 
 * @Component
 */
class DrupalTemplate extends BaseTemplate  {

	/**
	 * Default constructor
	 */
	public function __construct() {
		parent::__construct();
		//$this->logoImg = "plugins/html/template/SplashTemplate/1.0/css/images/logo.png";
	}
	
	/**
	 * The CacheService is used to store the structure of the Drupal Template file.
	 * Using a cache service is important to avoid loading the .info file on each
	 * page.
	 * 
	 * @Property
	 * @var CacheInterface
	 */
	public $cacheService;
	
	/**
	 * Define if the template.php file must be included or not.
	 * 
	 * @Property
	 * @Compulsory
	 * @var bool
	 */
	public $templateFile=false;

	/**
	 * The directory to the Drupal theme, relative to the root directory of your web application.
	 * The directory should not start with a "/" and should not end with a "/".
	 * 
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $drupalThemeDirectory;
	
	/**
	 * The name of the site, as displayed in the Drupal template.
	 * 
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $siteName;
	
	/**
	 * The slogan of the site, as displayed in the Drupal template.
	 * 
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $siteSlogan;

	/**
	 * The "mission" of the site, as displayed in the Drupal template.
	 * 
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $mission;
	
	/**
	 * The HTML elements that will be displayed on the breadcrumb.
	 *
	 * @Property
	 * @var array<HtmlElementInterface>
	 */
	public $breadcrumb = array();
	
	/**
	 * The "title" of the site, as displayed in the Drupal template.
	 * This is different from the HTML &lt;title&gt; tag.
	 * 
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $drupalTitle;
	
	/**
	 * Some "messages" to be displayed, as displayed in the Drupal template.
	 * 
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $messages;

	/**
	 * The HTML elements that will be displayed at the very end of the HTML page.
	 * Usually used to put some Javascript for trackers.
	 *
	 * @Property
	 * @var array<HtmlElementInterface>
	 */
	public $closure = array();
	
	/**
	 * URL of the front page (used as a link for the logo)
	 * 
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $frontPageUrl;
	
	/**
	 * The set of custom regions (that are not left/right/content...) that are
	 * supported by the Drupal theme.
	 * The name of the region is set as a key, and the content as the value.
	 *
	 * @Property
	 * @var array<string, array<HtmlElementInterface>>
	 */
	public $optionalRegions=array();
	
	/**
	 * The HTML elements that will be displayed on the right of the screen.
	 *
	 * @Property
	 * @var array<HtmlElementInterface>
	 */
	public $region;
	
	
	function addOptionalRegionFunction($region, $function){
		$arguments = func_get_args();
		// Remove the first argument
		array_shift($arguments);

		$content = new HtmlFromFunction();
		$content->functionPointer = $function;
		$content->parameters = $arguments;
		$this->optionalRegions[$region][] = $content;
		return $this;
	}
	
	public function addOptionalRegionText($region, $text) {
		$content = new HtmlString();
		$content->htmlString = $text;
		$this->optionalRegions[$region][] = $content;
		return $this;
	}
	
	public function addOptionalRegionFile($region, $fileName, Scopable $scope = null) {
		$content = new HtmlFromFile();
		$content->fileName = $fileName;
		$content->scope = $scope;
		$this->optionalRegions[$region][] = $content;
		
		return $this;
	}
	

	
	/**
	 * Draws the Splash page by calling the template in /views/template/splash.php
	 */
	public function draw(){
		header('Content-Type: text/html; charset=utf-8');
		global $i18n_lg;
		global $theme;
		
		$this->private_css_files = array(ROOT_URL.$this->drupalThemeDirectory."/style.css");
		
		//get the datas stored in cache
		$cachedValue = $this->cacheService->get("drupaltheme".$this->drupalThemeDirectory);
		//if cache is empty
		if ($cachedValue == null) {
			//Read info file
			$info_file = @glob(ROOT_PATH.$this->drupalThemeDirectory."/*.info");
			$info = drupal_parse_info_file($info_file[0]);
			foreach ($info as $key => $value){
				//set features variables
				if ($key=="features"){
					foreach ($value as $feature){
						$cacheinfo['features'][] = $feature;
						$$feature="";
					}
				} 
				//set the regions
				if ($key=="regions"){
					foreach ($value as $key => $regions){
						$cacheinfo['regions'][] = $key;
						$$key="";
					}
				}
				//add css files
				if ($key=="stylesheets"){
					if (array_key_exists('all', $value)){
						foreach ($value['all'] as $css){
							$this->private_css_files[] = ROOT_URL.$this->drupalThemeDirectory."/".$css;
							$cacheinfo['stylesheets'][] = $css;
						}
					}
	//				if (array_key_exists('print', $value)){
	//					foreach ($value['print'] as $css){
	//						$this->private_css_files[] = ROOT_URL.$this->drupalThemeDirectory."/".$css;
	//					}
	//				}
				}
			}
			//store the datas in cache
			$this->cacheService->set("drupaltheme".$this->drupalThemeDirectory, $cacheinfo);
		} else {
			//if the datas are stored in cache, use it 
			foreach($cachedvalue as $infoname => $value){
				if ($infoname == "features"){
					foreach($infoname as $feature){
						$$feature="";
					}
				}
				if ($infoname == "regions"){
					foreach($infoname as $region){
						$$region="";
					}
				}
				if ($infoname == "stylesheets"){
					foreach ($value['all'] as $css){
						$this->private_css_files[] = ROOT_URL.$this->drupalThemeDirectory."/".$css;
					}
				}
			}
		}
		
		
		
		$language = new stdClass();
		
		if (empty($this->frontPageUrl)) {
			$front_page = ROOT_URL;
		} else {
			$front_page = $this->frontPageUrl;
		}
		
		// FIXME: regarder ce que  fait Drupal et faire pareil!
		$language->language = $i18n_lg;
		$language->dir = "";
		
		// In Splash, head is appendend after the scripts (in Drupal, it is prepended)
		$head = "";
		$head_title = $this->title;
		$styles = $this->getCssFiles();
		$scripts = $this->getJsFiles().$this->getHtmlArray($this->head);;
		
		$logo = $this->logoImg;
		$site_name = $this->siteName;
		$site_slogan = $this->siteSlogan;
		
		$search_box = null;
		$mission = $this->mission;
		
		$header = $this->getHtmlArray($this->header);
		$left = $this->getHtmlArray($this->left);
		$breadcrumb = $this->getHtmlArray($this->breadcrumb);
		$title = $this->drupalTitle;

		// TODO: support tabs
		if (!isset($tabs)) {
    		$tabs = array();
		}
		$tabs2 = array();
		
		if (!empty($this->messages)) {
			$messages = $this->messages;
			$show_messages = true;
		} else {
			$show_messages = false;
		}
		
		$help = null;
		
		$content = $this->getHtmlArray($this->content);
		
		$feed_icons = null;
		
		$right = $this->getHtmlArray($this->right);
		
		$footer_message = null;
		$footer = $this->getHtmlArray($this->footer);
		
		$closure = $this->getHtmlArray($this->closure);
		
		foreach ($this->optionalRegions as $key => $value){
			$$key = $this->getHtmlArray($value);
		}
		
		$old_error_reporting = error_reporting();
		error_reporting($old_error_reporting ^ E_NOTICE);
		if ($this->templateFile){
			if (file_exists(ROOT_PATH.$this->drupalThemeDirectory."/template.php")) include ROOT_PATH.$this->drupalThemeDirectory."/template.php";
		}
	
		if (file_exists(ROOT_PATH.$this->drupalThemeDirectory."/page.tpl.php")) include ROOT_PATH.$this->drupalThemeDirectory."/page.tpl.php";
		error_reporting($old_error_reporting);
		
	}
	
	/**
	 * 
	 * @param $array array<HtmlElementInterface>
	 * @return string
	 */
	private function getHtmlArray($array) {
		ob_start();
		try {
			$this->drawArray($array);
		} catch (Exception $e) {
			ob_end_clean();
			throw $e;
		}
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
}
?>