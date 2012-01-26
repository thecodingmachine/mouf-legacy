<?php

/**
 * The controller used by the db mail service to display mail lists and mails.
 *
 * @Component
 */
class BlackListMailServiceListController extends Controller {

	const PAGE_SIZE = 100;
	
	/**
	 * The default template to use for this controller (will be the mouf template)
	 *
	 * @Property
	 * @Compulsory 
	 * @var TemplateInterface
	 */
	public $template;
	
	/**
	 * The list of mails retrieved.
	 * 
	 * @var array
	 */
	protected $mailList;
	
	public $instanceName;
	public $selfedit;
	public $fullTextSearch;
	public $offset;
	
	/**
	 * Admin page used to list the latest sent mails.
	 *
	 * @Action
	 * @Logged
	 */
	public function defaultAction($instanceName, $fullTextSearch = null, $offset = 0, $selfedit="false") {
		$this->instanceName = $instanceName;
		$this->selfedit = $selfedit;
		$this->fullTextSearch = $fullTextSearch;
		$this->offset = $offset;
		
		$blacklistMailServiceProxy = MoufProxy::getInstance($instanceName, $selfedit=="true");
		/* @var $dbMailServiceProxy DBMailService */
		$this->mailList = $blacklistMailServiceProxy->getMailsBlackList("blacklist_date", "DESC", $offset, self::PAGE_SIZE, $fullTextSearch);
		
		$this->template->addContentFile(dirname(__FILE__)."/../views/list.php", $this);
		$this->template->draw();
	}
	
	/**
	 * @var DBMail
	 */
	protected $mail;
	
	/**
	 * Admin page used to view one sent mail.
	 *
	 * @Action
	 * @Logged
	 */
	public function delete($mailaddress, $category, $type, $instanceName, $fullTextSearch = null, $offset = 0, $selfedit="false") {
		
		if (empty($category)) {
			$category = null;
		}
		if (empty($type)) {
			$type = null;
		}
		
		$blacklistMailServiceProxy = MoufProxy::getInstance($instanceName, $selfedit=="true");
		/* @var $blacklistMailServiceProxy BlackListMailService */
		$nbDeleted = $blacklistMailServiceProxy->cancelUnsubscribe($mailaddress, $category, $type);
		
		header("Location: .?instanceName=".plainstring_to_urlprotected($instanceName)."&selfedit=".plainstring_to_urlprotected($selfedit)."&fullTextSearch=".plainstring_to_urlprotected($fullTextSearch)."&offset=".plainstring_to_urlprotected($offset));
	}
}
