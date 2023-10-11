<?php
require_once "includes/class/class.phpmailer.php";
require_once "lib/FileAccess/class.FileAccess.php";

class SystemMailer {
	
	private $template = "";
	private $body = "";
	
	private $mailer;
	private $fileAccess;
	
	public function __construct() {
		$this->mailer = new PHPMailer;
		$this->mailer->IsMail();
	}
	
	public function setFrom($email, $name) {
		$this->mailer->SetFrom($email, $name);
	}
	
	public function setSubject($subject) {
		$this->mailer->Subject = $subject;
	}
	
	public function addAddress($email, $name) {
		$this->mailer->AddAddress($email, $name);
	}
	
	public function send() {
		$this->mailer->MsgHTML($this->body);
		return $this->mailer->Send();
	}
	
	public function loadTemplate($template) {
		$this->template = $template;
		$this->fileAccess = new FileAccess($this->template);
		$this->body = $this->fileAccess->read();
	}
	
	public function setParam($param, $value) {
		$this->body = str_replace($param, $value, $this->body);
	}
	
}

?>