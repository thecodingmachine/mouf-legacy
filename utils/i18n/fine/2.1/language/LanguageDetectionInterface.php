<?php 

/**
 * Used to detect the website language
 * 
 * @author Marc Teyssier
 *
 */
interface LanguageDetectionInterface {
	
	/**
	 * Function return code language. 2 letters like 'en', 'fr' ...
	 * 
	 * @return string
	 */
	public function getLanguage();
}
