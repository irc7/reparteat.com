<?php
/*
Author: info@ismaelrc.es
Date: 2019-08-26

Usuario
*/

class Password extends System {
	
	private $pass;
	private $opciones;
	
	public function __construct() {
		$this->opciones = ['cost' => 15];
	}
	
	public function encriptPwd($pass) {
		$pwd_encript = password_hash($pass, PASSWORD_BCRYPT, $this->opciones);
		
		return $pwd_encript;
	}
	
}