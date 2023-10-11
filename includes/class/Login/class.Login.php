<?php

/*
Author: info@ismaelrc.es
Date: 2019-08-26

Crear session de login
*/

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
		
		$this->user = new UserWeb();
		
		$data = $this->user->checkUserWeb($this->email, $this->pass);
		
		return $data;
	}
	public function checkUser() {
		global $connectBD;
		
		$this->user = new UserWeb();
		
		$data = $this->user->infoUserWebByLogin($this->email);
		
		return $data;
	}

}