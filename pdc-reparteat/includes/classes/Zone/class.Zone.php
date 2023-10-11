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


class Zone extends System {
	
	protected $id;
	protected $city;
	protected $cp;
	protected $province;
	protected $country;
	protected $status;
	protected $orderLimit;
	protected $repLimit;
	protected $idsresponsables;
	protected $shipping;
	protected $type;
	
	
	public function __construct() {
	}
	
	
	public function infoZone($id = null) {
		global $connectBD;
		
		$q = "select * from ".preBD."zone where true and ID = '" . $id . "'";
		$res = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($res)) {
			return $data;
		} else {
			return false;
		}
	}
	public function infoById($id = null) {
		global $connectBD;
		
		$q = "select * from ".preBD."zone where true and ID = '" . $id . "'";
		$res = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($res)) {
			return $data;
		} else {
			return false;
		}
	}
	public function infoZoneUser($id = null) {
		global $connectBD;
		
		$q = "select ".preBD."user_web_zone_assoc.IDUSER
				from ".preBD."user_web_zone_assoc 
				where ".preBD."user_web_zone_assoc.IDZONE = " . $id;
		$r = checkingQuery($connectBD, $q);
		
		$users = array();
		while($data = mysqli_fetch_object($r)) {
			$users[] = $data->IDUSER;
		}
		return $users;
	}
	public function listZones() {
		global $connectBD;
		
		$q = "select * from ".preBD."zone where true and STATUS = 1";
		$res = checkingQuery($connectBD, $q);
		$zones = array();
		while($data = mysqli_fetch_object($res)) {	
			$zones[] = $data;
		}
		if(count($zones)>0) {
			return $zones;
		} else {
			return false;
		}
	}
	public function allZones() {
		global $connectBD;
		
		$q = "select * from ".preBD."zone where true and STATUS = 1";
		$res = checkingQuery($connectBD, $q);
		$zones = array();
		while($data = mysqli_fetch_object($res)) {	
			$zones[] = $data;
		}
		if(count($zones)>0) {
			return $zones;
		} else {
			return false;
		}
	}
	
	public function add(){
		
		global $connectBD;
		
		$q = "INSERT INTO ".preBD."zone (CITY, CP, PROVINCE, COUNTRY, STATUS, ORDER_LIMIT, REP_LIMIT, SHIPPING, TYPE) 
				VALUES 
			('".$this->city."', '".$this->cp."', '".$this->province."', '".$this->country."', '".$this->status."', '".$this->orderLimit."', '".$this->repLimit."', '".$this->shipping."', '".$this->type."')";
		
			if(!checkingQuery($connectBD, $q)) {
				return false;	
			}else {
				$idNew = mysqli_insert_id($connectBD);
				$this->id = $idNew;
				//asociaciones
				$this->updateUserRes($idNew);
				return $idNew;					
		}
	}
	public function update($id = null){
		
		global $connectBD;
		$q = "UPDATE `".preBD."zone` SET 
				`CITY`='".$this->city."',
				`CP`='".$this->cp."',
				`PROVINCE`='".$this->province."', 
				`COUNTRY`='".$this->country."', 
				`STATUS`='".$this->status."',
				`ORDER_LIMIT`='".$this->orderLimit."',
				`REP_LIMIT`='".$this->repLimit."',
				`SHIPPING`='".$this->shipping."',
				`TYPE`='".$this->type."'
			WHERE ID = " . $id;
		
			if(!checkingQuery($connectBD, $q)) {
				return false;	
			}else {
				//asociaciones
				$this->updateUserRes($id);
				return $id;					
		}
	}
	public function updateUserRes($id = null) {
		global $connectBD;
		
		$q = "DELETE FROM `".preBD."user_web_zone_assoc` WHERE IDZONE = ".$id;
		checkingQuery($connectBD, $q);
		if(count($this->idsresponsables)) {
			$q = "INSERT INTO `".preBD."user_web_zone_assoc`(`IDUSER`, `IDZONE`) 
					VALUES ";
				for($i=0;$i<count($this->idsresponsables);$i++) {
					$q .= "(".$this->idsresponsables[$i].",".$id.")";
					if($i<count($this->idsresponsables)-1){
						$q .= ", ";
					}
				}
			checkingQuery($connectBD, $q);		
		}
	}
	
	public function totalSupplierByZone($id = null) {
		global $connectBD;
		
		$q = "select DISTINCT ".preBD."user_sup_web_address.IDASSOC
			 from ".preBD."user_sup_web_address
			 where true and ".preBD."user_sup_web_address.TYPE = 'proveedor' and ".preBD."user_sup_web_address.IDZONE = " . $id;
		
		$res = checkingQuery($connectBD, $q);
		$supplier = array();
		while($data = mysqli_fetch_object($res)) {	
			$supplier[] = $data;
		}
		return $supplier;
		
	}
	public function listTypeZone() {
		global $connectBD;
		$q = "SHOW COLUMNS FROM ".preBD."zone WHERE Field='TYPE'";
		
		$res = checkingQuery($connectBD, $q);
		$data = array();
		$row = mysqli_fetch_object($res);
		preg_match("/^enum\(\'(.*)\'\)$/",$row->Type,$matches);
		$data = explode("','",$matches[1]);
		
		return $data;
		
	}
	
	public function delete($id){
		$q = "delete from ".preBD."zone where ID = " . $id;
		
		$res = checkingQuery($connectBD, $q);
		return $res;		
	}
	
}