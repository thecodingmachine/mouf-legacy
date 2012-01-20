<?php

/**
 * This class represents an email address.
 * It contains:
 *  - an email address
 *  - the name that should be displayed
 *
 * @Component
 */
class MailAddress implements MailAddressInterface {
	
	private $mail;
	private $displayAs;
	
	public function getMail() {
		return $this->mail;
	}
	
	public function getDisplayAs() {
		return $this->displayAs;
	}
	
	/**
	 * Sets the mail address.
	 *
	 * @Property
	 * @Compulsory 
	 * @param string $mail
	 */
	public function setMail($mail) {
		$this->mail = $mail;
	}

	/**
	 * Sets the name of the mail owner.
	 *
	 * @Property
	 * @param string $displayAs
	 */
	public function setDisplayAs($displayAs) {
		$this->displayAs = $displayAs;
	}
	
	public function __construct($mail = null, $displayAs = null) {
		$this->setMail($mail);
		$this->setDisplayAs($displayAs);
	}
	
	/**
	 * Renders the mail address as Name <mail@example.com> if there is a diplay name, or simply as mail@example.com if there is no display name.
	 * @return string
	 */
	public function __toString() {
		if ($this->displayAs) {
			return $this->displayAs.' <'.$this->mail.'>';
		} else {
			return $this->mail;
		}
	}
}
?>