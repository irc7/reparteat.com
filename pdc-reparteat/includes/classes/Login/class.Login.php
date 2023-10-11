<?php

/*
Author: irodriguez@aomcomunicacion.com
Date: 2019-08-26

Crear session de login
*/
if(strpos($_SERVER["SCRIPT_NAME"], "modules")) {
	require_once "../../includes/classes/class.System.php";
	require_once "../../includes/classes/User/class.User.php";
}else {
	require_once "includes/classes/class.System.php";
	require_once "includes/classes/User/class.User.php";
}

class Login extends System {
	
	private $email;
	private $pass;
	
	protected $user;
	
	
	public function __construct($email, $pass) {
		$this->email = trim($email);
		$this->pass = $pass;
	}
	
	public function searchUser() {
		global $connectBD;
		
		$this->user = new User();
		$data = $this->user->checkUser($this->email, $this->pass);
		
		if($data) {
			$ql = "INSERT INTO `".preBD."users_log`(`IDUSER`, `MAIL`, `IP`, `DATE_LOG`, `LOG`) 
					VALUES 
				('".$data->ID."','".$this->email."','".$_SERVER['REMOTE_ADDR']."',NOW(),1)";
			checkingQuery($connectBD, $ql);
			
		}else {
			$ql = "INSERT INTO `".preBD."users_log`(`IDUSER`, `MAIL`, `IP`, `DATE_LOG`, `LOG`) 
					VALUES 
				(0,'".$this->email."','".$_SERVER['REMOTE_ADDR']."',NOW(),0)";
			checkingQuery($connectBD, $ql);
		}
		return $data;
	}

}