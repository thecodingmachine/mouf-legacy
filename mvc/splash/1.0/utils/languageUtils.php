<?php

$i18n_lg = LanguageUtils::parseHttpAcceptLanguage();

if (file_exists(SPLASH_RESOURCES_PATHS.'message_'.$i18n_lg.'.php')){
	require_once SPLASH_RESOURCES_PATHS.'message_'.$i18n_lg.'.php';
}
else{
	require_once SPLASH_RESOURCES_PATHS.'message.php';
}


if (file_exists(RESOURCES_PATHS.'message_'.$i18n_lg.'.php')){
	require_once RESOURCES_PATHS.'message_'.$i18n_lg.'.php';
}
else{
	// No error if the file is not found.
	@include_once RESOURCES_PATHS.'message.php';
}
/*require_once RESOURCES_PATHS.'message.php';
if (file_exists(RESOURCES_PATHS.'message_'.$i18n_lg.'.php')){
	require_once RESOURCES_PATHS.'message_'.$i18n_lg.'.php';
}*/

function iMsg(){
	global $msg, $msg_edition_mode;
	if ($msg_edition_mode === null) {
		$msg_edition_mode = SplashSessionUtils::isMessageEditionMode();
	}

	$args = func_get_args();
	$key = $args[0];

	if (isset($msg[$key])){
		$value = $msg[$key];
		for ($i=1;$i<count($args);$i++){
			$value = str_replace('{'.($i-1).'}', plainstring_to_htmlprotected($args[$i]), $value);
		}
	} else {
		$value = "???".$key;
		for ($i=1;$i<count($args);$i++){
			$value .= ", ".plainstring_to_htmlprotected($args[$i]);
		}
		$value .= "???";
	}

	if ($msg_edition_mode) {
		$value = $value.' <a href="'.ROOT_URL.'EditLabel/editLabel?key='.$key.'">edit</a>';
	}

	return $value;
}

function eMsg(){
	$args = func_get_args();
	echo call_user_func_array("iMsg", $args);
}

class LanguageUtils {

	private static $messages = array();

	/**
	 * Returns the language used for the users browser.
	 */
	public static function parseHttpAcceptLanguage($str=NULL) {
		// getting http instruction if not provided
		$str=$str?$str:(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])?$_SERVER['HTTP_ACCEPT_LANGUAGE']:"");
		// exploding accepted languages
		$langs=explode(',',$str);
		// creating output list
		$accepted=array();
		foreach ($langs as $lang) {
			// parsing language preference instructions
			// 2_digit_code[-longer_code][;q=coefficient]
			preg_match('/([a-z]{1,2})(-([a-z0-9]+))?(;q=([0-9\.]+))?/',$lang,$found);
			//ereg('([a-z]{1,2})(-([a-z0-9]+))?(;q=([0-9\.]+))?',$lang,$found);
			// 2 digit lang code
			$code=isset($found[1])?$found[1]:null;
			// lang code complement
			$morecode=isset($found[3])?$found[3]:null;
			// full lang code
			$fullcode=$morecode?$code.'-'.$morecode:$code;
			// coefficient
			$coef=sprintf('%3.1f',isset($found[5])?$found[5]:'1');
			// for sorting by coefficient
			// adding
			$accepted[]=array('code'=>$code,'coef'=>$coef,'morecode'=>$morecode,'fullcode'=>$fullcode);
		}
		// sorting the list by coefficient desc
		krsort($accepted);
		return $accepted[0]['code'];
	}

	/**
	 * Returns the language of the browser, or "default" if the language is not supported (no messages_$language.php).
	 */
	public static function getLanguage() {
		$language = self::parseHttpAcceptLanguage();
		if (file_exists(RESOURCES_PATHS.'message_'.$language.'.php')) {
			return $language;
		} else {
			return "default";
		}
	}

	/**
	 * @return MessageFile The message file for the current user.
	 */
	public static function getMessageFileForCurrentUser() {
		$messageFile = new MessageFile();

		$language = self::getLanguage();
		if ($language == 'default') {
			$messageFile->load(RESOURCES_PATHS."message.php");
		} else {
			$messageFile->load(RESOURCES_PATHS."message_".self::parseHttpAcceptLanguage().".php");
		}
		return $messageFile;
	}

	/**
	 * @return MessageFile The message file for the current user.
	 */
	public static function getMessageFileForLanguage($language) {
		if (isset(self::$messages[$language])) {
			return self::$messages[$language];
		}

		$messageFile = new MessageFile();
		if ($language == 'default') {
			$messageFile->load(RESOURCES_PATHS."message.php");
		} else {
			$messageFile->load(RESOURCES_PATHS."message_".$language.".php");
		}
		self::$messages[$language] = $messageFile;
		return $messageFile;
	}

	/**
	 * Load all messages
	 */
	public static function loadAllMessages() {
		$files = glob(RESOURCES_PATHS.'message*.php');

		foreach ($files as $file) {
			$base = basename($file);
			if ($base == "message.php") {
				$messageFile = new MessageFile();
				$messageFile->load($file);
				self::$messages['default'] = $messageFile;
			} else {
				$phpPos = strpos($base, '.php');
				$language = substr($base, 8, $phpPos-8);
				$messageFile = new MessageFile();
				$messageFile->load($file);
				self::$messages[$language] = $messageFile;
			}
		}
	}

	public static function getMessageForAllLanguages($key) {
		$messageArray = array();
		foreach (self::$messages as $language=>$messageFile) {
			$messageArray[$language] = $messageFile->getMessage($key);
		}
		return $messageArray;
	}
}

?>
