<?php

/**
 * This function return the translation of a code or a sentence for a language.
 * The language is selected by the detection class instantiated. This class must by implements the LanguageDetectionInterface. By default for the application, the instance is translateService.
 * The translation is searched in the translation instance. This class must by implements the LanguageTranslationInterface. The getTranslation method return the translation for a key for a language.
 * 
 * 
 * @param $key string Code, sentence or key for the translation
 * @param ... string Parameters of variable elements in the translation. These elements are wrote {0} for the first, {1} for the second ...
 * @return string Return the translation
 */
function iMsg($key) {
	static $translationService = null;
	if ($translationService == null) {
		/* @var $translationService LanguageTranslationInterface */
		$translationService = MoufManager::getMoufManager()->getInstance("translationService");
	}
	
	$args = func_get_args();
	return call_user_func_array(array($translationService, "getTranslation"), $args);	
}

/**
 * Do an echo for the iMsg return
 * 
 * @param string $key
 * @param ... string Parameters of variable elements in the translation. These elements are wrote {0} for the first, {1} for the second ...
 */
function eMsg($key){
	$args = func_get_args();
	echo call_user_func_array("iMsg", $args);
}


/**
 * This function return the translation of a code or a sentence for a language. Moreover this function doesn't displayed the edit link even if the edition is active.
 * The language is selected by the detection class instantiated. This class must by implements the LanguageDetectionInterface. By default for the application, the instance is translateService.
 * The translation is searched in the translation instance. This class must by implements the LanguageTranslationInterface. The getTranslation method return the translation for a key for a language.
 * 
 * 
 * @param $key string Code, sentence or key for the translation
 * @param ... string Parameters of variable elements in the translation. These elements are wrote {0} for the first, {1} for the second ...
 * @return string Return the translation
 */
function iMsgNoEdit($key) {
	static $translationService = null;
	if ($translationService == null) {
		/* @var $translationService LanguageTranslationInterface */
		$translationService = MoufManager::getMoufManager()->getInstance("translationService");
	}
	
	$args = func_get_args();
	return call_user_func_array(array($translationService, "getTranslationNoEditMode"), $args);	
}

/**
 * Do an echo for the iMsgNoEdit return
 * 
 * @param string $key
 * @param ... string Parameters of variable elements in the translation. These elements are wrote {0} for the first, {1} for the second ...
 */
function enMsgNoEdit($key){
	$args = func_get_args();
	echo call_user_func_array("iMsgNoEdit", $args);
}