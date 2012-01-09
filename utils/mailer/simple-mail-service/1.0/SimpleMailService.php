<?php

/**
 * This class sends mails using the php Mail function. 
 * There's no authentication to the mail server since the purpose of this service is to be simple.<br/>
 * This mail service works very fine with the simple php sendmail module (apt-get install sendmail)
 * 
 * @Component
 */
class SimpleMailService implements MailServiceInterface {
	
	/**
	 * The logger to use.
	 *
	 * @Property
	 * @Compulsory
	 * @var LogInterface
	 */
	public $log;
	
	/**
	 * The From string.
	 *
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $fromString;
	
	/**
	 * The From email address.
	 *
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $fromAddress;
	
	
	/**
	 * Sends the mail passed in parameter.
	 *
	 * @param MailInterface $mail The mail to send.
	 */
	public function send(MailInterface $mail) {
		$boundary = "nextPart";

		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "From: $fromString <$fromAddress>\r\n";
		$headers .= "Content-Type: multipart/alternative; boundary = $boundary\r\n";
		
		//text version
		$headers .= "\n--$boundary\n"; // beginning \n added to separate previous content
		$headers .= "Content-type: text/plain; charset=utf-8\r\n";
		$headers .= "This is the plain version";
		
		//html version
		$headers .= "\n--$boundary\n";
		$headers .= "Content-type: text/html; charset=utf-8\r\n";
		$headers .= "This is the <b>HTML</b> version";

		return mail("nguyenket@gmail.com", $mail->getTitle(), "", $headers);
	}
	
}
?>