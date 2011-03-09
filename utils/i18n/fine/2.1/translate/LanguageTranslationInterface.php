<?php 

/**
 * Used to translation message
 *  
 * @author Marc Teyssier
 *
 */
interface LanguageTranslationInterface {
	/**
	 * Get the translation of a message or a code
	 * 
	 * @param string $message Message or code to translate
	 * @return string translation
	 */
	public function getTranslation($message);
}