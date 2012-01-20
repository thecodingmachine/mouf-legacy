<?php

require_once 'Zend/Mail.php';
require_once 'Zend/Mail/Transport/Smtp.php';

/**
 * This class does not send mails; instead it stores the mails in database.<br/>
 * <br/>
 * <p>Mails are stored in the <strong>outgoing_mails</strong> table while from, to, cc and bcc fields are stored in the 
 * <strong>outgoing_mail_addresses</strong> table.</p>
 * <p>The stored mails can later be viewed using the Mouf user interface and can also be accessed through
 * methods of this class.</p>
 * <p>If you pass an instance of DBMailInterface (instead of simply a MailInterface), you can add a category and a type
 * to your mail. That could be used to sort sent mails later.
 * </p>
 * 
 * 
 * @Component
 */
class DBMailService implements MailServiceInterface {
	
	/**
	 * The datasource to use.
	 *
	 * @Property
	 * @Compulsory
	 * @var DB_ConnectionInterface
	 */
	public $datasource;
	
	/**
	 * If set, the mail sent will be forwarded to this service after being stored in database.
	 * Put a mail service that actually sends mails in this property.
	 * This way, when you call the DB mail service, it will store the mail in database, then run
	 * the next mail service that will actually send the mail.
	 * 
	 * @Property
	 * @var MailServiceInterface
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
	 */
	public function send(MailInterface $mail) {
		// Transactions have been disabled. This is because we might want to send a mail as part of a wider
		// transaction and that there is only one transaction allowed at a time. 
		/*try {
			$this->datasource->beginTransaction();
			*/
			if ($mail instanceof DBMailInterface) {
				$category = $mail->getCategory();
				$type = $mail->getType();
			} else {
				$category = null;
				$type = null;
			}
			
			$sql = "INSERT INTO `outgoing_mails` (category, mail_type, title, text_body, html_body) VALUES (";
			$sql .= $this->datasource->quoteSmart($category).", ";
			$sql .= $this->datasource->quoteSmart($type).", ";
			$sql .= $this->datasource->quoteSmart($mail->getTitle()).", ";
			$sql .= $this->datasource->quoteSmart($mail->getBodyText()).", ";
			$sql .= $this->datasource->quoteSmart($mail->getBodyHtml());
			$sql .= ")";
			$this->datasource->exec($sql);
			
			$mailId = $this->datasource->getInsertId('outgoing_mails', 'id');
			
			$fromAddress = $mail->getFrom();
			$this->insertIntoOutgoingMailAddresses($mailId, $fromAddress, 'from');
			
			$toRecipients = $mail->getToRecipients();
			foreach ($toRecipients as $toRecipient) {
				$this->insertIntoOutgoingMailAddresses($mailId, $toRecipient, 'to');
			}
			
			$ccRecipients = $mail->getCcRecipients();
			foreach ($ccRecipients as $ccRecipient) {
				$this->insertIntoOutgoingMailAddresses($mailId, $ccRecipient, 'cc');
			}
			
			$bccRecipients = $mail->getBccRecipients();
			foreach ($bccRecipients as $bccRecipient) {
				$this->insertIntoOutgoingMailAddresses($mailId, $bccRecipient, 'bcc');
			}
			
					
			// Let's log the mail:
			$recipients = array_merge($mail->getToRecipients(), $mail->getCcRecipients(), $mail->getBccRecipients());
			$recipientMails = array();
			foreach ($recipients as $recipient) {
				$recipientMails[] = $recipient->getMail();
			}
			if ($this->log) {
				$this->log->debug("Storing mail to ".implode(", ", $recipientMails)." in database. Mail subject: ".$mail->getTitle());
			}
			
			if ($this->forwardTo) {
				$this->forwardTo->send($mail);
			}
		
		/*	$this->datasource->commit();
		} catch (Exception $e) {
			// Note: if an exception is thrown while logging or forwarding the mail, the database will be roll-backed
			try {
				$this->datasource->rollback();
			} catch (Exception $e) {
				// Ignore rollback error, we want to know what went wrong before.
			}
			throw $e;
		}*/
	}
	
	/**
	 * Inserts one record into the outgoing_mail_addresses table.
	 * 
	 * @param int $mailId
	 * @param MailAddressInterface $mailAddress
	 * @param string $role
	 */
	private function insertIntoOutgoingMailAddresses($mailId, MailAddressInterface $mailAddress, $role) {
		$sql = "INSERT INTO `outgoing_mail_addresses` (outgoing_mail_id, mail_address, mail_address_name, role) VALUES (";
		$sql .= "$mailId, ";
		$sql .= $this->datasource->quoteSmart($mailAddress->getMail()).", ";
		$sql .= $this->datasource->quoteSmart($mailAddress->getDisplayAs()).", ";
		$sql .= $this->datasource->quoteSmart($role);
		$sql .= ")";
			
		$this->datasource->exec($sql);
	}
	
	/**
	 * Querys the mails database.
	 * Returns a table of rows, applying some filter if asked to.
	 * Each row is a table containing:
	 * {
	 * 	category=>
	 * 	mail_type=>
	 * 	title=>
	 * 	from=>
	 * 	tos=>list of email addresses, separated by a ,
	 * 	sent_date=>date in Y-m-d H:i:s format
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
	public function getMailsList($sortby='', $sortorder = "ASC", $offset = 0, $limit = 100, $fullTextSearch = null, $title = null, $from = null, $to = null, $category = null, $type = null) {
		
		$whereArr = array();
		if ($category) {
			$whereArr[] = " category LIKE ".$this->datasource->quoteSmart("%".$category."%");
		}
		if ($type) {
			$whereArr[] = " mail_type LIKE ".$this->datasource->quoteSmart("%".$type."%");
		}
		if ($title) {
			$whereArr[] = " title LIKE ".$this->datasource->quoteSmart("%".$title."%");
		}
		if ($from) {
			$whereArr[] = " mfrom.mail_address LIKE ".$this->datasource->quoteSmart("%".$from."%");
		}
		// NOTE: only the filtered mail addresse will appear... not perfect
		if ($to) {
			$whereArr[] = " mto.mail_address LIKE ".$this->datasource->quoteSmart("%".$to."%");
		}
		if ($fullTextSearch) {
			$whereArr[] = " category LIKE ".$this->datasource->quoteSmart("%".$fullTextSearch."%");
			$whereArr[] = " mail_type LIKE ".$this->datasource->quoteSmart("%".$fullTextSearch."%");
			$whereArr[] = " title LIKE ".$this->datasource->quoteSmart("%".$fullTextSearch."%");
			$whereArr[] = " mfrom.mail_address LIKE ".$this->datasource->quoteSmart("%".$fullTextSearch."%");
			$whereArr[] = " mto.mail_address LIKE ".$this->datasource->quoteSmart("%".$fullTextSearch."%");
			$whereArr[] = " sent_date LIKE ".$this->datasource->quoteSmart("%".$fullTextSearch."%");
		}
		
		$where = "";
		if ($whereArr) {
			$where = " WHERE ".implode(" OR ", $whereArr);
		}
		
		$sql = "SELECT om.id, `category`, `mail_type`, `title`, mfrom.mail_address AS frommail, GROUP_CONCAT(mto.mail_address) as tos, `sent_date` 
			FROM `outgoing_mails` om 
			LEFT JOIN `outgoing_mail_addresses` mfrom ON (om.id = mfrom.outgoing_mail_id AND mfrom.role='from')
			LEFT JOIN `outgoing_mail_addresses` mto ON (om.id = mto.outgoing_mail_id AND mto.role<>'from')
			$where
			GROUP BY mto.outgoing_mail_id
			ORDER BY $sortby $sortorder";
		// TODO: think about SQL injection in sortby and sortorder
		
		return $this->datasource->getAll($sql, PDO::FETCH_ASSOC, null, $offset, $limit);
	}
	
	/**
	 * Returns a DBMail object representing the mail.
	 * 
	 * @param int $mailId
	 * @throws DBMailException
	 * @return DBMail
	 */
	public function getMail($mailId) {
		$sql = "SELECT *
					FROM `outgoing_mails` om 
				WHERE id = ".$this->datasource->quoteSmart($mailId);
		
		return $this->getMailBySql($sql);
	}
	
	/**
	 * Returns a mail object based on its hash key.
	 * An hashkey can be attributed to any mail stored in DB. 
	 * 
	 * @param string $hashKey
	 */
	public function getMailByHashKey($hashKey) {
		$sql = "SELECT *
					FROM `outgoing_mails` om 
				WHERE unique_key = ".$this->datasource->quoteSmart($hashKey);
		
		return $this->getMailBySql($sql);
	}
	
	/**
	* Returns a DBMail object representing the mail from a SQL request that returns only one mail row.
	*
	* @param string $sql
	* @throws DBMailException
	* @return DBMail
	*/
	private function getMailBySql($sql) {
	
		$mailsArr = $this->datasource->getAll($sql);
		if (count($mailsArr) == 0) {
			throw new DBMailException("Unable to find mail in database.");
		}
	
		$mailArr = $mailsArr[0];
	
		$dbMail = new DBMail();
		$dbMail->setCategory($mailArr['category']);
		$dbMail->setType($mailArr['mail_type']);
		$dbMail->setTitle($mailArr['title']);
		$dbMail->setBodyText($mailArr['text_body']);
		$dbMail->setBodyHtml($mailArr['html_body']);
		$dbMail->setDateSent(strtotime($mailArr['sent_date']));
		$dbMail->setDbId($mailArr['id']);
		$dbMail->setHashKey($mailArr['unique_key']);
		
		$sql = "SELECT *
					FROM `outgoing_mail_addresses` oma 
				WHERE outgoing_mail_id = ".$this->datasource->quoteSmart($mailArr['id']);
	
		$mailAddresses = $this->datasource->getAll($sql);
		foreach ($mailAddresses as $mailAddressRow) {
			switch ($mailAddressRow['role']) {
				case 'from':
					$dbMail->setFrom(new MailAddress($mailAddressRow['mail_address'], $mailAddressRow['mail_address_name']));
					break;
				case 'to':
					$dbMail->addToRecipient(new MailAddress($mailAddressRow['mail_address'], $mailAddressRow['mail_address_name']));
					break;
				case 'cc':
					$dbMail->addCcRecipient(new MailAddress($mailAddressRow['mail_address'], $mailAddressRow['mail_address_name']));
					break;
				case 'bcc':
					$dbMail->addBccRecipient(new MailAddress($mailAddressRow['mail_address'], $mailAddressRow['mail_address_name']));
					break;
			}
		}
		return $dbMail;
	}
}
?>