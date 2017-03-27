<?php
class IetopiaMailer extends PHPMailer {
	static function getInstance() {
		$mail = new static;
		
		#$mail->SMTPDebug = 3;
		$mail->isSMTP();
		$mail->Host     = IETOPIA_API_SERVICE_SMTP;
		$mail->SMTPAuth = true;
		$mail->Username = IETOPIA_API_SERVICE_EMAIL;
		$mail->Password = IETOPIA_MAILER_SMTP_PASSWORD;
		$mail->Port     = 587;
		
		# $mail->isHTML(true);

		$mail->setFrom(IETOPIA_API_SERVICE_EMAIL, IETOPIA_API_SERVICE_NAME);
		$mail->Subject = (IS_DEV?"[TEST] ":"");
		return $mail;
	}
	function setSubject($subject) {
		$this->Subject .= mb_encode_mimeheader($subject,'ISO-2022-JP');
	}
	function setHtmlBody($html) {
		$this->ContentType = "text/html";
		$this->Body = $html;
	}
}