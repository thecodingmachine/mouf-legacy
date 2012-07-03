<?php 

/**
 * Class providing utility methods (especially regarding translation) to the validators.
 * 
 * @author David Negrier
 */
class ValidatorUtils {
	
	/**
	 * 
	 * @param string $msg
	 */
	public static function translate($msg) {
		$translationService = MoufManager::getMoufManager()->getInstance("validatorsTranslateService");
		/* @var $translationService FinePHPArrayTranslationService */
		
		return call_user_func_array(array($translationService, "getTranslation"), func_get_args());
	}
}
?>