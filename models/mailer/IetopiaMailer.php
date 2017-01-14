<?php
class IetopiaMailer {
	static function getInstance() {
		$mail = new PHPMailer;

		#$mail->SMTPDebug = 3;
		$mail->isSMTP();
		$mail->Host     = IETOPIA_API_SERVICE_SMTP;
		$mail->SMTPAuth = true;
		$mail->Username = IETOPIA_API_SERVICE_EMAIL;
		$mail->Password = IETOPIA_MAILER_SMTP_PASSWORD;
		
		# $mail->isHTML(true);

		$mail->setFrom(IETOPIA_API_SERVICE_EMAIL, 'Ietopia API Backend Service');
		$mail->Subject = (IS_DEV?"TEST ":"");
		return $mail;
	}
}