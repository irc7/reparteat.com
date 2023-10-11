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


class User extends System {
	
	protected $id;
	protected $login;
	protected $name;
	protected $text;
	protected $image;
	protected $pwd;
	protected $type;
	
	
	public function __construct() {
	}
	
	public function checkUser($login = null, $pwd= null) {
		global $connectBD;
		
		$q = "select * from ".preBD."users where true and Login = '" . $login . "'";
		$res = checkingQuery($connectBD, $q);
		$data = mysqli_fetch_object($res);
		
		if(password_verify($pwd, $data->Pwd)) {
			return $data;
		} else {
			return false;
		}
	}
	public function infoUserByLogin($login = null) {
		global $connectBD;
		
		$q = "select * from ".preBD."users where true and Login = '" . $login . "'";
		$res = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($res)) {
			return $data;
		} else {
			return false;
		}
	}
	public function infoUserById($id = null) {
		global $connectBD;
		
		$q = "select * from ".preBD."users where true and ID = '" . $id . "'";
		$res = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($res)) {
			return $data;
		} else {
			return false;
		}
	}
	
	public function add(){
		
		global $connectBD;
		
		$opciones = ['cost' => 15,];
		$pwd_encript = password_hash($this->pwd, PASSWORD_BCRYPT, $opciones);
		
		$q = "INSERT INTO ".preBD."users (Login, Name, Pwd, Type, Image, Text) 
				VALUES 
			('".$this->login."', '".$this->name."', '".$pwd_encript."', '".$this->type."', '".$this->image."', '".$this->text."')";
		
		if(!checkingQuery($connectBD, $q)) {
			return false;	
		}else {
			return true;					
		}
	}
	
	public function updatePwd($id, $pwd){
		global $connectBD;
		
		$opciones = ['cost' => 15,];
		$pwd_encript = password_hash($pwd, PASSWORD_BCRYPT, $opciones);
		
		$q = "update ".preBD."users set 
		Pwd = '". $pwd_encript ."'
		where ID = '" . $id."'";
		
		if(!checkingQuery($connectBD, $q)) {
			return false;	
		}else {
			return true;					
		}
	}
	/*
	
	public function delete($id){
		$q = "delete from ".preBD."pm_collegiates where code = " . $id;
		
		$res = checkingQuery($connectBD, $q);
		return $res;		
	}
	*/
}