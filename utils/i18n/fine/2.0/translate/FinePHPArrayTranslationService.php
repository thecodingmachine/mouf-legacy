<?php

/**
 * Used to save all translation in a php array.
 * 
 * @Component
 * @author Marc Teyssier
 * @ExtendedAction {"name":"Supported languages", "url":"mouf/editLabels/supportedLanguages", "default":false}
 * @ExtendedAction {"name":"Edit translations", "url":"mouf/editLabels/missinglabels", "default":false}
 */
class FinePHPArrayTranslationService implements LanguageTranslationInterface {
	
	/**
	 * Detection language object
	 * 
	 * @var LanguageDetectionInterface
	 */
	private $languageDetection = null;
	
	/**
	 * Store all message
	 * 
	 * @var array
	 */
	private $msg = null;
	
	/**
	 * Store all missing message (not reference by the user)
	 * 
	 * @var FineMessageLanguage
	 */
	private $msg_missing = null;
	
	/**
	 * Set the path file of resources message. This file contain an array variable named $msg. The key is the code or message id, the value is translation.
	 * example :
	 * $msg["home.title"] = "Hello world";<br />
	 * $msg["home.text"] = "News 1, news 2 and news 3";
	 * 
	 * @Property
	 * @var string
	 */
	public $i18nMessagePath = "resources/";
	
	/**
	 * Store the edition mode from the session
	 * 
	 * @var boolean
	 */
	private $msg_edition_mode = null;
	
	/**
	 * Store the auto mode from the session
	 * 
	 * @var boolean
	 */
	private $msg_auto_mode = null;
	
	/**
	 * Save the object of LanguageDetectionInterface. This class retrieve the language code detected
	 * 
	 * @Property
	 * @param LanguageDetectionInterface $languageDetection
	 */
	public function setLanguageDetection($languageDetection) {
		$this->languageDetection = $languageDetection;
	}
	
	/**
	 * The name of the Mouf component this instance is bound to.
	 * @var string
	 */
	private $myInstanceName;
	
	/**
	 * Store the language code
	 * @var string
	 */
	private $language = null;
	
	/**
	 * Retrieve the translation of code or message.
	 * Check in the $msg variable if the key exist to return the value. This function check all the custom file if the translation is not in the main message_[language].php
	 * If this message doesn't exist, it return a link to edit it.
	 * 
	 * @see plugins/utils/i18n/fine/2.0/translate/LanguageTranslationInterface::getTranslation()
	 */
	public function getTranslation($message) {
		//Display the message without edition
		$args = func_get_args();
		$value = call_user_func_array(array($this, "getTranslationNoEditMode"), $args);	
		
		if ($this->msg_edition_mode) {
			if ($this->myInstanceName == null) {
				$this->myInstanceName = MoufManager::getMoufManager()->findInstanceName($this);
			}
			$value = $value.' <a href="'.ROOT_URL.'mouf/editLabels/editLabel?key='.$message.'&backto='.urlencode($_SERVER['REQUEST_URI']).'&msginstancename='.urlencode($this->myInstanceName).'">edit</a>';
		}
	
		return $value;
	}

	/**
	 * Returns true if a translation is available for the $message key, false otherwise.
	 * 
	 * @param string $message Key of the message
	 * @return bool
	 */
	public function hasTranslation($message) {
		if($this->language === null) {
			$this->initLanguage();
		}
		if($this->msg_edition_mode === null)
			$this->msg_edition_mode = isset($_SESSION["FINE_MESSAGE_EDITION_MODE"])?$_SESSION["FINE_MESSAGE_EDITION_MODE"]:false;
	
		//Load the main file
		if($this->msg === null)
			$this->retrieveMessages($this->language);
			
		//If the translation is not in the main file, load the custom file associated to the message
		if(!isset($this->msg[$message]))
			$this->retrieveCustomMessages($message, $this->language);
		
		if (isset($this->msg[$message])) {
			return true;
		} else {
			return false;
		}
	}
	
	private function initLanguage() {
		if($this->languageDetection)
			$this->language = $this->languageDetection->getLanguage();
		elseif(MoufManager::getMoufManager()->instanceExists("translationService"))
			$this->language = MoufManager::getMoufManager()->getInstance("translationService")->getLanguage();
		else {
			$this->languageDetection = new BrowserLanguageDetection();
			$this->language = $this->languageDetection->getLanguage();
		}
	}

	/**
	 * Retrieve the translation of code or message.
	 * Check in the $msg variable if the key exist to return the value. This function check all the custom file if the translation is not in the main message_[language].php
	 * If this message doesn't exist, it not return a link to edit it.
	 * 
	 * @see plugins/utils/i18n/fine/2.0/translate/LanguageTranslationInterface::getTranslation()
	 */
	public function getTranslationNoEditMode($message) {
		if($this->language === null) {
			$this->initLanguage();
		}

		$args = func_get_args();
	
		//Load the main file
		if($this->msg === null)
			$this->retrieveMessages($this->language);
			
		//If the translation is not in the main file, load the custom file associated to the message
		if(!isset($this->msg[$message]))
			$this->retrieveCustomMessages($message, $this->language);

		if (isset($this->msg[$message])) {
			$value = $this->msg[$message];
			for ($i=1;$i<count($args);$i++){
				$value = str_replace('{'.($i-1).'}', $args[$i], $value);
			}
		} else {
			$value = "???".$message;
			for ($i=1;$i<count($args);$i++){
				$value .= ", ".plainstring_to_htmlprotected($args[$i]);
			}
			$value .= "???";
			if($this->msg_edition_mode === null)
				$this->msg_edition_mode = isset($_SESSION["FINE_MESSAGE_EDITION_MODE"])?$_SESSION["FINE_MESSAGE_EDITION_MODE"]:false;
			if ($this->msg_edition_mode) {
				if($this->msg_auto_mode === null)
					$this->msg_auto_mode = isset($_SESSION["FINE_MESSAGE_AUTO_MODE"])?$_SESSION["FINE_MESSAGE_AUTO_MODE"]:false;
				if($this->msg_auto_mode) {
					if ($this->myInstanceName == null) {
						$this->myInstanceName = MoufManager::getMoufManager()->findInstanceName($this);
					}
					MoufManager::getMoufManager()->getInstance($this->myInstanceName)->getMissingTranslation($message);
				}
			}
		}
	
		
		return $value;
	}
	
	/**
	 * Retrieve array variable store in the language file.
	 * This function include the message resource by default and the language file if the language code is set.
	 * The file contain an array with translation, we retrieve it in a private array msg. 
	 * 
	 * @param string $language Language code
	 * @return boolean
	 */
	private function retrieveMessages($language = null) {
		$msg = array();
		@include_once ROOT_PATH.$this->i18nMessagePath.'message.php';
		if($language) {
			if (file_exists(ROOT_PATH.$this->i18nMessagePath.'message_'.$language.'.php')){
				require_once ROOT_PATH.$this->i18nMessagePath.'message_'.$language.'.php';
			}
		}
		$this->msg = $msg;
	}

	/**
	 * Retrieve array variable store in the custom language file.
	 * This function include the message resource by default and the language file if the language code is set.
	 * The file contain an array with translation for the same key (element before a separator ; , or _), we retrieve it in a private array msg. 
	 * 
	 * @param string $language Language code
	 * @return boolean
	 */
	private function retrieveCustomMessages($key, $language = null) {
		$msg = array();
		
		$strs = preg_split('/[\.\-\_]/', $key);
		$str = strtolower($strs[0]);
		if($str && $str != $key && preg_match('/[a-z0-9\.\-\_]*/', $str)) {
			if (file_exists(ROOT_PATH.$this->i18nMessagePath.'message_custom_default_'.$str.'.php'))
				require_once ROOT_PATH.$this->i18nMessagePath.'message_custom_default_'.$str.'.php';
			if (file_exists(ROOT_PATH.$this->i18nMessagePath.'message_custom_'.$language.'_'.$str.'.php'))
				require_once ROOT_PATH.$this->i18nMessagePath.'message_custom_'.$language.'_'.$str.'.php';
			if(isset($msg))
				$this->msg = array_merge($msg, $this->msg);
		}
	}
	
	
	/***************************/
	/****** Edition mode *******/
	/***************************/
	
	/**
	 * The list of all messages in all languages
	 * @var array<string, FineMessageLanguage>
	 */
	private $messages = array();
	
	/**
	 * Object missing translation
	 * @var FineMessageLanguage
	 */
	private $messages_missing = null;
	
	/**
	 * Returns the language of the browser, or "default" if the language is not supported (no messages_$language.php).
	 */
	public function getLanguage() {
		$language = $this->languageDetection->getLanguage();
		if (file_exists(ROOT_PATH.$this->i18nMessagePath.'message_'.$language.'.php')) {
			return $language;
		} else {
			return "default";
		}
	}

	/**
	 * @return FineMessageLanguage The message file for the current user.
	 */
	public function getLanguageFileForCurrentUser() {
		$messageLanguage = new FineMessageLanguage();

		$language = $this->getLanguage();
		$messageLanguage->load(ROOT_PATH.$this->i18nMessagePath, $language);
		return $messageLanguage;
	}

	/**
	 * @return FineMessageLanguage The message language for the current user.
	 */
	public function getMessageLanguageForLanguage($language) {
		if (isset($this->messages[$language])) {
			return $this->messages[$language];
		}
		
		$messageLanguage = new FineMessageLanguage();
		$messageLanguage->loadAllFile(ROOT_PATH.$this->i18nMessagePath, $language);

		$this->messages[$language] = $messageLanguage;
		return $messageLanguage;
	}

	/**
	 * Load all messages
	 */
	public function loadAllMessages() {
		$files = glob(ROOT_PATH.$this->i18nMessagePath.'message*.php');

		foreach ($files as $file) {
			$base = basename($file);
			if ($base == "message.php") {
				$messageFile = new FineMessageLanguage();
				$messageFile->loadAllFile(ROOT_PATH.$this->i18nMessagePath);
				$this->messages['default'] = $messageFile;
			} else {
				if(strpos($base, '_custom') === false) {
					$phpPos = strpos($base, '.php');
					$language = substr($base, 8, $phpPos-8);
					$messageLanguage = new FineMessageLanguage();
					$messageLanguage->loadAllFile(ROOT_PATH.$this->i18nMessagePath, $language);
					$this->messages[$language] = $messageLanguage;
				}
			}
		}
		
		$messageLanguage = new FineMessageLanguage();
		$messageLanguage->loadMissingFile(ROOT_PATH.$this->i18nMessagePath);
		$this->messages_missing = $messageLanguage;
	}

	/**
	 * Get the message for language.
	 * 
	 * @param string $key
	 * @return array<string, string>
	 */
	public function getMessageForAllLanguages($key, $lang = null) {
		$messageArray = array();
		foreach ($this->messages as $language=>$messageLanguage) {
			if(is_null($lang) || $lang == "")
				$messageArray[$language] = $messageLanguage->getMessage($key);
			elseif($lang == $language)
				$messageArray[$language] = $messageLanguage->getMessage($key);
		}
		return $messageArray;
	}

	/**
	 * Returns the list of all keys that have been defined in all language files.
	 * loadAllMessages must have been called first.
	 */
	public function getAllKeys() {
		$all_messages = array();

		// First, let's merge all the arrays in order to get all the keys:
		foreach ($this->messages as $language=>$message) {
			$all_messages = array_merge($all_messages, $message->getAllMessages());
		}

		return array_keys($all_messages);
	}

	/**
	 * Returns the list of all keys that have been defined in all language files.
	 * loadAllMessages must have been called first.
	 */
	public function getAllKeysMissing() {
		$all_messages = array();

		// First, let's merge all the arrays in order to get all the keys:
		foreach ($this->messages_missing as $language=>$message) {
			$all_messages = array_merge($all_messages, $message->getAllMessages());
		}

		return $this->messages_missing->getAllMissingMessages();
	}
	
	/**
	 * Returns the list of languages loaded.
	 */
	public function getSupportedLanguages() {
		return array_keys($this->messages);
	}
	
	/**
	 * Creates the file for specified language.
	 *
	 * @param string $language
	 */
	public function createLanguageFile($language) {
		if ($language=="default") {
			$file = ROOT_PATH.$this->i18nMessagePath."message.php";
		} else {
			$file = ROOT_PATH.$this->i18nMessagePath."message_".$language.".php";
		}
		
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
	

	/**
	 * Check if the message exist in the missing file
	 * 
	 * @param string $key
	 * @return FineMessageLanguage
	 */
	public function getMissingTranslation($key) {
		if($this->msg_missing === null) {
			$messageLanguage = new FineMessageLanguage();
			$messageLanguage->loadMissingFile(ROOT_PATH.$this->i18nMessagePath);
			$this->msg_missing = $messageLanguage;
		}
		if($this->msg_missing->getMissingMessage($key) === null) {
			$this->msg_missing->setMissingMessage($key);
			$this->msg_missing->saveMissing();
		}
		return $this->msg_missing;
	}
}