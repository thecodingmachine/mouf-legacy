<?php 

/**
 * Use fixed language detection if you want to always use the same language in your application.
 * The FixedLanguageDetection class is a utility class that always returns the same
 * language.
 * Use the setLanguage method to set the language it will return.
 * 
 * @author David Negrier
 * @Component
 */
class FixedLanguageDetection implements LanguageDetectionInterface {
	
	/**
	 * The language that will be returned.
	 * 
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $language = "default";
	
	/**
	 * Returns the language to use.
	 * 
	 * @see plugins/utils/i18n/fine/2.1/language/LanguageDetectionInterface::getLanguage()
	 * @return string
	 */
	public function getLanguage() {
		return $this->language;
	}
	
	/**
	 * Sets the language to use.
	 * 
	 * @param string $language
	 */
	public function setLanguage($language) {
		$this->language = $language;
	}
}

?>