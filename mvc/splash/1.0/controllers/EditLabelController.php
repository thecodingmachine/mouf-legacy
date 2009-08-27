<?php
require_once SPLASH_PATH.'utils/MessageFile.php';
require_once VIEWS_PATHS.'admin/editLabel.php';

class EditLabelController extends Controller {

	/**
	 * Admin page used to enable or disable label edition.
	 *
	 * @Action
	 * @Admin
	 */
	public function defaultAction() {
		$isMessageEditionMode = SessionUtils::isMessageEditionMode();
		$template = new SplashTemplate();
		$template->addContentFunction("enableDisableLabel", $isMessageEditionMode);
		$template->draw();
	}

	/**
	 * Action used to set the mode of label edition.
	 *
	 * @Action
	 * @Admin
	 */
	public function setMode($mode) {
		$editMode = ($mode=="on")?true:false;
		SessionUtils::setMessageEditionMode($editMode);

		$template = new SplashTemplate();
		$template->addContentFunction("enableDisableLabel", $editMode);
		$template->draw();
	}

	/**
	 * @Action
	 */
	public function editLabel($key, $language = null) {
		if (!SessionUtils::isMessageEditionMode()) {
			throw new ApplicationException('editlabel.editlabel.messageeditionmoderequired.title','editlabel.editlabel.messageeditionmoderequired.text');
		}
LanguageUtils::loadAllMessages();
		if (!$language) {
			$language = LanguageUtils::parseHttpAcceptLanguage();
		}

		$messageFile = LanguageUtils::getMessageFileForLanguage($language);
		$msg = $messageFile->getMessage($key);

		LanguageUtils::loadAllMessages();
		$messagesArray = LanguageUtils::getMessageForAllLanguages($key);
		unset($messagesArray[$language]);

		$template = new SplashTemplate();
		$template->addContentFunction("editLabel", $key, $msg, $language, $messagesArray, false);
		$template->draw();
	}

	/**
	 * @Action
	 */
	public function saveLabel($key, $label, $language = null) {
		if (!$language) {
			$language = LanguageUtils::parseHttpAcceptLanguage();
		}

		$messageFile = LanguageUtils::getMessageFileForLanguage($language);
		$messageFile->setMessage($key, $label);
		$messageFile->save();

		LanguageUtils::loadAllMessages();
		$messagesArray = LanguageUtils::getMessageForAllLanguages($key);
		unset($messagesArray[$language]);

		$template = new SplashTemplate();
		$template->addContentFunction("editLabel", $key, $label, $language, $messagesArray, true);
		$template->draw();
	}


}

?>
