<?php

/**
 * An annotation used to allow a method of a controller to be accessible from the web.
 * Syntax: @URL your_url_goes_here
 */
class URLAnnotation 
{
	
	// The complete string after @param
	private $url;
	
	/**
	 * The type the param must match. Can be int, string, etc...
	 *
	 * @var string
	 */
	private $type;
	
	/**
	 * The variable the annotation applies to.
	 *
	 * @var string
	 */
	private $var;
	
	public function __construct($value) {
		$url = $value;
		$this->url = trim($url, " \t()\"'");	
	}
	
	/**
	 * Returns the URL
	 */
	public function getUrl() {
		return $this->url;
	}
	    
}

?>