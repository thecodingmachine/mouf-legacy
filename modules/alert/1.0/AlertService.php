<?php
require_once 'AlertException.php';

/**
 * The service used to send alerts.
 *
 * @Component
 */
class AlertService {
	
	/**
	 * The Dao for accessing the alerts in database.
	 *
	 * @Property
	 * @Compulsory
	 * @var AlertDaoInterface
	 */
	public $alertDao;
	
	/**
	 * The Dao for accessing the alert_recipients table in database.
	 *
	 * @Property
	 * @Compulsory
	 * @var AlertRecipientDao
	 */
	public $alertRecipientsDao;
	
	/**
	 * The service used to send mails.
	 *
	 * @Property
	 * @Compulsory
	 * @var MailServiceInterface
	 */
	public $mailService;
	
	/**
	 * The mail address the alerts will originate from.
	 *
	 * @Property
	 * @var MailAddressInterface
	 */
	public $mailFrom;
	
	/**
	 * Sends the alert (and stores the alert in database).
	 *
	 * @param AlertInterface $alert
	 */
	public function send(AlertInterface $alert) {
		
		// Creates the alert in database.
		$alertBean = $this->alertDao->getNewAlert();
		$alertBean->setTitle($alert->getTitle());
		$alertBean->setMessage($alert->getMessage());
		$alertBean->setUrl($alert->getUrl());
		$alertBean->setCategory($alert->getCategory());
		$alertBean->setDateAsTimeStamp($alert->getDate());
		$alertBean->setLevel($alert->getLevel());
		$alertBean->setValidated($alert->getValidated());
		$alertBean->setValidationDateAsTimeStamp($alert->getValidationDate());
		$alertBean->save();
		
		foreach ($alert->getRecipients() as $recipient) {
			$alertRecipient = $this->alertRecipientsDao->getNewAlertRecipients();
			$alertRecipient->setAlertId($alertBean->getId());
			$alertRecipient->setUserId($recipient->getId());
			$alertRecipient->save();
		}
		
		// Send the alert via mail.
		foreach ($alert->getRecipients() as $recipient) {
			$mail = new Mail();
			if ($this->mailFrom != null) {
				$mail->setFrom($this->mailFrom);
			}
			$mail->setTitle($alert->getTitle());
			$mail->setBodyHtml($alert->getMessage());
			$mail->addToRecipient(new MailAddress($recipient->getEmail(), $recipient->getFullName()));
			$this->mailService->send($mail);
		}
	}

}

?>