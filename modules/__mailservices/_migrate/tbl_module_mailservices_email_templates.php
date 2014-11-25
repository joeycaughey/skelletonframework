<?php
global $ModuleMailServicesEmailTemplatesModel;

$ModuleMailServicesEmailTemplatesModel->insert(array(
	"template_id" => "newsletter-signup-email", 
	"subject" => "Thank you for joining our mailing list.",
	"body" => file_contents("modules/__mailservices/_email_templates/newsletter-signup-email.html")
));

$ModuleMailServicesEmailTemplatesModel->insert(array(
	"template_id" => "invoice-email", 
	"subject" => "Invoice Due ({INVOICE_NUMBER})",
	"body" => file_contents("modules/__mailservices/_email_templates/invoice-email.html")
));








