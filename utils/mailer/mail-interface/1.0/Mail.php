<?php

/**
 * This class represents a mail to be sent using a Mailer class extending the MailerInterface.
 * 
 * @Component
 */
class Mail implements MailInterface {
	
	private $bodyText;
	private $bodyHtml;
	private $title;
	private $from;
	private $toRecipients = array();
	private $ccRecipients = array();
	private $bccRecipients = array();
	private $attachements = array();
	
	
	/**
	 * Returns the mail text body.
	 *
	 * @return string
	 */
	function getBodyText() {
		return $this->bodyText;
	}
	
	/**
	 * The mail text body.
	 *
	 * @Property
	 * @param string $bodyText
	 */
	function setBodyText($bodyText) {
		$this->bodyText = $bodyText;
	}
	
	/**
	 * Returns the mail html body.
	 *
	 * @return string
	 */
	function getBodyHtml() {
		return $this->bodyHtml;
	}

	/**
	 * The mail html body.
	 *
	 * @Property
	 * @param string $bodyHtml
	 */
	function setBodyHtml($bodyHtml) {
		$this->bodyHtml = $bodyHtml;
	}
	
	
	/**
	 * Returns the mail title.
	 *
	 * @return string
	 */
	function getTitle() {
		return $this->title;
	}
	
	/**
	 * The mail title.
	 *
	 * @Property
	 * @param string $title
	 */
	function setTitle($title) {
		$this->title = $title;
	}
	
	/**
	 * Returns the "From" email address
	 *
	 * @return MailInterface The first element is the email address, the second the name to display.
	 */
	function getFrom() {
		return $this->from;
	}

	/**
	 * The mail from address.
	 *
	 * @Property
	 * @param MailAddressInterface $from
	 */
	function setFrom(MailAddressInterface $from) {
		$this->from = $from;
	}
	
	/**
	 * Returns an array containing the recipients.
	 *
	 * @return array<MailAddressInterface>
	 */
	function getToRecipients() {
		return $this->toRecipients;
	}

	/**
	 * An array containing the recipients.
	 *
	 * @Property
	 * @param array<MailAddressInterface> toRecipients
	 */
	function setToRecipients($toRecipients) {
		$this->toRecipients = $toRecipients;
	}
	
	/**
	 * Adss a recipient.
	 *
	 * @param MailAddressInterface $toRecipient
	 */
	function addToRecipient(MailAddressInterface $toRecipient) {
		$this->toRecipients[] = $toRecipient;
	}
	
	/**
	 * Returns an array containing the recipients in Cc.
	 *
	 * @return array<MailAddressInterface>
	 */
	function getCcRecipients() {
		return $this->ccRecipients;
	}
	
	/**
	 * An array containing the recipients.
	 *
	 * @Property
	 * @param array<MailAddressInterface> ccRecipients
	 */
	function setCcRecipients($ccRecipients) {
		$this->ccRecipients = $ccRecipients;
	}
	
	/**
	 * Adds a recipient.
	 *
	 * @param MailAddressInterface $ccRecipient
	 */
	function addCcRecipient(MailAddressInterface $ccRecipient) {
		$this->ccRecipients[] = $ccRecipient;
	}
	
	/**
	 * Returns an array containing the recipients in Bcc.
	 *
	 * @return array<MailAddressInterface>
	 */
	function getBccRecipients() {
		return $this->bccRecipients;
	}
	
	/**
	 * An array containing the recipients.
	 *
	 * @Property
	 * @param array<MailAddressInterface> bccRecipients
	 */
	function setBccRecipients($bccRecipients) {
		$this->bccRecipients = $bccRecipients;
	}
	
	/**
	 * Adds a recipient.
	 *
	 * @param MailAddressInterface $ccRecipient
	 */
	function addBccRecipient(MailAddressInterface $bccRecipient) {
		$this->bccRecipients[] = $bccRecipient;
	}
	
	/**
	 * Returns an array of attachements for that mail.
	 *
	 * @return array<MailAttachmentInterface>
	 */
	function getAttachements() {
		return $this->attachements;
	}
	
	/**
	 * An array containing the attachments.
	 *
	 * @Property
	 * @param array<MailAttachmentInterface> attachments
	 */
	function setAttachements($attachments) {
		$this->attachments = $attachments;
	}
	
	/**
	 * Adds an attachment.
	 *
	 * @param MailAttachmentInterface $ccRecipient
	 */
	function addAttachement(MailAttachmentInterface $attachment) {
		$this->attachments[] = $attachment;
	}
}
?>