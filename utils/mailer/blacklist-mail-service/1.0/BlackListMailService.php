<?php

/**
 * This class does not send mails; Instead, it modifies	your mail to add a "unsubscribe this mailing list" link at the end of your mail,
 * then forwards your mail to a "real" mail service that will indeed send the mail. 
 * This will allow your users to unsubscribe from the mailing list. Any attempt to send a mail to this user later
 * will be rejected.<br/>
 * <br/>
 * <p>The blacklisted mails are stored in the 'outgoing_mail_blacklist' table. If you pass an instance of DBMailInterface 
 * (instead of simply a MailInterface), you can add a category and a type to your mail.When a mail is blacklisted, it can be blacklisted
 * for the whole application, or only for a category or type of mails.</p>
 * 
 * <p>The BlackListMailService will append a "unsubscribe this mailing list" link only if there is one and only one "To" recipient to the mail.</p>
 * 
 * <p>Finally, the text of the link can be fully translated in many languages using the Fine translation framework.</p>
 * 
 * @Component
 */
class BlackListMailService implements MailServiceInterface {
	
	/**
	 * The text of the link that should be appended in any HTML mail.
	 * It should contain "__UNSUBSCRIBE_LINK__". This text will be replaced by the URL.
	 * 
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $unsubscribeHtmlText;
	
	/**
	 * The text of the link that should be appended in any text mail.
	 * It should contain "__UNSUBSCRIBE_LINK__". This text will be replaced by the URL.
	 * If empty, the $unsubscribeHtmlText will be used, with a stripped down version of the HTML and the
	 * link will be added afterwards.
	 * 
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $unsubscribeText;
	
	/**
	 * Put the "needle" text in your mail, and it will be replaced by the unsubscribe text.
	 * If no needle text is found (or if needle is empty), the text will be appended
	 * at the end of the mail. 
	 * 
	 * @Property
	 * @var string
	 */
	public $needle = "__UNSUBSCRIBE_TEXT__";
	
	/**
	 * The service that should be used to translate the unsubscribe text link.
	 * If null, no translation will happen.
	 * 
	 * @Property
	 * @var LanguageTranslationInterface
	 */
	public $languageTranslationService;

	/**
	 * The link to the unsubscribe controller.
	 * If empty, the default link will be used.
	 * The link should contain "__UNIQUE_KEY__".
	 * This will be replaced by a unique key describing the mail (and therefore the "To:" address).
	 * If it does not start with "http://", the link is supposed to be relative to ROOT_URL.
	 * 
	 * @Property
	 * @var string
	 */
	public $link = "plugins/utils/mailer/blacklist-mail-service/1.0/direct/unsubscribeConfirmBlock.php?id=__UNIQUE_KEY__";
	
	/**
	 * The host name for the server (plus the port if not 80).
	 * If set, it will be used instead of $_SERVER['HTTP_HOST'] variable.
	 * This can be very useful if you plan to send mail using the PHP CLI (for instance using a CRON task).
	 * Indeed, the $_SERVER['HTTP_HOST'] is not set if you use the PHP CLI.
	 * 
	 * For instance: mouf-php.com is a valid hostname, or exemple.com:8080 is also valid.
	 * 
	 * @Property
	 * @var string
	 */
	public $serverName;
	
	/**
	 * The datasource to use.
	 *
	 * @Property
	 * @Compulsory
	 * @var DB_ConnectionInterface
	 */
	public $datasource;
	
	/**
	 * The DB mail service to forward the call to.
	 * <p>We must use a DBMailService behind the blacklist service. This is compulsory
	 * because we must save what mail has been sent to whom, whith a unique hash key.
	 * This way, when you call the Blacklist mail service, it will add the unsubscribe link with
	 * an unguessable hashkey, then run the DB mail service that will store the key. The DBMailService
	 * should actually forward the mail to a real service.</p>
	 * 
	 * @Property
	 * @var DBMailService
	 */
	public $forwardTo;
	
	/**
	 * The logger to use.
	 *
	 * @Property
	 * @var LogInterface
	 */
	public $log;
	
	
	
	/**
	 * Sends the mail passed in parameter to the database and eventually forwards the mail.
	 *
	 * @param MailInterface $mail The mail to send.
	 * @return boolean Returns true if the mail was sent, and false if the mail was not sent because the user has unsubscribed.
	 */
	public function send(MailInterface $mail) {
		
		if ($mail instanceof DBMailInterface) {
			$category = $mail->getCategory();
			$type = $mail->getType();
			$uniqueKey = $mail->getHashKey();
		} else {
			$category = null;
			$type = null;
			$uniqueKey = null;
		}
		
		if ($uniqueKey == null) {
			$uniqueKey = self::generateUniqueKey();
		}
		
		$toRecipients = $mail->getToRecipients();
		
		if (count($toRecipients) != 1) {
			// If there is more than 1 recipient, let's just ignore the BlackListMailService
			// It can only be applied when a mail has 1 and only 1 recipient.
			$this->forwardTo->send($blackListMail);
			return true;
		}
		/* @var $toRecipient MailAddressInterface */
		$toRecipient = $toRecipients[0];
		
		// If the user is blacklisted, let's not send the mail.
		if ($this->isBlackListed($toRecipient->getMail(), $category, $type)) {
			return false;
		}
		
		
		$serverName = $this->serverName;
		if (empty($serverName)) {
			$serverName = $_SERVER['HTTP_HOST'];
		}
		
		$count = 0;
		$link = $this->link;
		$link = str_replace("__UNIQUE_KEY__", $uniqueKey, $link, $count);
		if ($count == 0) {
			throw new BlackListMailServiceException("Error: the BlackListMailService is poorly configured. The \$link property should contain the __UNIQUE_KEY__ placeholder that will be used to insert the unique key of the mail.");
		}
		
		if (strpos($link, "http://") !== 0 &&
			strpos($link, "https://") !== 0) {
		
			if (strpos($link, "/") !== 0) {
				$link = ROOT_URL.$link;
			}
			$link = "http://".$serverName.$link;
		}
		
		if ($this->languageTranslationService) {
			$unsubscribeHtmlText = $this->languageTranslationService->getTranslation($this->unsubscribeHtmlText);
		} else {
			$unsubscribeHtmlText = $this->unsubscribeHtmlText;
		}
		$unsubscribeHtmlText = str_replace("__UNSUBSCRIBE_LINK__", $link, $unsubscribeHtmlText, $count);
		if ($count == 0) {
			throw new BlackListMailServiceException("Error: the BlackListMailService is poorly configured. The \$unsubscribeHtmlText property should contain the __UNIQUE_KEY__ placeholder that will be used to insert the unique key of the mail.");
		}
		
		if ($this->languageTranslationService) {
			$unsubscribeText = $this->languageTranslationService->getTranslation($this->unsubscribeText);
		} else {
			$unsubscribeText = $this->unsubscribeText;
		}
		if (empty($unsubscribeText)) {
			$unsubscribeText = stripslashes($this->unsubscribeHtmlText);
		}
		$unsubscribeText = str_replace("__UNSUBSCRIBE_LINK__", $link, $unsubscribeText, $count);
		if ($count == 0) {
			$unsubscribeText .= "\n".$this->link;
		}
		
		$blackListMail = new BlackListMail($mail, $unsubscribeHtmlText, $unsubscribeText, $this->needle, $uniqueKey);
		

		if ($this->log) {
			$this->log->debug("Adding unsubscribe link to mail. Mail subject: ".$mail->getTitle());
		}
		
		$this->forwardTo->send($blackListMail);
		return true;
	}
	
	/**
	 * Generates a unique key
	 * @return string
	 */
	private static function generateUniqueKey() {
		$length = 30;
		$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
		$string = '';
		
		for ($p = 0; $p < $length; $p++) {
			$string .= $characters[mt_rand(0, strlen($characters)-1)];
		}
		
		return $string;
	}
	
	/**
	 * Inserts one record into the outgoing_mail_addresses table.
	 * 
	 * @param int $mailId
	 * @param MailAddressInterface $mailAddress
	 * @param string $role
	 */
	private function insertIntoBlackListMailAddresses($mailId, MailAddressInterface $mailAddress, $role) {
		$sql = "INSERT INTO `outgoing_mail_addresses` (outgoing_mail_id, mail_address, mail_address_name, role) VALUES (";
		$sql .= "$mailId, ";
		$sql .= $this->datasource->quoteSmart($mailAddress->getMail()).", ";
		$sql .= $this->datasource->quoteSmart($mailAddress->getDisplayAs()).", ";
		$sql .= $this->datasource->quoteSmart($role);
		$sql .= ")";
			
		$this->datasource->exec($sql);
	}
	
	/**
	 * Querys the mails blacklist.
	 * Returns a table of rows, applying some filter if asked to.
	 * Each row is a table containing:
	 * {
	 * 	category=>
	 * 	mail_type=>
	 * 	address=>
	 * 	black_list_date=>date in Y-m-d H:i:s format
	 * }
	 * 
	 * @param string $sortby
	 * @param string $sortorder
	 * @param int $offset
	 * @param int $limit
	 * @param string $fullTextSearch
	 * @param string $title
	 * @param string $from
	 * @param string $to
	 * @param string $category
	 * @param string $type
	 */
	public function getMailsBlackList($sortby='', $sortorder = "ASC", $offset = 0, $limit = 100, $fullTextSearch = null, $mailAddress = null, $category = null, $type = null) {
		
		$whereArr = array();
		if ($category) {
			$whereArr[] = " category LIKE ".$this->datasource->quoteSmart("%".$category."%");
		}
		if ($type) {
			$whereArr[] = " mail_type LIKE ".$this->datasource->quoteSmart("%".$type."%");
		}
		if ($mailAddress) {
			$whereArr[] = " mail_address LIKE ".$this->datasource->quoteSmart("%".$mailAddress."%");
		}
		if ($fullTextSearch) {
			$whereArr[] = " category LIKE ".$this->datasource->quoteSmart("%".$fullTextSearch."%");
			$whereArr[] = " mail_type LIKE ".$this->datasource->quoteSmart("%".$fullTextSearch."%");
			$whereArr[] = " mail_address LIKE ".$this->datasource->quoteSmart("%".$fullTextSearch."%");
			$whereArr[] = " blacklist_date LIKE ".$this->datasource->quoteSmart("%".$fullTextSearch."%");
		}
		
		$where = "";
		if ($whereArr) {
			$where = " WHERE ".implode(" OR ", $whereArr);
		}
		
		$sql = "SELECT * 
			FROM `outgoing_mail_blacklist` 
			$where
			ORDER BY $sortby $sortorder";
		// TODO: think about SQL injection in sortby and sortorder
		
		return $this->datasource->getAll($sql, PDO::FETCH_ASSOC, null, $offset, $limit);
	}
	
	/**
	 * Returns a DBMail object representing the mail.
	 * 
	 * @param int $mailId
	 * @throws DBMailServiceException
	 * @return DBMail
	 */
	public function getMail($mailId) {
		$sql = "SELECT *
					FROM `outgoing_mails` om 
				WHERE id = ".$this->datasource->quoteSmart($mailId);
		
		return $this->getMailBySql($sql);
	}
	
	/**
	 * Returns true if the mail address $mailAddress asked to be black listed.
	 * You can optionnally pass a category and a mail type if you want to check for a category or a type.
	 * In this case the function will return true if the user asked to be unsubscribed for this mailing list in particular, or for all mailing lists. 
	 * 
	 * @param string $mailAddress
	 * @param string $category
	 * @param string $type
	 */
	public function isBlackListed($mailAddress, $category = null, $type = null) {
		$sql = "SELECT * FROM outgoing_mail_blacklist WHERE mail_address = ".$this->datasource->quoteSmart($mailAddress)." AND (category IS NULL";
		if ($category) {
			$sql .= " OR category = ".$this->datasource->quoteSmart($category);
		}
		$sql .= ") AND (mail_type IS NULL ";
		if ($type) {
			$sql .= " OR mail_type = ".$this->datasource->quoteSmart($type);
		}
		$sql .= ")";
		
		$mailsArr = $this->datasource->getAll($sql);
		if (count($mailsArr) == 0) {
			return false;
		}
		return true;
	}
	
	/**
	 * Unsubscribes the mail address $mailAddress.
	 * You can optionnally pass a category and a mail type if you want to unsubscribe only from a category or a type. 
	 * 
	 * @param string $mailAddress
	 * @param string $category
	 * @param string $type
	 */
	public function unsubscribe($mailAddress, $category = null, $type = null) {
		$isAlreadyBlackListed = $this->isBlackListed($mailAddress, $category, $type);
		if (!$isAlreadyBlackListed) {
			$sql = "INSERT INTO outgoing_mail_blacklist (mail_address, category, mail_type) VALUES (".$this->datasource->quoteSmart($mailAddress).",
					".$this->datasource->quoteSmart($category).", ".$this->datasource->quoteSmart($type).");";
			
			$this->datasource->exec($sql);
		}
	}
	
	/**
	 * Removes the user from the blacklist.
	 * 
	 * @param string $mailAddress
	 * @param string $category
	 * @param string $type
	 */
	public function cancelUnsubscribe($mailAddress, $category = null, $type = null) {
		$sql = "DELETE FROM outgoing_mail_blacklist WHERE mail_address = ".$this->datasource->quoteSmart($mailAddress)."
					AND ";
		if ($category) {
			$sql .= "category = ".$this->datasource->quoteSmart($category)." ";
		} else {
			$sql .= "category IS NULL ";
		}
		$sql .= "AND ";
		if ($type) {
			$sql .= "mail_type = ".$this->datasource->quoteSmart($type)." ";
		} else {
			$sql .= "mail_type IS NULL ";
		}

		return $this->datasource->exec($sql);
	}
}
?>