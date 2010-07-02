<?php
require_once dirname(__FILE__).'/../MessageFile.php';
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
	public function editLabel($key, $backto=null, $language = null) {
		/*if (!SessionUtils::isMessageEditionMode() && !SessionUtils::isAdmin()) {
			throw new ApplicationException('editlabel.editlabel.messageeditionmoderequired.title','editlabel.editlabel.messageeditionmoderequired.text');
		}*/

		LanguageUtils::loadAllMessages();
		if (!$language) {
			$language = LanguageUtils::parseHttpAcceptLanguage();
		}

		$messageFile = LanguageUtils::getMessageFileForLanguage($language);
		
		// If the language file is not defined, let's go back to the default language.
		if ($messageFile == null) {
			$language = "default"; 
			$messageFile = LanguageUtils::getMessageFileForLanguage("default");
		}
		
		$msg = $messageFile->getMessage($key);

		LanguageUtils::loadAllMessages();
		$messagesArray = LanguageUtils::getMessageForAllLanguages($key);
		if (!isset($messagesArray["default"])) {
			$messagesArray["default"] = "";
		}
		unset($messagesArray[$language]);
		

		$this->template->addContentFunction("editLabel", $key, $msg, $language, $messagesArray, false, $backto);
		$this->template->draw();
	}

	/**
	 * @Action
	 */
	public function saveLabel($key, $label, $save = null, $back = null, $backto = null, $language = null) {
		if ($save) {
			if (!$language) {
				$language = LanguageUtils::parseHttpAcceptLanguage();
			}

			$messageFile = LanguageUtils::getMessageFileForLanguage($language);
			$messageFile->setMessage($key, $label);
			$messageFile->save();

			LanguageUtils::loadAllMessages();
			$messagesArray = LanguageUtils::getMessageForAllLanguages($key);
			unset($messagesArray[$language]);

			$this->template->addContentFunction("editLabel", $key, $label, $language, $messagesArray, true, $backto);
			$this->template->draw();
		} else {
			header("Location: ".$backto);
		}
	}

	/**
	 * @Action
	 * //@Admin
	 */
	public function missinglabels() {
		LanguageUtils::loadAllMessages();

		// The array of messages by message, then by language:
		// array(message_key => array(language => message))
		$this->msgs = array();

		$keys = LanguageUtils::getAllKeys();

		$this->languages = LanguageUtils::getSupportedLanguages();

		foreach ($keys as $key) {
			$this->msgs[$key] = LanguageUtils::getMessageForAllLanguages($key);
		}
			
		$this->template->addContentFile(dirname(__FILE__)."/../views/missingLabel.php", $this);
		//$this->template->addContentFunction("missingLabels", $languages, $msgs);
		$this->template->draw();
// TODO: function view missingLabels
	}

	/**
	 * Displays the page that will allow the user to add a new language. 
	 * 
	 * @Action
	 */
	public function supportedLanguages() {
		LanguageUtils::loadAllMessages();
		$this->languages = LanguageUtils::getSupportedLanguages();
		
		$this->template->addContentFile(dirname(__FILE__)."/../views/supportedLanguages.php", $this);
		$this->template->draw();
	}
	
	/**
	 * Adds a language to the list of supported languages
	 *
	 * @Action
	 * @param string $language
	 */
	public function addSupportedLanguage($language) {
		LanguageUtils::loadAllMessages();
		$this->languages = LanguageUtils::getSupportedLanguages();
		
		if (array_search($language, $this->languages) === false) {
			LanguageUtils::createLanguageFile($language);
		}
		
		// Once more to reaload languages list
		LanguageUtils::loadAllMessages();
		$this->languages = LanguageUtils::getSupportedLanguages();
		
		$this->template->addContentFile(dirname(__FILE__)."/../views/supportedLanguages.php", $this);
		$this->template->draw();
	}
}

?>
