<?php

/**
 * This class sends mails using the Zend Framework SMTP mailer.
 * 
 * @Component
 */
class NullMailService implements MailServiceInterface {
	
	/**
	 * The logger to use.
	 *
	 * @Property
	 * @Compulsory
	 * @var LogInterface
	 */
	public $log;
	
	/**
	 * Sends the mail passed in parameter.
	 *
	 * @param MailInterface $mail The mail to send.
	 */
	public function send(MailInterface $mail) {
		// Let's log the mail:
		$recipients = array_merge($mail->getToRecipients(), $mail->getCcRecipients(), $mail->getBccRecipients());
		$recipientMails = array();
		foreach ($recipients as $recipient) {
			$recipientMails[] = $recipient->getMail();
		}
		$this->log->debug("Null mailer in place. Not sending mail to ".implode(", ", $recipientMails).". Mail subject: ".$mail->getTitle());
	}
}
?>