<?php
require_once dirname(__FILE__).'/html2txt.php';


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
	 * The domain name.
	 *
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $domainName;
	
	
	/**
	 * Sends the mail passed in parameter.
	 *
	 * @param MailInterface $mail The mail to send.
	 */
	public function send(MailInterface $mail) {
		foreach ($mail->getToRecipients() as $recipient) {
			$recipientMails[] = $recipient->getMail();
		}
		$to = implode(", ", $recipientMails);
		
		foreach ($mail->getCcRecipients() as $recipient) {
			$recipientMailsCC[] = $recipient->getMail();
		}
		if(!empty($recipientMailsCC))
			$cc = implode(", ", $recipientMailsCC);
		
		foreach ($mail->getBccRecipients() as $recipient) {
			$recipientMailsBCC[] = $recipient->getMail();
		}
		if(!empty($recipientMailsBCC))
			$bcc = implode(", ", $recipientMailsBCC);
		
		$text = $mail->getBodyText();
		$html = $mail->getBodyHtml();
		
		if (empty($text)){
			$text = convert_html_to_text(html_entity_decode($html, ENT_COMPAT, "UTF-8"));
		}if (empty($html)){
			$html = htmlentities($text, ENT_COMPAT, "UTF-8");
		}

		if ($mail->getFrom() != null){
			$fromString = $mail->getFrom()->getDisplayAs();
			$fromAdress = $mail->getFrom()->getMail();
		}else{
			$fromString = $this->fromString;
			$fromAdress = $this->fromAddress;
		}
		$from = $fromString." <".$fromAdress.">";
		
		$limite = "_----------=_parties_".md5(uniqid (rand()));
		
		$header  = "Reply-to: ".$from."\n";
		$header .= "From: ".$from."\n";
		$header .= "X-Sender: <".$this->domainName.">\n";
		$header .= "X-Mailer: PHP\n";
		$header .= "X-auth-smtp-user: ".$from." \n";
		$header .= "X-abuse-contact: ".$from." \n";
		$header .= "Date: ".date("D, j M Y G:i:s O")."\n";
		$header .= "MIME-Version: 1.0\n";
		$header .= "Content-Type: multipart/alternative; boundary=\"".$limite."\"";
		$addheader = null;
		if(!empty($cc))
			$addheader = "Cc: $cc" . "\r\n";
		if(!empty($bcc))
			$addheader .= "Bcc: $bcc" . "\r\n";
		
		$message = "";
		
		$message .= "--".$limite."\n";
		$message .= "Content-Type: text/plain\n";
		$message .= "charset=\"utf-8\"\n";
		$message .= "Content-Transfer-Encoding: 8bit\n\n";
		$message .= $text;
		
		$message .= "\n\n--".$limite."\n";
		$message .= "Content-Type: text/html; ";
		$message .= "charset=\"iso-8859-1\"; ";
		$message .= "Content-Transfer-Encoding: 8bit;\n\n";
		$message .= $html;
		
		$message .= "\n--".$limite."--";
		mail($to, $mail->getTitle(), $message, $header, $addheader);
	}
	
}
?>