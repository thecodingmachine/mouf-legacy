<?php
require_once dirname(__FILE__).'/../FineMessageFile.php';
require_once dirname(__FILE__).'/../views/editLabel.php';

/**
 * The controller to edit labels that are to be translated.
 *
 * @Component
 */
class EditLabelController extends Controller {

	/**
	 * The default template to use for this controller (will be the mouf template)
	 *
	 * @Property
	 * @Compulsory 
	 * @var TemplateInterface
	 */
	public $template;
	
	public $isMessageEditionMode = false;
	public $languages;
	public $msgs;
	public $selfedit;
	
	public $msgInstanceName;
	
	/**
	 * Admin page used to enable or disable label edition.
	 *
	 * @Action
	 * //@Admin
	 */
	public function defaultAction() {
		if (isset($_SESSION["FINE_MESSAGE_EDITION_MODE"])) {
			$this->isMessageEditionMode = true;
		}
		$this->template->addContentFile(dirname(__FILE__)."/../views/enableDisableEdition.php", $this);
		$this->template->draw();
	}

	/**
	 * Action used to set the mode of label edition.
	 *
	 * @Action
	 * //@Admin
	 */
	public function setMode($mode) {
		$editMode = ($mode=="on")?true:false;
		//SessionUtils::setMessageEditionMode($editMode);
		if ($editMode) {
			$_SESSION["FINE_MESSAGE_EDITION_MODE"] = true;
			$this->isMessageEditionMode = true;
		} else {
			unset($_SESSION["FINE_MESSAGE_EDITION_MODE"]);
		}

		$this->template->addContentFile(dirname(__FILE__)."/../views/enableDisableEdition.php", $this);
		$this->template->draw();
	}

	/**
	 * @Action
	 */
	public function editLabel($key, $backto=null, $language = null, $msginstancename="translationService" ,$selfedit = "false", $saved = false) {
		/*if (!SessionUtils::isMessageEditionMode() && !SessionUtils::isAdmin()) {
			throw new ApplicationException('editlabel.editlabel.messageeditionmoderequired.title','editlabel.editlabel.messageeditionmoderequired.text');
		}*/
		$this->msgInstanceName = $msginstancename;

		$messagesArray = $this->getAllTranslationsForMessageFromService(($selfedit == "true"), $msginstancename, $key);
		
		if (!$language) {
			$language = "default";
		}

		if (isset($messagesArray[$language])) {
			$msg = $messagesArray[$language];
		} else {
			$msg = "";
		}
		if (!isset($messagesArray["default"])) {
			$messagesArray["default"] = "";
		}
		
		unset($messagesArray[$language]);

		$this->template->addContentFunction("editLabel", $key, $msg, $language, $messagesArray, false, $backto, $msginstancename, $selfedit, $saved);
		$this->template->draw();
	}

	/**
	 * @Action
	 */
	public function saveLabel($key, $label, $language = null, $save = null, $back = null, $backto = null, $msginstancename="translationService" ,$selfedit = "false") {
		$this->msgInstanceName = $msginstancename;
		if ($save) {
			$this->setTranslationsForMessageFromService(($selfedit == "true"), $msginstancename, $key, $label, $language);

			$this->editLabel($key, $backto, $language, $msginstancename, $selfedit, true);
			
		} else {
			header("Location: ".$backto);
		}
	}

	/**
	 * @Action
	 * //@Admin
	 * @param string $name The name of the Mouf instance representing the translator
	 */
	public function missinglabels($name = "translationService", $selfedit = "false") {
		$this->msgInstanceName = $name;
		$this->selfedit =$selfedit;
		
		$array = $this->getAllMessagesFromService(($selfedit == "true"), $name);
		$this->languages = $array["languages"];
		$this->msgs = $array["msgs"];
		
		$this->template->addContentFile(dirname(__FILE__)."/../views/missingLabel.php", $this);
		$this->template->draw();
	}

	/**
	 * Displays the page that will allow the user to add a new language. 
	 * 
	 * @Action
	 */
	public function supportedLanguages($name = "translationService", $selfedit="false") {
		$this->msgInstanceName = $name;
		$this->selfedit =$selfedit;
		
		$array = $this->getAllMessagesFromService(($selfedit == "true"), $name);
		$this->languages = $array["languages"];
		
		
		$this->template->addContentFile(dirname(__FILE__)."/../views/supportedLanguages.php", $this);
		$this->template->draw();
	}
	
	/**
	 * Adds a language to the list of supported languages
	 *
	 * @Action
	 * @param string $language
	 */
	public function addSupportedLanguage($language, $name = "translationService", $selfedit="false") {
		$this->addTranslationLanguageFromService(($selfedit == "true"), $name, $language);
		/*LanguageUtils::loadAllMessages();
		$this->languages = LanguageUtils::getSupportedLanguages();
		
		if (array_search($language, $this->languages) === false) {
			LanguageUtils::createLanguageFile($language);
		}*/
		
		// Once more to reaload languages list
		$this->supportedLanguages($name, $selfedit);
		/*LanguageUtils::loadAllMessages();
		$this->languages = LanguageUtils::getSupportedLanguages();
		
		$this->template->addContentFile(dirname(__FILE__)."/../views/supportedLanguages.php", $this);
		$this->template->draw();*/
	}
	
	
	/**
	 * Returns the list of all messages in all languages by making a CURL call.
	 * 
	 * @param bool $selfEdit
	 * @param string $msgInstanceName
	 * @return array
	 * @throws Exception
	 */
	protected static function getAllMessagesFromService($selfEdit, $msgInstanceName) {

		$url = "http://127.0.0.1:".$_SERVER['SERVER_PORT'].ROOT_URL."plugins/utils/i18n/fine/2.0/direct/get_all_messages.php?msginstancename=".urlencode($msgInstanceName)."&selfedit=".(($selfEdit)?"true":"false");
		
		$response = self::performRequest($url);

		$obj = unserialize($response);
		
		if ($obj === false) {
			throw new Exception("Unable to unserialize message:\n".$response."\n<br/>URL in error: <a href='".plainstring_to_htmlprotected($url)."'>".plainstring_to_htmlprotected($url)."</a>");
		}
		
		return $obj;
	}
	
	/**
	 * Returns all the translation of one key by making a CURL call.
	 * 
	 * @param bool $selfEdit
	 * @param string $msgInstanceName
	 * @param string $key
	 * @return array
	 * @throws Exception
	 */
	protected static function getAllTranslationsForMessageFromService($selfEdit, $msgInstanceName, $key) {

		$url = "http://127.0.0.1:".$_SERVER['SERVER_PORT'].ROOT_URL."plugins/utils/i18n/fine/2.0/direct/get_message_translations.php?msginstancename=".urlencode($msgInstanceName)."&selfedit=".(($selfEdit)?"true":"false")."&key=".urlencode($key);
		
		$response = self::performRequest($url);

		$obj = unserialize($response);
		
		if ($obj === false) {
			throw new Exception("Unable to unserialize message:\n".$response."\n<br/>URL in error: <a href='".plainstring_to_htmlprotected($url)."'>".plainstring_to_htmlprotected($url)."</a>");
		}
		
		return $obj;
	}
	
	/**
	 * Saves the translation for one key and one language using CURL.
	 * 
	 * @param bool $selfEdit
	 * @param string $msgInstanceName
	 * @param string $key
	 * @param string $label
	 * @param string $language
	 * @return boolean
	 * @throws Exception
	 */
	protected static function setTranslationsForMessageFromService($selfEdit, $msgInstanceName, $key, $label, $language) {

		$url = "http://127.0.0.1:".$_SERVER['SERVER_PORT'].ROOT_URL."plugins/utils/i18n/fine/2.0/direct/set_message_translation.php?msginstancename=".urlencode($msgInstanceName)."&selfedit=".(($selfEdit)?"true":"false")."&key=".urlencode($key)."&label=".urlencode($label)."&language=".urlencode($language);
		 
		$response = self::performRequest($url);

		$obj = unserialize($response);
		
		if ($obj === false) {
			throw new Exception("Unable to unserialize message:\n".$response."\n<br/>URL in error: <a href='".plainstring_to_htmlprotected($url)."'>".plainstring_to_htmlprotected($url)."</a>");
		}
		
		return $obj;
	}
	
	/**
	 * Adds a new translation language using CURL.
	 * 
	 * @param bool $selfEdit
	 * @param string $msgInstanceName
	 * @param string $language
	 * @return boolean
	 * @throws Exception
	 */
	protected static function addTranslationLanguageFromService($selfEdit, $msgInstanceName, $language) {

		$url = "http://127.0.0.1:".$_SERVER['SERVER_PORT'].ROOT_URL."plugins/utils/i18n/fine/2.0/direct/create_language_file.php?msginstancename=".urlencode($msgInstanceName)."&selfedit=".(($selfEdit)?"true":"false")."&language=".urlencode($language);
		 
		$response = self::performRequest($url);

		$obj = unserialize($response);
		
		if ($obj === false) {
			throw new Exception("Unable to unserialize message:\n".$response."\n<br/>URL in error: <a href='".plainstring_to_htmlprotected($url)."'>".plainstring_to_htmlprotected($url)."</a>");
		}
		
		return $obj;
	}
	
	private static function performRequest($url) {
		// preparation de l'envoi
		$ch = curl_init();
				
		curl_setopt( $ch, CURLOPT_URL, $url);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt( $ch, CURLOPT_POST, FALSE );
		$response = curl_exec( $ch );
		
		if( curl_error($ch) ) { 
			throw new Exception("An error occured: ".curl_error($ch));
		}
		curl_close( $ch );
		
		return $response;
	}
}

?>
