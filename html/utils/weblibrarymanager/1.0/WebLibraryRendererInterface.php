<?php 

/**
 * Classes implementing this interface can "render" the HTML of a web library. 
 * A web library can be made of:
 * - CSS files
 * - Javascript files
 * - Any other additional scripts.
 * 
 * For performance purpose, CSS files should be rendered first, then JS files.
 * This is why this interface has a toCssHtml and toJsHtml method instead of only one toHtml method.
 * 
 * The default implementation of the interface is the DefaultWebLibraryRenderer class, that does most of the job,
 * but you can provide your own implementation if needed.
 * 
 * @author David Négrier
 */
interface WebLibraryRendererInterface {
	
	/**
	 * Renders the CSS part of a web library.
	 * 
	 * @param WebLibrary $webLibrary
	 */
	function toCssHtml(WebLibrary $webLibrary);
	
	/**
	 * Renders the JS part of a web library.
	 *
	 * @param WebLibrary $webLibrary
	 */
	function toJsHtml(WebLibrary $webLibrary);
	
	/**
	 * Renders any additional HTML that should be outputed below the JS and CSS part.
	 *
	 * @param WebLibrary $webLibrary
	 */
	function toAdditionalHtml(WebLibrary $webLibrary);
}