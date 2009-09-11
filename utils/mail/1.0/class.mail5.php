<?php
class mailFieldFilter {
	public $log = array();

	function __construct() {
	}

	function checkAddress($address) {
		if ( preg_match('`([[:alnum:]]([-_.]?[[:alnum:]])*@[[:alnum:]]([-_.]?[[:alnum:]])*\.([a-z]{2,4}))`', $address) ) {
			return TRUE;
		} else {
			$this->log['Error'][] = "invalid address: $address;"; return FALSE;
		}
	}

	function checkName($name) {
		if ( preg_match("`[0-9a-zA-Z\.\-_ ]*`" , $name ) ) {
			return TRUE;
		} else {
			$this->log['Error'][] = "invalid name: $name;"; return FALSE;
		}
	}

}

class QP {

	static function encode($input, $line_max = 76) {

		$hex = array('0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F');
		$lines = preg_split("/(?:\r\n|\r|\n)/", $input);
		$eol = "\r\n";
		$escape = "=";
		$output = "";

		while( list(, $line) = each($lines) ) {
			$line = rtrim($line);
			$linlen = strlen($line);
			$newline = "";
			for($i = 0; $i < $linlen; $i++) {
				$c = substr($line, $i, 1);
				$dec = ord($c);
				if ( ($dec == 32) && ($i == ($linlen - 1)) ) {
					$c = "=20";
				} elseif ( ($dec == 61) || ($dec < 32 ) || ($dec > 126) ) {
					$h2 = floor($dec/16); $h1 = floor($dec%16);
					$c = $escape.$hex["$h2"].$hex["$h1"];
				}
				if ( (strlen($newline) + strlen($c)) >= $line_max ) {
					$output .= $newline.$escape.$eol;
					$newline = "";
				}
				$newline .= $c;
			}
			$output .= $newline.$eol;
		}
		return trim($output);
	}

	static function headerEncode($in) {

		$in = QP::encode($in);
		$in = str_replace("=\r\n", "", $in);
		$in = str_replace("?", "=3F", $in);
		$in = chunk_split($in, 55);
		if (substr($in, -2) == "\r\n") $in = substr($in, 0, -2);
		$in = str_replace("\r\n", "?=\r\n =?ISO-8859-1?Q?", $in);
		$in = "=?ISO-8859-1?Q?" . $in . "?=" ;
		return $in;
	}

	static function needQP($in) {
		$in = (string) $in;
		if ( empty($in) ) return FALSE;
		$return = FALSE;
		for($i = 0;$i<strlen($in) ;$i++ ) {
			$ord = ord($in{$i});
			if ($ord<32 || $ord>126) {
				$return = TRUE;
			}
		}
		return $return;
	}
}

class mailHeaderField {
	public $name = NULL;
	public $value = NULL;
	public $type = NULL;
	public $list = array();

	function __construct($name, $value = NULL, $type = 'S') {
		$this->name = $name;
		$this->value = $value;
		$this->type = $type;
	}

	function addRecipient($namePlusAddress) {
		$this->type = 'RL';
		$this->list[] = $namePlusAddress ;
	}

	function addValueString($value) {
		$this->type = 'S';
		$this->value = $value;
	}

	function value2string() {
		return (string) $this->value;
	}

	function recipientList2string() {
		return implode ( ', ',$this->list );
	}

	function rawValue() {
		if ($this->type == 'S') return $this->value2string();
		if ($this->type == 'RL') return $this->recipientList2string();
		return FALSE;
	}

	function rawValueIter() {
		if ( ! list(, $val) = each($this->list) ) return FALSE;
		if (is_string($val)) return $val;
		return FALSE;
	}

	function toString() {
		if ($this->type == 'S') $buffer = $this->value2string();
		if ($this->type == 'RL') $buffer = $this->recipientList2string();
		if ( QP::needQP($buffer) ) {
			$buffer = QP::headerEncode($buffer);
			return sprintf('%s: %s',$this->name,$buffer);
		} else {
			$buffer = chunk_split( sprintf('%s: %s',$this->name, $buffer), 74, "\r\n\t");
			if (substr($buffer, -3) == "\r\n\t") $buffer = substr($buffer, 0, -3);
			return $buffer;
		}
	}
}

class mailModel {
	public $header = array();
	public $body = array();
	public $log = array();
	public $param = array();
	private $fieldFilter;

	function __construct() {
		$this->fieldFilter = new mailFieldFilter;
		$this->param['TO1by1'] = false;
		$this->param['addHeaderNoCRLF'] = false;

		$this->header['XMailer'] = new mailHeaderField ('X-mailer','PHP');
		$this->header['XPriority'] = new mailHeaderField ('X-priority','3');
	}

	private function makeNamePlusAddress($address ,$name = NULL) {
		if ( empty($name) ) { return $address; }
		if ( !$this->fieldFilter->checkAddress($address) ) return FALSE;
		if ( !$this->fieldFilter->checkName($name) ) return FALSE;
		return sprintf('"%s" <'.'%s>',$name,$address);
	}

	public function setParam($param , $value ) {
		$this->param[$param] = $value;
		return TRUE;
	}

	function addHeaderField($label, $field ,$value, $type = 'S') {
		$this->header['addField'][$label] = new mailHeaderField ( $field, $value, $type );
	}

	private function addRecipient ($label, $header, $mail ,$name='') {
		$tmp = $this->makenameplusaddress($mail, $name);
		if ( !$tmp ) {
			$this->log['Error'][] = 'Add recipient '.$header.' error: '.$tmp;
			return FALSE;
		}
		if (!isset($this->header[$label])) $this->header[$label] = new mailHeaderField ($header);
		$this->header[$label]->addRecipient( $tmp );
		return TRUE;
	}

	public function addTO($mail, $name ='' ) {
		$check = $this->addRecipient('TO','To', $mail, $name );
		if ( count($this->header['TO']->list)>1 ) $this->param['TO1by1'] = true;
		return $check;
	}

	public function addCC($mail, $name ='' ) {
		return $this->addRecipient('CC', 'Cc', $mail, $name );
	}

	public function addBCC($mail, $name ='' ) {
		return $this->addRecipient('BCC', 'BCC', $mail, $name );
	}

	public function addReturnPath($mail, $name ='' ) {
		return $this->addRecipient('ReturnP', 'Return-Path', $mail, $name );
	}

	public function addReplyTo($mail, $name ='' ) {
		return $this->addRecipient('ReplyT', 'Reply-To', $mail, $name );
	}

	public function addFROM($mail, $name ='' ) {
		return $this->addRecipient('FROM', 'From', $mail, $name );
	}

	public function addSubject($subject ) {
		return $this->header['Subject'] = new mailHeaderField ( 'Subject', $subject )  ;
	}

	public function addPlainText($text ) {
		$this->body['PlainText'] = $text;
		return TRUE;
	}

	public function addHTML($text ) {
		$this->body['HTML'] = $text;
		return TRUE;
	}

	public function addFile($filename, $contenttype = NULL) {
		if (!file_exists($filename)) {
			$this->log['Error'][] = 'No file : '.$filename;
			return FALSE;
		}
		$this->body['attachement'][] = array ( 'filename' => $filename, 'contenttype' => $contenttype ) ;
		return TRUE;
	}

	public function addHTMLfile($filename ,$cid = '' , $contenttype = '') {
		if (!file_exists($filename)) {
			$this->log['Error'][] = 'No file : '.$filename;
			return FALSE;
		}
		$this->body['htmlattachement'][] = array (  'filename'=>$filename ,
													'cid'=>$cid ,
													'contenttype'=>$contenttype );
		return TRUE;
	}

}

class buildMail {

	public $charset = 'iso-8859-1';
	public $B1B = "----=_001";
	public $B2B = "----=_002";
	public $B3B = "----=_003";
	public $mailModel;
	public $log = array();
	public $headers = '';
	public $body = '';
	public $to = '';
	public $TZ = 'Europe/Paris';

	function __construct($mailModel) {
		$this->mailModel = $mailModel;
		date_default_timezone_set($this->TZ);
	}

	function AddField2Header($Field, $Value = NULL) {
		if ($Field instanceof mailHeaderField && $this->mailModel->param['TO1by1'] && $Field->name == 'To') {
			if ( ! list(, $val) = each($Field->list) ) return FALSE;
			if (is_string($val)) $this->headers .= chunk_split(sprintf('%s :%s',$Field->name, $val), 74,"\r\n\t ")."\r\n";
			return TRUE;
		}

		if ($Field instanceof mailHeaderField) $this->headers .= $Field->toString()."\r\n";
		if (is_string($Field)) $this->headers .= wordwrap(sprintf('%s: %s',$Field, $Value), 74,"\r\n\t ")."\r\n";
		return TRUE;
	}



	# Main part #
	function makeHeader($param = array()) {
		

		$this->headers = '';

		if ( !isset($this->mailModel->header['TO'])) {
			$this->log['Error'][]= "No TO";
			return FALSE;
		}

		if ( !isset($param['noTO']))
		$this->AddField2Header($this->mailModel->header['TO']);
		
		if (isset($param['TO']))
			$this->AddField2Header("To", $param['TO']);

		if ( !isset($this->mailModel->header['Subject']) ) {
			$this->log['Error'][] = 'No subject';
			return FALSE;
		}

		if ( !isset($param['noSubject'])) $this->AddField2Header($this->mailModel->header['Subject']);

		$this->AddField2Header("Date", date ('r'));

		if ( !isset($this->mailModel->header['Xsender']) ) {
			$this->mailModel->header['Xsender'] = clone $this->mailModel->header['FROM'];
			$this->mailModel->header['Xsender']->name = 'X-sender';
		}
		$this->AddField2Header($this->mailModel->header['Xsender']);

		if ( !isset($this->mailModel->header['ErrorsTo']) ) {
			$this->mailModel->header['ErrorsTo'] = new mailHeaderField ('Errors-To', $this->mailModel->header['FROM']->rawValue() );
		}
		$this->AddField2Header($this->mailModel->header['ErrorsTo']);

		if ( isset($this->mailModel->header['XMailer']) ) $this->AddField2Header($this->mailModel->header['XMailer']);

		if ( isset($this->mailModel->header['XPriority']) ) $this->AddField2Header($this->mailModel->header['XPriority']);

		if ( isset($this->mailModel->header['FROM']) ) $this->AddField2Header($this->mailModel->header['FROM']);

		if ( isset($this->mailModel->header['ReturnP']) ) $this->AddField2Header($this->mailModel->header['ReturnP']);

		if ( isset($this->mailModel->header['ReplyT']) ) $this->AddField2Header($this->mailModel->header['ReplyT']);
		
		if ( is_array($this->mailModel->header['addField']) ) {
			foreach($this->mailModel->header['addField'] as $val ) {
				$this->AddField2Header($val);
			}
		}

		$this->headers .= "MIME-Version: 1.0\r\n";

		if ( !$this->mailModel->body['HTML'] && $this->mailModel->body['PlainText'] && !empty($this->mailModel->body['attachement']) ) {
			$this->headers .= sprintf("Content-Type: multipart/mixed;\r\n\t boundary=\"%s\"\r\n", $this->B1B);
		} elseif ( !$this->mailModel->body['HTML'] && $this->mailModel->body['PlainText'] && empty($this->mailModel->body['attachement']) ) {
			$this->headers .= "Content-Type: text/plain; charset=iso-8859-1; format=flowed\r\n";
			$this->headers .= "Content-Transfer-Encoding: quoted-printable\r\n";
		} elseif ( $this->mailModel->body['HTML'] ) {
			if ( !$this->mailModel->body['PlainText'] ) { $this->mailModel->body['PlainText'] = "HTML only!"; }
			$this->headers .= sprintf("Content-Type: multipart/mixed;\r\n\t boundary=\"%s\"\r\n",$this->B1B);
		}

		if ( isset($this->mailModel->header['CC']) && !isset($param['noCC']) ) $this->AddField2Header($this->mailModel->header['CC']);
		
		if ( isset($this->mailModel->header['BCC']) && !isset($param['noBCC']) ) $this->AddField2Header($this->mailModel->header['BCC']);

		return $this->headers;

	}

	function makeBody() {
		$message='';
		if ( !$this->mailModel->body['HTML'] && $this->mailModel->body['PlainText'] && !empty($this->mailModel->body['attachement']) ) {
			$message ="This is a multi-part message in MIME format.";
			$message .= sprintf("\r\n--%s\r\n",$this->B1B);

			$message .= "Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
			$message .= "Content-Transfer-Encoding: quoted-printable\r\n\r\n";
			// plaintext goes here
			$message .= QP::encode($this->mailModel->body['PlainText'])."\r\n\r\n";
			$message .= $this->writeAttachement($this->mailModel->body['attachement'],$this->B1B);
		}
		elseif ( !$this->mailModel->body['HTML'] && $this->mailModel->body['PlainText'] && empty($this->mailModel->body['attachement']) ) {
			// plaintext goes here
			$message.= QP::encode($this->mailModel->body['PlainText'])."\r\n\r\n";
		}
		elseif ( $this->mailModel->body['HTML'] ) {
			//Messages start with text/html alternatives in OB
			$message = "This is a multi-part message in MIME format.\r\n";
			$message .= sprintf("\r\n--%s\r\n",$this->B1B);
			$message .= sprintf("Content-Type: multipart/alternative;\r\n\t boundary=\"%s\"\r\n\r\n",$this->B2B);

			//plaintext section
			$message .= sprintf("\r\n--%s\r\n",$this->B2B);
			$message .= "Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
			$message .= "Content-Transfer-Encoding: quoted-printable\r\n\r\n";
			// plaintext goes here
			$message .= QP::encode($this->mailModel->body['PlainText'])."\r\n\r\n";

			$message .= sprintf("\r\n--%s\r\n",$this->B2B);
			$message .= sprintf("Content-Type: multipart/related;\r\n\t boundary=\"%s\"\r\n\r\n",$this->B3B);

			// html section
			$message .= sprintf("\n--%s\n",$this->B3B);
			$message .= "Content-Type: text/html; charset=\"iso-8859-1\"\r\n";
			$message .= "Content-Transfer-Encoding: quoted-printable\r\n\r\n";
			// html goes here
			$message .= QP::encode($this->mailModel->body['HTML'])."\r\n\r\n";

			// attachments html
			if (empty($this->mailModel->body['htmlattachement'])) {
				$message .= sprintf("\r\n--%s--\r\n",$this->B3B);
			} else {
				$message.=$this->writeAttachement( $this->mailModel->body['htmlattachement'],$this->B3B);
			}

			// end of html
			$message .= sprintf ("\r\n--%s--\r\n",$this->B2B);

			// attachments
			if (empty($this->mailModel->body['attachement'])) {
				$message .= sprintf("\r\n--%s--\r\n",$this->B1B);
			} else {
				$message.=$this->writeAttachement($this->mailModel->body['attachement'],$this->B1B);
			}

		}
		$this->body = $message;
		return $message;
	}

	function writeAttachement($attachement,$B) {
		$message = '';
		if ( !empty($attachement) ) {
			foreach($attachement as $AttmFile){
				$patharray = explode ("/", $AttmFile['filename']);
				$FileName = $patharray[count($patharray)-1];
				$message .= "\r\n--".$B."\r\n";
				if (!empty($AttmFile['cid'])) {
					$message .= "Content-Type: {$AttmFile['contenttype']};\r\n name=\"".$FileName."\"\r\n";
					$message .= "Content-Transfer-Encoding: base64\r\n";
					$message .= "Content-ID: <{$AttmFile['cid']}>\r\n";
					$message .= "Content-Disposition: inline;\n filename=\"".$FileName."\"\r\n\r\n";
				} else {
					if ($AttmFile['contenttype']) {
						$ctype = $AttmFile['contenttype'] ;
					} else {
						$ctype = $AttmFile['contenttype']= 'application/octetstream';
					}
					$message .= "Content-Type: ".$ctype.";\r\n name=\"".$FileName."\"\r\n";
					$message .= "Content-Transfer-Encoding: base64\r\n";
					$message .= "Content-Disposition: attachment;\r\n filename=\"".$FileName."\"\r\n\r\n";
				}

				$fd=fopen ($AttmFile['filename'], "rb");
				$FileContent=fread($fd,filesize($AttmFile['filename']));
				fclose ($fd);

				$FileContent = chunk_split(base64_encode($FileContent));
				$message .= $FileContent;
				//$message .= "\r\n";
			}
			$message .= "--".$B."--\r\n";
		}
		return $message;
	}
}

class mailSender {
	public $set_mode = 'socket';
	public $log = array();

	function __construct($buildMail) {
		$this->buildMail = $buildMail;
		$this->socketErrorCode = '`251|421|450|451|452|500|501|503|521|550|551|552|553|554`';
	}

	function send() {
		$bool=true;
		switch($this->set_mode)	{
			case 'php' : $this->phpMail(); break;
			case 'socket': $bool = $this->socketMailLoop(); break;
		}
		return $bool;
	}

	# Mail send by PHPmail #
	function phpMail() {

		$body = $this->CleanMailDataString($this->buildMail->makeBody());
		$subject = $this->buildMail->mailModel->header['Subject']->rawValue();
		if ( $this->buildMail->mailModel->param['addHeaderNoCRLF'] ):
			$header = $this->DirtMailDataString($this->buildMail->makeHeader(array ( 'noTO' =>  TRUE, 'noSubject' => TRUE )));
		else:
			$header = $this->CleanMailDataString($this->buildMail->makeHeader(array ( 'noTO' =>  TRUE, 'noSubject' => TRUE )));
		endif;

		if ($this->buildMail->mailModel->param['TO1by1']) {
			while ( $to = $this->buildMail->mailModel->header['TO']->rawValueIter() ) {
				
				$mailSentReport = mail( $to,
										$subject,
										$body,
										$header
									);
				
				if ($mailSentReport){
					$this->log['Success'][] = "Success sending to $to";
				} else {
					$this->log['Error'][] = "Error sending to $to";
				}
			}
		} else {
			$to = $this->buildMail->mailModel->header['TO']->rawValue();
			
			$mailSentReport = mail( $to,
									$subject,
									$body,
									$header
								);
			
			if ($mailSentReport){
				$this->log['Success'][] = "Success sending to $to";
			} else {
				$this->log['Error'][] = "Error sending to $to";
			}

		}
		return TRUE;
	}

	# Socket Function #
	function SocketStart() {
		try{
			$this->connect = fsockopen (ini_get("SMTP"), ini_get("smtp_port"), $errno, $errstr, 15);
			$test = fgets($this->connect, 1024);
			return $test;
		}catch (Exception $e){
			return false;
		}
	}

	function SocketStop() {
		fclose($this->connect);
		return TRUE;
	}

	function SocketSend($in,$wait='') {
		fputs($this->connect, $in, strlen($in));
		if(empty($wait)) {
			$rcv = fgets($this->connect, 1024);
			$this->rcv .= $rcv;
			return $rcv;
		}
		return TRUE;
	}

	# Mail Socket #
	function socketMailStart() {
		if (!$this->SocketStart()){
			return false;
		}
		if (!isset($_SERVER['SERVER_NAME'])  || empty($_SERVER['SERVER_NAME'])) { $serv = 'unknown'; }
		else { $serv = $_SERVER['SERVER_NAME']; }
		if (!$this->SocketSend("HELO $serv\r\n")){
			return false;
		}
		return true;
	}

	function socketMailSend($to,$from,$headers,$body) {

		$this->recipient = $to;
		$this->log[] = "Socket vers $to";
		$this->rcv = '';

		$this->SocketSend( "MAIL FROM:$from\r\n" );
		
		$this->SocketSend( "RCPT TO:$to\r\n" );
		if (isset($this->buildMail->mailModel->header['CC'])):
			while ( $cc = $this->buildMail->mailModel->header['CC']->rawValueIter() ) {
				$this->SocketSend( "RCPT TO:$cc\r\n" );
			}
		endif;
		if (isset($this->buildMail->mailModel->header['BCC'])):
			while ( $bcc = $this->buildMail->mailModel->header['BCC']->rawValueIter() ) {
				$this->SocketSend( "RCPT TO:$bcc\r\n" );
			}
		endif;
		$this->SocketSend( "DATA\r\n" );
		$this->SocketSend( $this->CleanMailDataString($headers)."\r\n", 'NOWAIT' );
		$this->SocketSend( $this->CleanMailDataString($body)."\r\n", 'NOWAIT' );
		$this->SocketSend( ".\r\n" );
		$this->SocketSend( "RSET\r\n" );

		if ( preg_match ( $this->socketErrorCode, $rcv, $out ) ) {
			$this->log[] = $rcv ;
		}

		$this->log[] = "Fin de l'envoi vers $to";

		return TRUE;
	}

	function socketMailStop() {
		if (!$this->SocketSend("QUIT\r\n")) return false;
		if (!$this->SocketStop()) return false;
		return TRUE;
	}

	function socketMailLoop() {
		$body = $this->buildMail->makeBody();
		$header = $this->buildMail->makeHeader(array ( 'noTO' =>  TRUE, 'noBCC' =>  TRUE));
		$from = $this->buildMail->mailModel->header['FROM']->rawValue();

		if (!$this->socketMailStart()){
			return false;
		}
		if ($this->buildMail->mailModel->param['TO1by1']) {
			while ( $to = $this->buildMail->mailModel->header['TO']->rawValueIter() ) {
				$header = $this->buildMail->makeHeader(array ( 'noTO' =>  TRUE, 'TO' => $to, 'noBCC' =>  TRUE ));
				$mailSentReport = $this->socketMailSend(  	$to,
															$from,
															$header,
															$body
														);
				if ($mailSentReport) {
					$this->log['Success'][] = "Success sending to {$to}";
					return true;
				} else {
					$this->log['Error'][] = "Error sending to {$to}";
					return false;
				}
			}
		} else {
			$to = $this->buildMail->mailModel->header['TO']->rawValue();
			$mailSentReport =   $this->socketMailSend(  $to,
														$from,
														$header,
														$body
													);
			if ($mailSentReport){
				$this->log['Success'][] = "Success sending to {$to}";
				return true;
			} else {
				$this->log['Error'][] = "Error sending to {$to}";
				return false;
			}
		}
		if (!$this->socketMailStop()){
			return false;
		}
		
		return TRUE;
	}

	function CleanMailDataString($data) {
	
		$data = preg_replace("/([^\r]{1})\n/", "\\1\r\n", $data);
		$data = preg_replace("/\n\n/", "\n\r\n", $data);
		$data = preg_replace("/\n\./", "\n..", $data);
		
		return $data;
	}
	
	function DirtMailDataString($data) {
		$data = preg_replace("/([^\r]{1})\r\n/", "\\1\n", $data);
		#$data = preg_replace("/\n\n/", "\n\r\n", $data);
		$data = preg_replace("/\n\./", "\n..", $data);
		return $data;
	}
}

class mailMain {
	function __construct() {
		$this->model = new mailModel;
		$this->build = new buildMail($this->model);
		$this->sender = new mailSender($this->build);
	}
	
	function log() {
		return array(	'Model' => $this->model->log,
						'Build' => $this->build->log,
						'Sender' => $this->sender->log
					);
	}
}



?>