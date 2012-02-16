<?php

/**
 * The DefaultWebLibraryRenderer class is the default implementation of the WebLibraryRendererInterface interface
 *  
 * <p>It is in charge of "renderint" the HTML of a web library.</p> 
 * <p>A web library can be made of:</p>
 * <ul>
 * 	<li>CSS files</li>
 * 	<li>Javascript files</li>
 * 	<li>Any other additional scripts.</li>
 * </ul>
 * 
 * <p>For performance purpose, CSS files should be rendered first, then JS files.
 * This is why this class has a toCssHtml and toJsHtml method instead of only one toHtml method.</p>
 * 
 * @author David Négrier
 */
class DefaultWebLibraryRenderer implements WebLibraryRendererInterface {
	
	/**
	 * Renders the CSS part of a web library.
	 *
	 * @param WebLibrary $webLibrary
	 */
	public function toCssHtml(WebLibrary $webLibrary) {
		$files = $webLibrary->getCssFiles();
		foreach ($files as $file) {	
			if(strpos($file, 'http://') === false && strpos($file, 'https://') === false && strpos($file, '/') !== 0) { 
				$url = ROOT_URL.$value;
			} else {
				$url = $value;
			}	
			echo "<link href='$url' rel='stylesheet' type='text/css' />\n";
		}
	}
	
	/**
	 * Renders the JS part of a web library.
	 *
	 * @param WebLibrary $webLibrary
	 */
	public function toJsHtml(WebLibrary $webLibrary) {
		$files = $webLibrary->getJsFiles();
		foreach ($files as $file) {
			if(strpos($file, 'http://') === false && strpos($file, 'https://') === false && strpos($file, '/') !== 0) { 
				$url = ROOT_URL.$value;
			} else {
				$url = $value;
			}	
			echo '<script type="text/javascript" src="'.$url.'"></script>'."\n";
		}
		
	}
	
	/**
	 * Renders any additional HTML that should be outputed below the JS and CSS part.
	 *
	 * @param WebLibrary $webLibrary
	 */
	public function toAdditionalHtml(WebLibrary $webLibrary) {
		return "";
	}
}