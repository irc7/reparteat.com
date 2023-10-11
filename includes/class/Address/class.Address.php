<?php
/*
Author: info@ismaelrc.es
Date: 2019-08-26
Last Edit: 2022-11-24
Direcciones
*/

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
		
		$q = "select * from ".preBD."user_sup_web_address where true and IDASSOC = '" . $id . "' and TYPE = '".$type."'";
		$res = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($res)) {
			return $data;
		} else {
			return false;
		}
	}
	public function infoAddressZone($id = null, $type = null, $zone = null) {
		global $connectBD;
		
		$q = "select * from ".preBD."user_sup_web_address 
				where true 
				and IDASSOC = '" . $id . "' 
				and IDZONE = '" . $zone . "' 
				and TYPE = '".$type."'";
		$res = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($res)) {
			return $data;
		} else {
			return false;
		}
	}
	public function listPointsAddress($idZone = null) {
		global $connectBD;
		
		$q = "select * from ".preBD."user_sup_web_address where true and ACTIVE = 1 and TYPE = 'points' and IDZONE = " . $idZone;
		$res = checkingQuery($connectBD, $q);
		$data = array();
		while($row = mysqli_fetch_object($res)) {
			$data[] = $row;
		}
		return $data;
	}
	
	public function add(){
		
		global $connectBD;
		if(!isset($this->active) || $this->active === null){
			$this->active = 1;
		}
		
		$q = "INSERT INTO ".preBD."user_sup_web_address (IDASSOC, TYPE, STREET, IDZONE, FAV, ACTIVE) 
				VALUES 
			('".$this->idassoc."', '".$this->type."', '".$this->street."', '".$this->idzone."', '".$this->fav."', '".$this->active."')";
		
		if(!checkingQuery($connectBD, $q)) {
			return false;	
		}else {
			return true;					
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
					`FAV`='".$this->fav."',
					`ACTIVE`='".$this->active."'
				WHERE ID = " . $id;
	
		if(!checkingQuery($connectBD, $q)) {
			return false;	
		}else {
			return true;					
		}
	}
	public function actionZoneActive($idZone = null, $idSup = null, $idAddress = null, $active = null){
		
		global $connectBD;
		if(!isset($this->active) || $this->active === null){
			$this->active = 1;
		}
		$q = "UPDATE `".preBD."user_sup_web_address` SET `ACTIVE`='".$active."'
			WHERE true and TYPE = 'proveedor' and ID = " . $idAddress. " and IDASSOC=".$idSup." and IDZONE=".$idZone;
	
		if(!checkingQuery($connectBD, $q)) {
			return false;	
		}else {
			return true;					
		}
	}
	public function infoAddressbyId($idA = null) {
		global $connectBD;
		
		$q = "select 
				".preBD."user_sup_web_address.STREET,
				".preBD."user_sup_web_address.ACTIVE,
				".preBD."user_sup_web_address.FAV,
				".preBD."user_sup_web_address.IDZONE,
				".preBD."user_sup_web_address.IDASSOC,
				".preBD."user_sup_web_address.IMAGE,
				".preBD."zone.CITY,
				".preBD."zone.CP,
				".preBD."zone.PROVINCE,
				".preBD."zone.COUNTRY
				from ".preBD."user_sup_web_address 
				inner join ".preBD."zone on ".preBD."zone.ID = ".preBD."user_sup_web_address.IDZONE
				where true and ".preBD."user_sup_web_address.ID = " . $idA;
				
		$res = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($res)) {
			return $data;
		} else {
			return false;
		}
	}
	public function updateFavZone($idUser = null, $idZone = null){
		
		global $connectBD;
		$q = "select * from ".preBD."user_sup_web_address 
				where true 
				and FAV = 0 
				and TYPE = 'user' 
				and IDASSOC = " . $idUser. " 
				and IDZONE = " . $idZone . "
				order by ID asc limit 0,1";
		
		$res = checkingQuery($connectBD, $q);
		if($row = mysqli_fetch_object($res)) {
		
			$q = "UPDATE `".preBD."user_sup_web_address` SET 
						`FAV`= 1 WHERE ID = " . $row->ID;
		
			if(!checkingQuery($connectBD, $q)) {
				return false;	
			}else {
				return true;					
			}
		}else {
			return false;	
		}

	}
	public function deleteAddressUser($idAddress = null, $idUser = null, $idZone = null){
		global $connectBD;

		$addressBD = $this->infoAddressbyId($idAddress);
		
		if($addressBD->FAV == 1) {
			$this->updateFavZone($idUser, $idZone);
		}
		
		$q = "DELETE FROM `".preBD."user_sup_web_address` WHERE TYPE = 'user' and IDASSOC = " . $idUser . " and ID = " . $idAddress;
		$res = checkingQuery($connectBD, $q);
		return $res;
	}
	
	public function deleteByUser($id = null){
		global $connectBD;
		
		$q = "DELETE FROM `".preBD."user_sup_web_address` WHERE TYPE = 'user' and IDASSOC = " . $id;
		$res = checkingQuery($connectBD, $q);
		return $res;
	}
	
}