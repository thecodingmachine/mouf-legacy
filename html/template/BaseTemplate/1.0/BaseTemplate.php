<?php

/**
 * Base class that can be used by any Splash template.
 * A template only needs to implement the TemplateInterface interface, but this class provides
 * sensible default behaviours for most methods.
 * Classes extending the BaseTemplate only need to implement the "draw" method.
 * 
 */
abstract class BaseTemplate implements TemplateInterface, Scopable {

	/**
	 * The HTML elements that will be displayed on the left of the screen.
	 *
	 * @Property
	 * @var array<HtmlElementInterface>
	 */
	public $left;
	
	/**
	 * The HTML elements that will be displayed on the center of the screen.
	 *
	 * @Property
	 * @var array<HtmlElementInterface>
	 */
	public $content;

	/**
	 * The HTML elements that will be displayed on the right of the screen.
	 *
	 * @Property
	 * @var array<HtmlElementInterface>
	 */
	public $right;
	
	/**
	 * The HTML elements that will be displayed in the header.
	 *
	 * @Property
	 * @var array<HtmlElementInterface>
	 */
	public $header;
	
	/**
	 * The HTML elements that will be displayed in the footer.
	 *
	 * @Property
	 * @var array<HtmlElementInterface>
	 */	
	public $footer;

	/**
	 * The HTML elements that will be written in the <head> tag.
	 *
	 * @Property
	 * @var array<HtmlElementInterface>
	 */	
	public $head;

	/**
	 * The web path to the logo image that will be displayed at the top of the page.
	 * The image height should be 79 px.
	 * The path is relative to the root of the web application.
	 * By default, the "Splash" logo is used.
	 *
	 * @Property
	 * @var string
	 */	
	public $logoImg;

	/**
	 * The title of the HTML page
	 * 
	 * @Property
	 * @var string
	 */
	public $title = "Splash - Your website";

	/**
	 * TODO
	 */
	private $javascript;

	/**
	 * A list of Javascript files to be loaded
	 */
	private $javascript_files;

	/**
	 * A list of css files that are used by the template and that msut be included.
	 */
	protected $private_css_files;
	
	/**
	 * A list of css files to be loaded.
	 * 
	 * @Property
	 * @var array<string>
	 */
	public $css_files;

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
		$this->footer = array();
		$this->head = array();
		$this->javascript = array();
		$this->javascript_files = array();
		$this->private_css_files = array();
		$this->logoImg = null;
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

		$content = new HtmlFromFunction();
		$content->functionPointer = $function;
		$content->parameters = $arguments;
		$this->content[] = $content;
		return $this;
	}

	/**
	 * Adds some content to the main panel by displaying the text passed in parameter.
	 * @return SplashTemplate
	 */
	public function addContentText($text) {
		$content = new HtmlString();
		$content->htmlString = $text;
		$this->content[] = $content;
		return $this;
	}
	
	/**
	 * Adds some content to the main panel by displaying the text in the file passed in parameter.
	 * The scope is the object that will refer the $this.
	 * @return SplashTemplate
	 */
	public function addContentFile($fileName, Scopable $scope = null) {
		//$this->content[] = array("type"=>"file", "name"=>$fileName, "scope"=>$this->getScope($scope));
		$content = new HtmlFromFile();
		$content->fileName = $fileName;
		$content->scope = $scope;
		$this->content[] = $content;
		
		return $this;
	}
	
	/**
	 * Adds an object extending the HtmlElementInterface interface to the content of the template.
	 *
	 * @param HtmlElementInterface $element
	 * @return SplashTemplate
	 */
	public function addContentHtmlElement(HtmlElementInterface $element) {
		$this->content[] = $element;
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

		$content = new HtmlFromFunction();
		$content->functionPointer = $function;
		$content->parameters = $arguments;
		$this->header[] = $content;
		return $this;
	}

	/**
	 * Adds some content to the header panel by displaying the text passed in parameter.
	 * @return SplashTemplate
	 */
	public function addHeaderText($text) {
		$content = new HtmlString();
		$content->htmlString = $text;
		$this->header[] = $content;
		return $this;
	}

	/**
	 * Adds some content to the header panel by displaying the text in the file passed in parameter.
	 * The scope is the object that will refer the $this.
	 * @return SplashTemplate
	 */
	public function addHeaderFile($fileName, Scopable $scope = null) {
		$content = new HtmlFromFile();
		$content->fileName = $fileName;
		$content->scope = $scope;
		$this->header[] = $content;
		
		return $this;
	}
	
	/**
	 * Adds an object extending the HtmlElementInterface interface to the header of the template.
	 *
	 * @param HtmlElementInterface $element
	 * @return SplashTemplate
	 */
	public function addHeaderHtmlElement(HtmlElementInterface $element) {
		$this->header[] = $element;
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

		$content = new HtmlFromFunction();
		$content->functionPointer = $function;
		$content->parameters = $arguments;
		$this->left[] = $content;
		return $this;
	}

	/**
	 * Adds some content to the left panel by displaying the text passed in parameter.
	 * @return SplashTemplate
	 */
	public function addLeftText($text) {
		$content = new HtmlString();
		$content->htmlString = $text;
		$this->left[] = $content;
		//$this->content[] = array("type"=>"text", "text"=>$text);
		return $this;
	}
	
	/**
	 * Adds some content to the left panel by displaying the text in the file passed in parameter.
	 * The scope is the object that will refer the $this.
	 * @return SplashTemplate
	 */
	public function addLeftFile($fileName, Scopable $scope = null) {
		$content = new HtmlFromFile();
		$content->fileName = $fileName;
		$content->scope = $scope;
		$this->left[] = $content;
		
		return $this;
	}
	
	/**
	 * Adds an object extending the HtmlElementInterface interface to the left of the template.
	 *
	 * @param HtmlElementInterface $element
	 * @return SplashTemplate
	 */
	public function addLeftHtmlElement(HtmlElementInterface $element) {
		$this->left[] = $element;
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

		$content = new HtmlFromFunction();
		$content->functionPointer = $function;
		$content->parameters = $arguments;
		$this->right[] = $content;
		return $this;
	}

	/**
	 * Adds some content to the right panel by displaying the text passed in parameter.
	 * @return SplashTemplate
	 */
	public function addRightText($text) {
		$content = new HtmlString();
		$content->htmlString = $text;
		$this->right[] = $content;
		//$this->content[] = array("type"=>"text", "text"=>$text);
		return $this;
	}

	/**
	 * Adds some content to the right panel by displaying the text in the file passed in parameter.
	 * The scope is the object that will refer the $this.
	 * @return SplashTemplate
	 */
	public function addRightFile($fileName, Scopable $scope = null) {
		$content = new HtmlFromFile();
		$content->fileName = $fileName;
		$content->scope = $scope;
		$this->right[] = $content;
		
		return $this;
	}
	
	/**
	 * Adds an object extending the HtmlElementInterface interface to the rgiht of the template.
	 *
	 * @param HtmlElementInterface $element
	 * @return SplashTemplate
	 */
	public function addRightHtmlElement(HtmlElementInterface $element) {
		$this->right[] = $element;
		return $this;
	}

	/**
	 * Adds some content to the footer panel by calling the function passed in parameter.
	 * @return SplashTemplate
	 */
	public function addFooterFunction($function) {
		$arguments = func_get_args();
		// Remove the first argument
		array_shift($arguments);

		$content = new HtmlFromFunction();
		$content->functionPointer = $function;
		$content->parameters = $arguments;
		$this->footer[] = $content;
		return $this;
	}

	/**
	 * Adds some content to the footer panel by displaying the text passed in parameter.
	 * @return SplashTemplate
	 */
	public function addFooterText($text) {
		$content = new HtmlString();
		$content->htmlString = $text;
		$this->footer[] = $content;
		//$this->content[] = array("type"=>"text", "text"=>$text);
		return $this;
	}

	/**
	 * Adds some content to the footer panel by displaying the text in the file passed in parameter.
	 * The scope is the object that will refer the $this.
	 * @return SplashTemplate
	 */
	public function addFooterFile($fileName, Scopable $scope = null) {
		$content = new HtmlFromFile();
		$content->fileName = $fileName;
		$content->scope = $scope;
		$this->footer[] = $content;
		
		return $this;
	}
	
	/**
	 * Adds an object extending the HtmlElementInterface interface to the footer of the template.
	 *
	 * @param HtmlElementInterface $element
	 * @return SplashTemplate
	 */
	public function addFooterHtmlElement(HtmlElementInterface $element) {
		$this->footer[] = $element;
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

		$content = new HtmlFromFunction();
		$content->functionPointer = $function;
		$content->parameters = $arguments;
		$this->head[] = $content;
		return $this;
	}

	/**
	 * Adds some content to the <head> tag by displaying the text passed in parameter.
	 * @return SplashTemplate
	 */
	public function addHeadText($text) {
		$content = new HtmlString();
		$content->htmlString = $text;
		$this->head[] = $content;
		return $this;
	}

	/**
	 * Adds some content to the <head> tag by displaying the text in the file passed in parameter.
	 * The scope is the object that will refer the $this.
	 * @return SplashTemplate
	 */
	public function addHeadFile($fileName, Scopable $scope = null) {
		$content = new HtmlFromFile();
		$content->fileName = $fileName;
		$content->scope = $scope;
		$this->head[] = $content;
		
		return $this;
	}
	
	/**
	 * Adds an object extending the HtmlElementInterface interface to the head of the template.
	 *
	 * @param HtmlElementInterface $element
	 * @return SplashTemplate
	 */
	public function addHeadHtmlElement(HtmlElementInterface $element) {
		$this->head[] = $element;
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
		foreach ($this->private_css_files as $file) {
			$html .= "<link href='$file' rel='stylesheet' type='text/css' />\n";

		}
		if (is_array($this->css_files)) {
			foreach ($this->css_files as $file) {
				$html .= "<link href='".ROOT_URL."$file' rel='stylesheet' type='text/css' />\n";
	
			}
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
			$element->toHtml();
		}
	}

	/**
	 * Draws the Splash page by calling the template in /views/template/splash.php
	 */
	//public abstract function draw();	
}
?>