<?php

/**
 * Class MailUtils is used for sending an email. 
 * 
 * @Component
 *
 */
class MailUtils{

	
	/**
	 *
	 * @return true on success, false on failure.
	 */
	public static function buildAndSendMail($to,$from, $subject, $fromAlias=null, $toAlias=null, $html, $text){
		require_once 'class.mail5.php';
		
		$mail = new mailMain();
		$mail -> model -> addTO ($to, $toAlias);
		$mail -> model -> addFROM ($from, $fromAlias);
		$mail -> model -> addSubject($subject);
		$mail->model->addHtml($html);
		$mail->model->addPlainText($text);

		if (SEND_MAIL==true){
			try {
			$bool = $mail -> sender -> send();
			}
			catch (Exception $e)  {
				//echo $e->getMessage();
				return false;
			}
			return $bool;
		}
		else{
			$file = fopen(sys_get_temp_dir().'/mails_sent.html', 'a+');
			fwrite($file, "<h2>------------------------- Mail Sent : $subject : $to -------------------------</h2>");
			fwrite($file, $html);
			fclose($file);
			return true;
		}
	}

}


?>