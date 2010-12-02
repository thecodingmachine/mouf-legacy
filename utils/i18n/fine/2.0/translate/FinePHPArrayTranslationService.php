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
	 * Detection language object
	 * 
	 * @var LanguageDetectionInterface
	 */
	private $msg = null;
	
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
	 * Retrieve the translation of code or message.
	 * Check in the $msg variable if the key exist to return the value. If this message doesn't exist, it return a link to edit it.
	 * 
	 * @see plugins/utils/i18n/fine/2.0/translate/LanguageTranslationInterface::getTranslation()
	 */
	public function getTranslation($message) {
		$language = $this->languageDetection->getLanguage();
		
		if($this->msg_edition_mode === null)
			$this->msg_edition_mode = isset($_SESSION["FINE_MESSAGE_EDITION_MODE"])?$_SESSION["FINE_MESSAGE_EDITION_MODE"]:false;

		$args = func_get_args();
	
		if($this->msg === null)
			$this->retrieveMessages($language);
		
		if (isset($this->msg[$message])) {
			$value = $this->msg[$message];
			for ($i=1;$i<count($args);$i++){
				$value = str_replace('{'.($i-1).'}', plainstring_to_htmlprotected($args[$i]), $value);
			}
		} else {
			$value = "???".$message;
			for ($i=1;$i<count($args);$i++){
				$value .= ", ".plainstring_to_htmlprotected($args[$i]);
			}
			$value .= "???";
		}
	
		if ($this->msg_edition_mode) {
			if ($this->myInstanceName == null) {
				$this->myInstanceName = MoufManager::getMoufManager()->findInstanceName($this);
			}
			$value = $value.' <a href="'.ROOT_URL.'mouf/editLabels/editLabel?key='.$message.'&backto='.urlencode($_SERVER['REQUEST_URI']).'&msginstancename='.urlencode($this->myInstanceName).'">edit</a>';
		}
	
		return $value;
	}
	

	/**
	 * Retrieve the translation of code or message.
	 * Check in the $msg variable if the key exist to return the value. If this message doesn't exist, it return a link to edit it.
	 * 
	 * @see plugins/utils/i18n/fine/2.0/translate/LanguageTranslationInterface::getTranslation()
	 */
	public function getTranslationNoEditMode($message) {
		$language = $this->languageDetection->getLanguage();

		$args = func_get_args();
	
		if($this->msg === null)
			$this->retrieveMessages($language);
		
		if (isset($this->msg[$message])) {
			$value = $this->msg[$message];
			for ($i=1;$i<count($args);$i++){
				$value = str_replace('{'.($i-1).'}', plainstring_to_htmlprotected($args[$i]), $value);
			}
		} else {
			$value = "???".$message;
			for ($i=1;$i<count($args);$i++){
				$value .= ", ".plainstring_to_htmlprotected($args[$i]);
			}
			$value .= "???";
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
	
	
	
	/***************************/
	/****** Edition mode *******/
	/***************************/
	
	/**
	 * The list of all messages in all languages
	 * @var array<string, FineMessageFile>
	 */
	private $messages = array();
	
	
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
	 * @return FineMessageFile The message file for the current user.
	 */
	public function getMessageFileForCurrentUser() {
		$messageFile = new FineMessageFile();

		$language = $this->getLanguage();
		if ($language == 'default') {
			$messageFile->load(ROOT_PATH.$this->i18nMessagePath."message.php");
		} else {
			$messageFile->load(ROOT_PATH.$this->i18nMessagePath."message_".$this->languageDetection->getLanguage().".php");
		}
		return $messageFile;
	}

	/**
	 * @return FineMessageFile The message file for the current user.
	 */
	public function getMessageFileForLanguage($language) {
		if (isset($this->messages[$language])) {
			return $this->messages[$language];
		}

		$messageFile = new FineMessageFile();
		if ($language == 'default') {
			$messageFile->load(ROOT_PATH.$this->i18nMessagePath."message.php");
		} else {
			if (file_exists(ROOT_PATH.$this->i18nMessagePath."message_".$language.".php")) {
				$messageFile->load(ROOT_PATH.$this->i18nMessagePath."message_".$language.".php");
			} else {
				return null;
			}
		}
		$this->messages[$language] = $messageFile;
		return $messageFile;
	}

	/**
	 * Load all messages
	 */
	public function loadAllMessages() {
		$files = glob(ROOT_PATH.$this->i18nMessagePath.'message*.php');

		foreach ($files as $file) {
			$base = basename($file);
			if ($base == "message.php") {
				$messageFile = new FineMessageFile();
				$messageFile->load($file);
				$this->messages['default'] = $messageFile;
			} else {
				$phpPos = strpos($base, '.php');
				$language = substr($base, 8, $phpPos-8);
				$messageFile = new FineMessageFile();
				$messageFile->load($file);
				$this->messages[$language] = $messageFile;
			}
		}
	}

	public function getMessageForAllLanguages($key) {
		$messageArray = array();
		foreach ($this->messages as $language=>$messageFile) {
			$messageArray[$language] = $messageFile->getMessage($key);
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
}