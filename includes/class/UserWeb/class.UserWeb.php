<?php
/*
Author: info@ismaelrc.es
Date: 2019-08-26

Usuario
*/


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
		
		$q = "select * from ".preBD."user_web where true and LOGIN = '" . $login . "' and STATUS = 1";
		$res = checkingQuery($connectBD, $q);
		$data = mysqli_fetch_object($res);
		
		if(password_verify($pass, $data->PASS)) {
			return $data;
		} else {
			return false;
		}
	}
	
	public function infoUserWebByLogin($login = null) {
		global $connectBD;
		
		$q = "select * from ".preBD."user_web where true and LOGIN = '" . $login . "'";
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
	public function infoUserWebSuperadmin() {
		global $connectBD;
		
		$q = "select * from ".preBD."user_web where true and IDTYPE = 1 and STATUS = 1 and ID != 1";//todos los usuarios menos el primero que es el del desarrollador
		$res = checkingQuery($connectBD, $q);
		
		$data = array();
		while($row = mysqli_fetch_object($res)) {
			$data[] = $row;
		} 
		return $data;
		
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
	public function userWebAddressZone($id = null, $idZone = null) {
		global $connectBD;
		
		$address = array();
		if($idZone > 0) {
			$q = "select ".preBD."user_sup_web_address.*
					from ".preBD."user_sup_web_address 
					where ".preBD."user_sup_web_address.IDASSOC = " . $id. " and TYPE = 'user' and IDZONE = ".$idZone." order by FAV desc";
			$r = checkingQuery($connectBD, $q);
			
			while($row = mysqli_fetch_object($r)) {
				$address[] = $row;
			}
		}
		return $address;
	}
	public function add(){
		
		global $connectBD;
		
		$now = new DateTime();
		
		$opciones = ['cost' => 15,];
		$pass_encript = password_hash($this->pass, PASSWORD_BCRYPT, $opciones);
	
		$q = "INSERT INTO ".preBD."user_web (IDTYPE, NAME, SURNAME, DNI, PHONE, IDTELEGRAM, LOGIN, PASS, STATUS, SUPERADMIN, DATE_REGISTER) 
				VALUES 
			('".$this->idtype."', '".$this->name."', '".$this->surname."', '".$this->dni."', '".$this->phone."', '".$this->idtelegram."', '".$this->login."', '".$pass_encript."', '".$this->status."', '".$this->superadmin."', '".$now->format('Y-m-d H:i:s')."')";
		
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
	public function confirmUser($Email = null){
		global $connectBD;
		$q = "select * from ".preBD."user_web where true and LOGIN = '" . $Email . "' and STATUS = 5";
		$res = checkingQuery($connectBD, $q);
		$total = mysqli_num_rows($res);
		$data = mysqli_fetch_object($res);
		
		if($total == 1) {
			$q = "update ".preBD."user_web set 
					STATUS = 1
				where ID = '" . $data->ID."'";
			checkingQuery($connectBD, $q);
			
			return "<span class='green'>¡Cuenta confirmada!</span><br/>Inicie sesión y empiece a realizar sus pedidos.";
		}else{
			return "<span class='danger'>No existe ninguna cuenta asociada al correo electrónico <em>".$Email."</em> pendiente de confirmación</span>";
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
	public function getUserWebInfoSupplier($id){
		global $connectBD;
		
		$q = "select ".preBD."suppliers.* 
				from ".preBD."user_web_supplier_assoc 
				inner join ".preBD."suppliers on ".preBD."user_web_supplier_assoc.IDSUPPLIER = ".preBD."suppliers.ID
				where true and ".preBD."user_web_supplier_assoc.IDUSER = " . $id;
		$r = checkingQuery($connectBD, $q);
		
		$data = array();
		while($row = mysqli_fetch_object($r)) {
			$data[] = $row;
		}
		return $data;
		
	}
	public function upStatus($id, $status){
		global $connectBD;
		$q = "UPDATE ".preBD."suppliers SET 
				STATUS = ". $status ." 
				where ID = " . $id;
		checkingQuery($connectBD, $q);
	}
	public function checkingSaldo($idUser){
		global $connectBD;
		$q = "select SALDO from ".preBD."user_web where ID = " . $idUser;
		
		$r = checkingQuery($connectBD, $q);
		$row = mysqli_fetch_object($r);
		return $row->SALDO;
	}
	public function updateSaldo($idUser, $saldo){
		global $connectBD;
		$q = "UPDATE ".preBD."user_web SET 
				SALDO = " . number_format($saldo,2,".","") . " 
				where ID = " . $idUser;
		checkingQuery($connectBD, $q);
	}
	public function checkingTokenApp($mail) {
		global $connectBD;
		$q = "select LAST_TOKEN from ".preBD."user_web where LOGIN = '" . $mail . "'";
		
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