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


class Address extends System {
	
	protected $id;
	protected $idassoc;
	protected $type;
	protected $street;
	protected $idzone;
	protected $fav;
	protected $shipping;
	protected $image;
	protected $active;
	
	
	public function __construct() {
	}
	
	
	public function infoAddress($id = null, $type = null) {
		global $connectBD;
		
		$q = "select * from ".preBD."user_sup_web_address where true and IDASSOC = '" . $login . "' and TYPE = '".$type."'";
		$res = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($res)) {
			return $data;
		} else {
			return false;
		}
	}
	
	public function add(){
		
		global $connectBD;
		if(!isset($this->active) || $this->active === null){
			$this->active = 1;
		}
		$q = "INSERT INTO ".preBD."user_sup_web_address (IDASSOC, TYPE, STREET, IDZONE, FAV, IMAGE, ACTIVE) 
				VALUES 
			('".$this->idassoc."', '".$this->type."', '".$this->street."', '".$this->idzone."', '".$this->fav."', '".$this->image."', '".$this->active."')";
		
		if(!checkingQuery($connectBD, $q)) {
			return false;	
		}else {
			$idNew = mysqli_insert_id($connectBD);
			return $idNew;
		}
	}
	public function update($id = null){
		
		global $connectBD;
		if(!isset($this->active) || $this->active === null){
			$this->active = 1;
		}
		$q = "UPDATE `".preBD."user_sup_web_address` SET 
					`STREET`='".$this->street."',
					`IDZONE`='".$this->idzone."',
					`IMAGE`='".$this->image."',
					`ACTIVE`='".$this->active."'
				WHERE ID = " . $id;
	
		if(!checkingQuery($connectBD, $q)) {
			return false;	
		}else {
			return true;					
		}
	}
	public function updateAll($id = null){
		
		global $connectBD;
		$q = "UPDATE `".preBD."user_sup_web_address` SET 
					`STREET`='".$this->street."',
					`TYPE`='".$this->type."',
					`IDZONE`='".$this->idzone."',
					`IMAGE`='".$this->image."',
					`ACTIVE`='".$this->active."',
					`FAV`='".$this->fav."'
				WHERE ID = " . $id;
	
		if(!checkingQuery($connectBD, $q)) {
			return false;	
		}else {
			return true;					
		}
	}
	public function upStatus($id = null, $status = null){
		
		global $connectBD;
		$q = "UPDATE `".preBD."user_sup_web_address` SET 
					`ACTIVE`='".$status."'
				WHERE ID = " . $id;
	
		if(!checkingQuery($connectBD, $q)) {
			return false;	
		}else {
			return true;					
		}
	}
	public function infoByID($id = null) {
		global $connectBD;
		
		$q = "select * from ".preBD."user_sup_web_address where true and ID = '".$id."'";
		$res = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($res)) {
			return $data;
		} else {
			return false;
		}
	}
	/*
	public function deletePoint($id){
		$q = "delete from ".preBD."user_sup_web_address where true and TYPE='points' and ID = " . $id;
		$res = checkingQuery($connectBD, $q);
		return $res;		
	}
	*/
}