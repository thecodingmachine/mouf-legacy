<?php
/*
 * Copyright (c) 2012 David Negrier
 * 
 * See the file LICENSE.txt for copying permission.
 */


/**
 * Template interface that should be implemented by any tempalte.
 * THe documentation on using a template can be found at http://www.thecodingmachine.com/ext/splash/doc/templates/index.html
 */
interface TemplateInterface {

	/**
	 * Sets the default scope for files that will be included using addXxxFile() functions.
	 *
	 * @param Scopable $scope
	 */
	public function setDefaultScope(Scopable $scope);

	/**
	 * Returns the default scope for files that will be included using addXxxFile() functions.
	 *
	 * @return Scopable
	 */
	public function getDefaultScope();
	
	/**
	 * Adds some content to the main panel by calling the function passed in parameter.
	 * @return SplashTemplate
	 */
	public function addContentFunction($function);

	/**
	 * Adds some content to the main panel by displaying the text passed in parameter.
	 * @return SplashTemplate
	 */
	public function addContentText($text);

	/**
	 * Adds some content to the main panel by displaying the text in the file passed in parameter.
	 * The scope is the object that will refer the $this.
	 * @return SplashTemplate
	 */
	public function addContentFile($fileName, Scopable $scope = null);
	
	/**
	 * Adds an object extending the HtmlElementInterface interface to the content of the template.
	 *
	 * @param HtmlElementInterface $element
	 * @return SplashTemplate
	 */
	public function addContentHtmlElement(HtmlElementInterface $element);
	
	/**
	 * Adds some content to the header panel by calling the function passed in parameter.
	 * @return SplashTemplate
	 */
	public function addHeaderFunction($function);

	/**
	 * Adds some content to the header panel by displaying the text passed in parameter.
	 * @return SplashTemplate
	 */
	public function addHeaderText($text);

	/**
	 * Adds some content to the header panel by displaying the text in the file passed in parameter.
	 * The scope is the object that will refer the $this.
	 * @return SplashTemplate
	 */
	public function addHeaderFile($fileName, Scopable $scope = null);
	
	/**
	 * Adds an object extending the HtmlElementInterface interface to the header of the template.
	 *
	 * @param HtmlElementInterface $element
	 * @return SplashTemplate
	 */
	public function addHeaderHtmlElement(HtmlElementInterface $element);
	
	/**
	 * Adds some content to the left panel by calling the function passed in parameter.
	 * @return SplashTemplate
	 */
	public function addLeftFunction($function);

	/**
	 * Adds some content to the left panel by displaying the text passed in parameter.
	 * @return SplashTemplate
	 */
	public function addLeftText($text);

	/**
	 * Adds some content to the left panel by displaying the text in the file passed in parameter.
	 * The scope is the object that will refer the $this.
	 * @return SplashTemplate
	 */
	public function addLeftFile($fileName, Scopable $scope = null);
	
	/**
	 * Adds an object extending the HtmlElementInterface interface to the left of the template.
	 *
	 * @param HtmlElementInterface $element
	 * @return SplashTemplate
	 */
	public function addLeftHtmlElement(HtmlElementInterface $element);
	
	/**
	 * Adds some content to the right panel by calling the function passed in parameter.
	 * @return SplashTemplate
	 */
	public function addRightFunction($function);

	/**
	 * Adds some content to the right panel by displaying the text passed in parameter.
	 * @return SplashTemplate
	 */
	public function addRightText($text);

	/**
	 * Adds some content to the right panel by displaying the text in the file passed in parameter.
	 * The scope is the object that will refer the $this.
	 * @return SplashTemplate
	 */
	public function addRightFile($fileName, Scopable $scope = null);
	
	/**
	 * Adds an object extending the HtmlElementInterface interface to the right of the template.
	 *
	 * @param HtmlElementInterface $element
	 * @return SplashTemplate
	 */
	public function addRightHtmlElement(HtmlElementInterface $element);
	
	/**
	 * Adds some content to the <head> tag by calling the function passed in parameter.
	 * @return SplashTemplate
	 */
	public function addHeadFunction($function);

	/**
	 * Adds some content to the <head> tag by displaying the text passed in parameter.
	 * @return SplashTemplate
	 */
	public function addHeadText($text);

	/**
	 * Adds some content to the <head> tag by displaying the text in the file passed in parameter.
	 * The scope is the object that will refer the $this.
	 * @return SplashTemplate
	 */
	public function addHeadFile($fileName, Scopable $scope = null);
	
	/**
	 * Adds an object extending the HtmlElementInterface interface to the <head> tag of the template.
	 *
	 * @param HtmlElementInterface $element
	 * @return SplashTemplate
	 */
	public function addHeadHtmlElement(HtmlElementInterface $element);
	
	/**
	 * Sets the title for the HTML page
	 * @return SplashTemplate
	 */
	public function setTitle($title);

	/**
	 * Gets the title for the HTML page
	 */
	public function getTitle();

	/**
	 * Adds a css file to the list of css files loaded.
	 * @return SplashTemplate
	 */
	public function addCssFile($cssUrl);

	/**
	 * Adds a css file to the list of css files loaded.
	 * @return SplashTemplate
	 */
	public function addJsFile($jsUrl);

	/**
	 * Draws the Admindeo page by calling the template in /views/template/admindeo.php
	 */
	public function draw();
}
?>