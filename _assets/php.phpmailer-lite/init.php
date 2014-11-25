<?php
require_once("_assets/php.phpmailer-lite/class.phpmailer-lite.php");

function phpmailer_mail($to, $subject, $message, $options=array()) {

	$mail             = new PHPMailerLite();
	
	if ($options["html"]) {
		$body             = file_get_contents($options["html"]);
		$body             = eregi_replace("[\]",'',$body);
	}
	
	//$mail->IsSMTP(); // telling the class to use SMTP
	$mail->Host       = "mail.joeycaughey.com"; // SMTP server
	$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
	                                           // 1 = errors and messages
	                                           // 2 = messages only
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->Host       = "mail.joeycaughey.com"; // sets the SMTP server
	$mail->Port       = 25;                    // set the SMTP port for the GMAIL server
	$mail->Username   = "caughey"; // SMTP account username
	$mail->Password   = "nirvana1";        // SMTP account password
	
	$mail->SetFrom('noreply@pooolo.joeycaughey.com', 'Pooolo Dev');
	
	$mail->AddReplyTo("noreply@pooolo.joeycaughey.com","Pooolo Dev");
	
	$mail->Subject    = $subject;
	
	$mail->AltBody    = $message; // optional, comment out and test
	
	if ($options["html"]) $mail->MsgHTML($body);
	else $mail->MsgHTML(nl2br($message));
	
	$mail->AddAddress($to, false);
	
	if (is_array($options["attachments"])) {
		foreach($options["attachments"] as $attachment) {
			$mail->AddAttachment($attachment);  
		}
	}
	
	if(!$mail->Send()) {
		return false;
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		return true;
	  	echo "Message sent!";
	}
    
}