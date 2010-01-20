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
	 * Draws the Splash page by calling the template in /views/template/splash.php
	 */
	public function draw(){
		header('Content-Type: text/html; charset=utf-8');
		global $i18n_lg;

		$this->private_css_files = array(ROOT_URL.$this->drupalThemeDirectory."/style.css");
		
		
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
		
		// FIXME: DRAW FAIT UN OUTPUT, IL FAUDRAIT FAIRE UN OUTPUT BUFFER DU COUP!!!!
		$header = $this->getHtmlArray($this->header);
		$left = $this->getHtmlArray($this->left);
		$breadcrumb = $this->getHtmlArray($this->breadcrumb);
		$title = $this->drupalTitle;

		// TODO: support tabs
		$tabs = null;
		
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

		include ROOT_PATH.$this->drupalThemeDirectory."/page.tpl.php";
	}
	
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