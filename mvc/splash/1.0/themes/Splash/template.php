<?php

define("TEMPLATE_ROOT_URL", PLUGINS_URL."mvc/splash/1.0/themes/Splash/");

/**
 * Template class for Splash.
 * This class relies on /views/template/splash.php for the design
 */
class SplashTemplate implements TemplateInterface, Scopable {

	protected $left;
	protected $content;
	protected $right;
	protected $header;

	/**
	 * Elements to write in the <head> tag
	 */
	protected $head;

	/**
	 * The array of parameters passed to the functions called in the template.
	 */
	//protected $params;

	/**
	 * The title of the HTML page
	 */
	protected $title = "Splash - Your website";

	/**
	 * TODO
	 */
	private $javascript;

	/**
	 * A list of Javascript files to be loaded
	 */
	private $javascript_files;

	/**
	 * A list of css files to be loaded.
	 */
	private $css_files;

	/**
	 * The default scope for the files that will be executed using addXxxFile.
	 * If none is specified, the template is used as the default scope.
	 *
	 * @var Scopable
	 */
	private $scope;
	
	/**
	 * Default constructor
	 */
	public function __construct() {
		$this->left = array();
		$this->content = array();
		$this->right = array();
		$this->header = array();
		$this->head = array();
		//$this->params = array();
		$this->javascript = array();
		$this->javascript_files = array();
		$this->css_files = array(TEMPLATE_ROOT_URL."css/reset.css", TEMPLATE_ROOT_URL."css/splash.css", TEMPLATE_ROOT_URL."css/styles.css");
	}
	
	/**
	 * Sets the default scope for files that will be included using addXxxFile() functions.
	 *
	 * @param Scopable $scope
	 */
	public function setDefaultScope(Scopable $scope) {
		$this->scope = $scope;
	}

	/**
	 * Returns the default scope for files that will be included using addXxxFile() functions.
	 *
	 * @return Scopable
	 */
	public function getDefaultScope() {
		return $this->scope;
	}

	/**
	 * Returns the passed scope if passed, or the default scope if any, or the template otherwise.
	 *
	 * @param Scopable $scope
	 * @return unknown
	 */
	private function getScope(Scopable $scope) {
		if ($scope == null) {
			if ($this->scope != null)
				$scope = $this->scope;
			else
				$scope = $this;
		}
		return $scope;
	}
	
	/**
	 * Adds some content to the main panel by calling the function passed in parameter.
	 * @return SplashTemplate
	 */
	public function addContentFunction($function) {
		$arguments = func_get_args();
		// Remove the first argument
		array_shift($arguments);

		$this->content[] = array("type"=>"function", "name"=>$function, "arguments"=>$arguments);
		return $this;
	}

	/**
	 * Adds some content to the main panel by displaying the text passed in parameter.
	 * @return SplashTemplate
	 */
	public function addContentText($text) {
		$this->content[] = array("type"=>"text", "text"=>$text);
		return $this;
	}
	
	/**
	 * Adds some content to the main panel by displaying the text in the file passed in parameter.
	 * The scope is the object that will refer the $this.
	 * @return SplashTemplate
	 */
	public function addContentFile($fileName, Scopable $scope = null) {
		$this->content[] = array("type"=>"file", "name"=>$fileName, "scope"=>$this->getScope($scope));
		return $this;
	}
	
	/**
	 * Adds some content to the header panel by calling the function passed in parameter.
	 * @return SplashTemplate
	 */
	public function addHeaderFunction($function) {
		$arguments = func_get_args();
		// Remove the first argument
		array_shift($arguments);

		$this->header[] = array("type"=>"function", "name"=>$function, "arguments"=>$arguments);
		return $this;
	}

	/**
	 * Adds some content to the header panel by displaying the text passed in parameter.
	 * @return SplashTemplate
	 */
	public function addHeaderText($text) {
		$this->header[] = array("type"=>"text", "text"=>$text);
		return $this;
	}

	/**
	 * Adds some content to the header panel by displaying the text in the file passed in parameter.
	 * The scope is the object that will refer the $this.
	 * @return SplashTemplate
	 */
	public function addHeaderFile($fileName, Scopable $scope = null) {
		$this->header[] = array("type"=>"file", "name"=>$fileName, "scope"=>$this->getScope($scope));
		return $this;
	}
	
	/**
	 * Adds some content to the left panel by calling the function passed in parameter.
	 * @return SplashTemplate
	 */
	public function addLeftFunction($function) {
		$arguments = func_get_args();
		// Remove the first argument
		array_shift($arguments);

		$this->left[] = array("type"=>"function", "name"=>$function, "arguments"=>$arguments);
	}

	/**
	 * Adds some content to the left panel by displaying the text passed in parameter.
	 * @return SplashTemplate
	 */
	public function addLeftText($text) {
		$this->left[] = array("type"=>"text", "text"=>$text);
		return $this;
	}
	
	/**
	 * Adds some content to the left panel by displaying the text in the file passed in parameter.
	 * The scope is the object that will refer the $this.
	 * @return SplashTemplate
	 */
	public function addLeftFile($fileName, Scopable $scope = null) {
		$this->left[] = array("type"=>"file", "name"=>$fileName, "scope"=>$this->getScope($scope));
		return $this;
	}
	
	/**
	 * Adds some content to the right panel by calling the function passed in parameter.
	 * @return SplashTemplate
	 */
	public function addRightFunction($function) {
		$arguments = func_get_args();
		// Remove the first argument
		array_shift($arguments);

		$this->right[] = array("type"=>"function", "name"=>$function, "arguments"=>$arguments);
		return $this;
	}

	/**
	 * Adds some content to the right panel by displaying the text passed in parameter.
	 * @return SplashTemplate
	 */
	public function addRightText($text) {
		$this->right[] = array("type"=>"text", "text"=>$text);
		return $this;
	}

	/**
	 * Adds some content to the right panel by displaying the text in the file passed in parameter.
	 * The scope is the object that will refer the $this.
	 * @return SplashTemplate
	 */
	public function addRightFile($fileName, Scopable $scope = null) {
		$this->right[] = array("type"=>"file", "name"=>$fileName, "scope"=>$this->getScope($scope));
		return $this;
	}

	/**
	 * Adds some content to the <head> tag by calling the function passed in parameter.
	 * @return SplashTemplate
	 */
	public function addHeadFunction($function) {
		$arguments = func_get_args();
		// Remove the first argument
		array_shift($arguments);

		$this->head[] = array("type"=>"function", "name"=>$function, "arguments"=>$arguments);
		return $this;
	}

	/**
	 * Adds some content to the <head> tag by displaying the text passed in parameter.
	 * @return SplashTemplate
	 */
	public function addHeadText($text) {
		$this->head[] = array("type"=>"text", "text"=>$text);
		return $this;
	}

	/**
	 * Adds some content to the <head> tag by displaying the text in the file passed in parameter.
	 * The scope is the object that will refer the $this.
	 * @return SplashTemplate
	 */
	public function addHeadFile($fileName, Scopable $scope = null) {
		$this->head[] = array("type"=>"file", "name"=>$fileName, "scope"=>$this->getScope($scope));
		return $this;
	}
	
	/**
	 * Sets the title for the HTML page
	 * @return SplashTemplate
	 */
	public function setTitle($title) {
		$this->title = $title;
		return $this;
	}

	/**
	 * Gets the title for the HTML page
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Adds a css file to the list of css files loaded.
	 * @return SplashTemplate
	 */
	public function addCssFile($cssUrl) {
		if (array_search($cssUrl, $this->css_files) === false) {
			$this->css_files[] = $cssUrl;
		}
		return $this;
	}

	/**
	 * Adds a css file to the list of css files loaded.
	 * @return SplashTemplate
	 */
	public function addJsFile($jsUrl) {
		if (array_search($jsUrl, $this->javascript_files) === false) {
			$this->javascript_files[] = $jsUrl;
		}
		return $this;
	}

	/**
	 * Returns the HTML that will be embedded in the page for CSS files.
	 */
	protected function getCssFiles() {
		$html = '';
		foreach ($this->css_files as $file) {
			$html .= "<link href='$file' rel='stylesheet' type='text/css' />\n";

		}
		return $html;
	}

	/**
	 * Returns the HTML that will be embedded in the page for Javascript files loaded.
	 */
	protected function getJsFiles() {
		$html = '';
		foreach ($this->javascript_files as $file) {
			$html .= "<script type='text/javascript' src='$file'></script>\n";

		}
		return $html;
	}
	
	/**
	 * Inludes the file (useful to load a view inside the Controllers scope).
	 *
	 * @param unknown_type $file
	 */
	public function loadFile($file) {
		include $file;
	}

	/**
	 * This function draws an array like $left, or $content.
	 * Those arrays can contain text to draw or function to call.
	 */
	protected function drawArray($array) {
		foreach ($array as $element) {
			if ($element["type"] == "function") {
				call_user_func_array($element["name"], $element["arguments"]);
			} else if ($element["type"] == "text") {
				echo $element["text"];
			} else if ($element["type"] == "file") {
				$element["scope"]->loadFile($element["name"]);
			} else {
				trigger_error("Unexpected type in template: ".$element["type"]);
			}
		}
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