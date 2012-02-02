<?php

/**
 * This interface must be implemented by mails to be sent using a class extending the MailerInterface interface.
 * 
 */
interface MailInterface {
	
	/**
	 * Returns the mail text body.
	 *
	 * @return string
	 */
	function getBodyText();
	
	/**
	 * Returns the mail html body.
	 *
	 * @return string
	 */
	function getBodyHtml();
	
	/**
	 * Returns the mail title.
	 *
	 * @return string
	 */
	function getTitle();
	
	/**
	 * Returns the "From" email address
	 *
	 * @return MailAddressInterface 
	 */
	function getFrom();
	
	/**
	 * Returns an array containing the recipients.
	 *
	 * @return array<MailAddressInterface>
	 */
	function getToRecipients();
	
	/**
	 * Returns an array containing the recipients in Cc.
	 *
	 * @return array<MailAddressInterface>
	 */
	function getCcRecipients();
	
	/**
	 * Returns an array containing the recipients in Bcc.
	 *
	 * @return array<MailAddressInterface>
	 */
	function getBccRecipients();
	
	/**
	 * Returns an array of attachements for that mail.
	 *
	 * @return array<MailAttachmentInterface>
	 */
	function getAttachements();
	
	/**
	 * Returns the encoding of the mail.
	 *
	 * @return string
	 */
	function getEncoding();
	// TODO
	// TODO
	// TODO
	// TODO
	// TODO
	// TODO
	// TODO
	// TODO
	// Plutôt qu'enrober, il faudrait utiliser la classe telle quelle!
	// Est-ce qu'on peut décrire des composants par déclaration dans components.xml?
	// Ca serait de la balle!!!!!!
	// Pourquoi pas une Factory d'ailleurs?
	// @Factory(Zend_Mail_Transport_Smtp)
	// puis des @Properties
	// puis 
	// function buildInstance()
	//
	// Note: attention: les boucles sont interdites avec les factory!!!!
	// Ou alors, on fait un truc du genre:
	// function getEmptyInstance()
	// function canParameter($parameter) // retourne true si on peut setter une property après l'instanciation vide
	// function getFullInstance()
}
?>