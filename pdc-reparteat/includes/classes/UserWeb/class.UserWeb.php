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


class UserWeb extends System {
	
	protected $id;
	protected $idtype;
	protected $name;
	protected $surname;
	protected $dni;
	protected $phone;
	protected $idtelegram;
	protected $login;
	protected $pass;
	protected $status;
	protected $saldo;
	protected $superadmin;
	
	
	public function __construct() {
	}
	
	public function checkUserWeb($login = null, $pass= null) {
		global $connectBD;
		
		$q = "select * from ".preBD."user_web where true and Login = '" . $login . "'";
		$res = checkingQuery($connectBD, $q);
		$data = mysqli_fetch_object($res);
		
		if(password_verify($pass, $data->pass)) {
			return $data;
		} else {
			return false;
		}
	}
	
	public function infoUserWebByLogin($login = null) {
		global $connectBD;
		
		$q = "select * from ".preBD."user_web where true and Login = '" . $login . "'";
		$res = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($res)) {
			return $data;
		} else {
			return false;
		}
	}
	public function infoUserWebById($id = null) {
		global $connectBD;
		
		$q = "select * from ".preBD."user_web where true and ID = '" . $id . "'";
		$res = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($res)) {
			return $data;
		} else {
			return false;
		}
	}
	public function listUserWebByType($idType = null) {
		global $connectBD;
		
		$q = "select * from ".preBD."user_web where true and IDTYPE = '" . $idType . "' and STATUS = 1 order by SURNAME asc, NAME asc";
		$r = checkingQuery($connectBD, $q);
		
		$users = array();
		while($data = mysqli_fetch_object($r)) {
			$users[] = $data;
		}
		return $users;
	}
	
	public function userWebAddress($id = null) {
		global $connectBD;
		
		$q = "select ".preBD."user_sup_web_address.*
				from ".preBD."user_sup_web_address 
				where ".preBD."user_sup_web_address.IDASSOC = " . $id. " and TYPE = 'user' order by FAV desc";
		$r = checkingQuery($connectBD, $q);
		
		$address = array();
		while($row = mysqli_fetch_object($r)) {
			$address[] = $row;
		}
		return $address;
	}
	public function add(){
		
		global $connectBD;
		
		$now = new DateTime();
		
		$opciones = ['cost' => 15,];
		$pass_encript = password_hash($this->pass, PASSWORD_BCRYPT, $opciones);
	
		$q = "INSERT INTO ".preBD."user_web (IDTYPE, NAME, SURNAME, DNI, PHONE, IDTELEGRAM, LOGIN, PASS, STATUS, SUPERADMIN, DATE_REGISTER, SALDO) 
				VALUES 
			('".$this->idtype."', '".$this->name."', '".$this->surname."', '".$this->dni."', '".$this->phone."', '".$this->idtelegram."', '".$this->login."', '".$pass_encript."', '".$this->status."', '".$this->superadmin."', '".$now->format('Y-m-d H:i:s')."',".number_format($this->saldo,2,'.','').")";
		
		if(!checkingQuery($connectBD, $q)) {
			return false;	
		}else {
			$idNew = mysqli_insert_id($connectBD);
			$this->id = $idNew;
			return $idNew;					
		}
	}
	public function update($id = null){
		
		global $connectBD;
		$q = "UPDATE `".preBD."user_web` SET
				`NAME`='".$this->name. "', 
				`SURNAME`='".$this->surname . "', 
				`DNI`= '".$this->dni . "',
				`SUPERADMIN`='".$this->superadmin."', 
				`PHONE`='".$this->phone. "', 
				`IDTELEGRAM`='".$this->idtelegram. "', 
				`IDTYPE`='".$this->idtype. "', 
				`SALDO`='".$this->saldo . "', 
				`STATUS`='".$this->status . "' 
			WHERE ID = " . $id;
		if(!checkingQuery($connectBD, $q)) {
			return false;	
		}else {
			return true;					
		}
	}
	
	public function updatePass($id = null){
		global $connectBD;
		
		$opciones = ['cost' => 15,];
		$pass_encript = password_hash($this->pass, PASSWORD_BCRYPT, $opciones);
		
		$q = "update ".preBD."user_web set 
			PASS = '". $pass_encript ."'
			where ID = '" . $id."'";
		
		if(!checkingQuery($connectBD, $q)) {
			return false;	
		}else {
			return true;					
		}
	}
	public function updateLog($id = null){
		global $connectBD;
		
		$q = "update ".preBD."user_web set 
				LOGIN = '". $this->login ."'
			where ID = '" . $id."'";
		
		if(!checkingQuery($connectBD, $q)) {
			return false;
		}else {
			return true;				
		}
	}
	
	public function resetStringName($string){
		global $connectBD;
		
		$string = utf8_decode($string);
		$string = strtolower($string);
		$string = ucwords($string);
		$string = utf8_encode($string);
		$string = mysqli_real_escape_string($connectBD, $string);
		return $string;
	}
	
	
	public function validateDNI(){
		if(strlen($this->dni)<9) {
			return false;
		}
	 
		$letra = substr($this->dni, -1, 1);
		$numero = substr($this->dni, 0, 8);
	 
		$numero = str_replace(array('X', 'Y', 'Z'), array(0, 1, 2), $numero);
	 
		$modulo = $numero % 23;
		$letras_validas = "TRWAGMYFPDXBNJZSQVHLCKE";
		$letra_correcta = substr($letras_validas, $modulo, 1);
	 
		if($letra_correcta!=$letra) {
			return false;
		}else {
			return true;
		}
	}
	public function getUserWebSupplier($id){
		global $connectBD;
		
		$q = "select * from ".preBD."user_web_supplier_assoc where true and IDUSER = " . $id;
		$r = checkingQuery($connectBD, $q);
		
		$total = mysqli_num_rows($r);
		
		return $total;
		
	}
	public function upStatus($id, $status){
		global $connectBD;
		$q = "UPDATE ".preBD."suppliers SET 
				STATUS = ". $status ." 
				where ID = " . $id;
		checkingQuery($connectBD, $q);
	}
	public function checkingTokenApp($mail) {
		global $connectBD;
		$q = "select LAST_TOKEN from ".preBD."user_web where LOGIN = '" . $mail. "'";
		
		$r = checkingQuery($connectBD, $q);
		if($row = mysqli_fetch_object($r)){
			return $row->LAST_TOKEN;		
		}else {
			return false;
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