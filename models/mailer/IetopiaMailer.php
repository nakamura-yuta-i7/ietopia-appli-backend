<?php
class IetopiaMailer {
	static function getInstance() {
		$mail = new PHPMailer;

		#$mail->SMTPDebug = 3;
		$mail->isSMTP();
		$mail->Host     = 'smtp.mail.yahoo.co.jp';
		$mail->SMTPAuth = true;
		$mail->Username = 'yuta_nakamura_i7@yahoo.co.jp';
		$mail->Password = IETOPIA_MAILER_SMTP_PASSWORD;
		
		# $mail->isHTML(true);

		$mail->Subject = 'Ietopia API Backend Service Mailer';
		return $mail;
	}
}