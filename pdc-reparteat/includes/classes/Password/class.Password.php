<?php
/*
Author: info@ismaelrc.es
Date: 2019-08-26

Usuario
*/


if(strpos($_SERVER["SCRIPT_NAME"], "modules")) {
	require_once "../../includes/classes/class.System.php";
}else {
	require_once "includes/classes/class.System.php";
}

class Password extends System {
	
	private $pass;
	private $opciones;
	
	public function __construct($pass) {
		$this->pass = trim($pass);
		$opciones = ['cost' => 15,];
	}
	
	public function encriptPwd() {
		$pwd_encript = password_hash($this->pass, PASSWORD_BCRYPT, $this->opciones);
		return $pwd_encript;
	}
	
}