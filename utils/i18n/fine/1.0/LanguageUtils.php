<?php

$i18n_lg = LanguageUtils::parseHttpAcceptLanguage();

define("I18N_MESSAGE_PATH", ROOT_PATH.'resources/');

/*if (file_exists(I18N_MESSAGE_PATH.'message_'.$i18n_lg.'.php')){
	require_once I18N_MESSAGE_PATH.'message_'.$i18n_lg.'.php';
}
else{
	require_once I18N_MESSAGE_PATH.'message.php';
}*/


if (file_exists(I18N_MESSAGE_PATH.'message_'.$i18n_lg.'.php')){
	global $msg;
	@include_once I18N_MESSAGE_PATH.'message.php';
	require_once I18N_MESSAGE_PATH.'message_'.$i18n_lg.'.php';
}
else{
	// No error if the file is not found.
	global $msg;
	@include_once I18N_MESSAGE_PATH.'message.php';
}
/*require_once RESOURCES_PATHS.'message.php';
if (file_exists(RESOURCES_PATHS.'message_'.$i18n_lg.'.php')){
	require_once RESOURCES_PATHS.'message_'.$i18n_lg.'.php';
}*/

function iMsg(){
	global $msg, $msg_edition_mode;
	if ($msg_edition_mode === null) {
		$msg_edition_mode = isset($_SESSION["FINE_MESSAGE_EDITION_MODE"])?$_SESSION["FINE_MESSAGE_EDITION_MODE"]:false;
	}

	$args = func_get_args();
	$key = $args[0];

	if (isset($msg[$key])) {
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
		$value = $value.' <a href="'.ROOT_URL.'mouf/editLabels/editLabel?key='.$key.'&backto='.urlencode($_SERVER['REQUEST_URI']).'">edit</a>';
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
			if (file_exists(RESOURCES_PATHS."message_".$language.".php")) {
				$messageFile->load(RESOURCES_PATHS."message_".$language.".php");
			} else {
				return null;
			}
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

	/**
	 * Returns the list of all keys that have been defined in all language files.
	 * loadAllMessages must have been called first.
	 */
	public static function getAllKeys() {
		$all_messages = array();

		// First, let's merge all the arrays in order to get all the keys:
		foreach (self::$messages as $language=>$message) {
			$all_messages = array_merge($all_messages, $message->getAllMessages());
		}

		return array_keys($all_messages);
	}

	/**
	 * Returns the list of languages loaded.
	 */
	public static function getSupportedLanguages() {
		return array_keys(self::$messages);
	}
	
	/**
	 * Creates the file for specified language.
	 *
	 * @param string $language
	 */
	public static function createLanguageFile($language) {
		$file = ROOT_PATH."/resources/message_".$language.".php";
		if (!is_writable($file)) {
			if (!file_exists($file)) {
				// Does the directory exist?
				$dir = dirname($file);
				if (!file_exists($dir)) {
					$result = mkdir($dir, 0755, true);
					
					if ($result == false) {
						$exception = new ApplicationException();
						$exception->setTitle("unable.to.create.directory.title", $dir);
						$exception->setMessage("unable.to.create.directory.text", $dir);
						throw $exception;
					}
				}
			} else {			
				$exception = new ApplicationException();
				$exception->setTitle("unable.to.write.file.title", $file);
				$exception->setMessage("unable.to.write.file.text", $file);
				throw $exception;
			}
		}
		
		$fp = fopen($file, "w");
		fclose($fp);
	}
}

?>