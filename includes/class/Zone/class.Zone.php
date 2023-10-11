<?php
/*
Author: info@ismaelrc.es
Date: 2019-08-26

Usuario
*/

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
	protected $time_delivery;
	protected $time_check_order;
	protected $time_orders_zones;
	
	
	public function __construct() {
	}
	
	public function updateResp($id = null){
		
		global $connectBD;
		$q = "UPDATE `".preBD."zone` SET 
				`ORDER_LIMIT`='".$this->orderLimit."',
				`REP_LIMIT`='".$this->repLimit."'
			WHERE ID = " . $id;
		
			if(!checkingQuery($connectBD, $q)) {
				return false;	
			}else {
				return $id;					
		}
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
	
	public function zonesByUser($id = null) {
		global $connectBD;
		
		$q = "select ".preBD."zone.*
			 from ".preBD."zone
			 inner join ".preBD."user_web_zone_assoc on ".preBD."user_web_zone_assoc.IDZONE = ".preBD."zone.ID and ".preBD."user_web_zone_assoc.IDUSER = " . $id . "
			 where true and ".preBD."zone.STATUS = '1'";
		
		$res = checkingQuery($connectBD, $q);
		$data = array();
		while($row = mysqli_fetch_object($res)) {	
			$data[] = $row;
		}
		return $data;
	}
	public function zonesByUserDisponibles($sup = null, $idUser = null) {
		global $connectBD;
		
		$q = "select ".preBD."zone.*
			 from ".preBD."zone
			 inner join ".preBD."user_web_zone_assoc on ".preBD."user_web_zone_assoc.IDZONE = ".preBD."zone.ID and ".preBD."user_web_zone_assoc.IDUSER = " . $idUser . "
			 where true and ".preBD."zone.STATUS = '1'";
		
		$res = checkingQuery($connectBD, $q);
		$data = array();
		while($row = mysqli_fetch_object($res)) {	
			$q2 = "select * from ".preBD."user_sup_web_address where IDASSOC = ".$sup." and IDZONE = " . $row->ID;
			$res2 = checkingQuery($connectBD, $q2);
			if(!$row2 = mysqli_fetch_object($res2)) {		
				$data[] = $row;
			}
		}
		return $data;
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
	public function isUserWebZone($id = null, $user = null) {
		global $connectBD;
		if($user->IDTYPE == 5){
			$q = "select ".preBD."user_web_zone_assoc.IDUSER
					from ".preBD."user_web_zone_assoc 
					where ".preBD."user_web_zone_assoc.IDZONE = " . $id. " and IDUSER = ".$user->ID;
			$r = checkingQuery($connectBD, $q);
			$total = mysqli_num_rows($r);
		}else {
			$total = 0;
		}

		if($total > 0) {
			return true;
		}else{
			return false;
		}
	}
	public function listSupplierZone($id = null) {
		global $connectBD;
		
		$q = "select DISTINCT ".preBD."suppliers.*, 
				".preBD."url_web.SLUG 
				from ".preBD."suppliers 
				inner join ".preBD."url_web on ".preBD."url_web.ID_VIEW = ".preBD."suppliers.ID and ".preBD."url_web.TYPE = 'supplier'
				inner join ".preBD."user_sup_web_address 
				on ".preBD."user_sup_web_address.IDASSOC = ".preBD."suppliers.ID and ".preBD."user_sup_web_address.TYPE = 'proveedor'
				where true 
				and ".preBD."user_sup_web_address.IDZONE = " . $id . "
				order by ".preBD."suppliers.TITLE asc";
			
		$r = checkingQuery($connectBD, $q);
		
		$sup = array();
		while($data = mysqli_fetch_object($r)) {
			$sup[] = $data;
		}
		return $sup;
	}
}