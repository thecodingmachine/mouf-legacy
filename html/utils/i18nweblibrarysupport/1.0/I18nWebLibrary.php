<?php
/**
 * @Component
 * @author Kevin
 *
 */
class I18nWebLibrary extends WebLibrary{
	
	/**
	 * @Property
	 * @var LanguageDetectionInterface
	 */
	public $languageDetection;
	
	/**
	 * (non-PHPdoc)
	 * Override the parent "getJSFiles"', by replacing the [lang] occurences depending on the detected Language
	 * @see WebLibrary::getJsFiles()
	 */
	public function getJsFiles(){
		$lang = $this->languageDetection->getLanguage();
		
		$files = array();
		foreach (parent::getJsFiles() as $file) {
			$files[] = str_replace("[lang]", $lang, $file);
		}
		
		return $files;
	}
	
}