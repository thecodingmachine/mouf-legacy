<?php 

/**
 * A BlackListMail is a class that wraps an existing DBMail, and adds an unsubscribe mail at the end of it.
 * Do not create this object yourself. It is internally used by the BlackListMailService.
 * 
 * @author David Négrier
 */
class BlackListMail implements DBMailInterface {
	/**
	 * @var MailInterface
	 */
	private $mail;
	private $htmlUnsubscribeLink;
	private $textUnsubscribeLink;
	private $needle;
	
	public function __construct(MailInterface $mail, $htmlUnsubscribeLink, $textUnsubscribeLink, $needle) {
		$this->mail = $mail;
		$this->htmlUnsubscribeLink = $htmlUnsubscribeLink;
		$this->textUnsubscribeLink = $textUnsubscribeLink;
		$this->needle = $needle;
	}
	
	/**
	 * Returns the category of the mail.
	 * Use the category to sort mails stored in database.
	 *
	 * @return string
	 */
	function getCategory() {
		if ($this->mail instanceof DBMailInterface) {
			return $this->mail->getCategory();
		} else {
			return null;
		}
	}
	
	/**
	 * Returns the type of the mail.
	 * Use the type to sort mails stored in database.
	 *
	 * @return string
	 */
	function getType() {
		if ($this->mail instanceof DBMailInterface) {
			return $this->mail->getType();
		} else {
			return null;
		}
	}
	
	/**
	 * Returns the date the mail was sent, as a PHP timestamp.
	 * Returns null if the mail was not yet sent.
	 *
	 * @return string
	 */
	function getDateSent() {
		if ($this->mail instanceof DBMailInterface) {
			return $this->mail->getDateSent();
		} else {
			return null;
		}
	}
	
	/**
	 * Returns the unique key identifying the mail.
	 * This key should be random enough to not be guessable.
	 * Returns null if there is no such key.
	 *
	 * @return string
	 */
	function getHashKey() {
		if ($this->mail instanceof DBMailInterface) {
			return $this->mail->getHashKey();
		} else {
			return null;
		}
	}
	
	/**
	 * This function is called by the DBMailService.
	 *
	 * @param int $dbId
	 */
	function setDbId($dbId) {
		if ($this->mail instanceof DBMailInterface) {
			$this->mail->setDbId($dbId);
		}
	}
	
	/**
	* Returns the mail text body.
	*
	* @return string
	*/
	function getBodyText() {
		$replaced = false;
		$text = $this->getBodyText();
		if ($this->needle) {
			
			if (strpos($text, $this->needle) !== false) {
				$text = str_replace($this->needle, $this->textUnsubscribeLink, $text);
				$replaced = true;
			}
		}
		// If no needle, let's add the link at the end of the mail.
		if (!$replaced) {
			$text .= "\n".$this->textUnsubscribeLink;
		}
		return $text;
	}
	
	/**
	 * Returns the mail html body.
	 *
	 * @return string
	 */
	function getBodyHtml() {
		$replaced = false;
		$html = $this->getBodyHtml();
		if ($html == null) {
			return null;
		}
		if ($this->needle) {
			if (strpos($html, $this->needle) !== false) {
				$html = str_replace($this->needle, $this->htmlUnsubscribeLink, $html);
				$replaced = true;
			}
		}
		// If no needle, let's add the link at the end of the mail.
		if (!$replaced) {
			$html .= "\n".$this->htmlUnsubscribeLink;
		}
		return $html;
	}
	
	/**
	 * Returns the mail title.
	 *
	 * @return string
	 */
	function getTitle() {
		return $this->mail->getTitle();
	}
	
	/**
	 * Returns the "From" email address
	 *
	 * @return MailAddressInterface
	 */
	function getFrom() {
		return $this->mail->getFrom();
	}
	
	/**
	 * Returns an array containing the recipients.
	 *
	 * @return array<MailAddressInterface>
	 */
	function getToRecipients() {
		return $this->mail->getToRecipients();
	}
	
	/**
	 * Returns an array containing the recipients in Cc.
	 *
	 * @return array<MailAddressInterface>
	 */
	function getCcRecipients() {
		return $this->mail->getCcRecipients();
	}
	
	/**
	 * Returns an array containing the recipients in Bcc.
	 *
	 * @return array<MailAddressInterface>
	 */
	function getBccRecipients() {
		return $this->mail->getBccRecipients();
	}
	
	/**
	 * Returns an array of attachements for that mail.
	 *
	 * @return array<MailAttachmentInterface>
	 */
	function getAttachements() {
		return $this->mail->getAttachements();
	}
}