<?php
/*
Author: info@ismaelrc.es
Date: 2022-10-04

Proveedor
*/

class Publicity extends System {
	
	protected $id;
	public $hook;
	public $zone;
	protected $title;
	protected $subtitle;
	protected $text;
	protected $image;
	protected $image_mobile;
	protected $link;
	protected $target;
	protected $type;
	protected $status;
	
	
	public function __construct() {
		$this->hook = array();
		$this->zone = array();
	}
	
	public function infoPublicityById($id = null) {
		global $connectBD;
		
		$q = "select * from ".preBD."publicity where true and ID = '" . $id . "'";
		$r = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($r)) {
			return $data;
		} else {
			return false;
		}
	}
	
	public function publicityByHookZone($hook = null, $zone = null) {
		global $connectBD;
		$data = array();
		$q = "select * from ".preBD."publicity_hook where true and IDCHAR = '$hook'";
		$r = checkingQuery($connectBD, $q);
		$row = mysqli_fetch_object($r);
		$data["hook"] = $row;

		$q = "select ".preBD."publicity.*,
				".preBD."publicity_hook_zone.POSITION
				from ".preBD."publicity 
				inner join ".preBD."publicity_hook_zone
				on ".preBD."publicity_hook_zone.HOOK = '" . $data["hook"]->ID;
			if($zone > 0) {
		$q .= "' and ".preBD."publicity_hook_zone.IDZONE = '" . $zone;
			}
		$q .= "' and ".preBD."publicity_hook_zone.IDITEM = ".preBD."publicity.ID
				where true and ".preBD."publicity.STATUS = 1
				order by ".preBD."publicity_hook_zone.POSITION asc";

		$r = checkingQuery($connectBD, $q);
		$data['items'] = array();
		while($row = mysqli_fetch_object($r)){
			$data['items'][]= $row;
		}

		if(count($data['items']) > 0) {
			return $data;
		}else {
			return false;
		}
	}
	public function allPublicity() {
		global $connectBD;
		
		$q = "select * from ".preBD."publicity where true";
		$r = checkingQuery($connectBD, $q);
		
		$sup = array();
		while($data = mysqli_fetch_object($r)) {
			$sup[] = $data;
		}
		return $sup;
	}
	
	public function lastPositionByHook($idhook = null, $idzone = null) {
		global $connectBD;
		
		$q = "select count(*) as total
				from ".preBD."publicity_hook_zone 
				where ".preBD."publicity_hook_zone.HOOK = " . $idhook . " and ".preBD."publicity_hook_zone.IDZONE = " . $idzone;
		$r = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($r)) {
			return $data->total;
		} else {
			return false;
		}
	}
	public function infoPositionById($hook,$zone, $id) {
		global $connectBD;
		$q = "select POSITION from ".preBD."publicity_hook_zone where true
						and HOOK = " . 	$hook . "
						and IDZONE = ".	$zone . "
						and IDITEM = ". $id;
		$r = checkingQuery($connectBD, $q);
		$data = mysqli_fetch_object($r);

		return $data->POSITION;;
	}
	public function checkingZone($zone = null, $item = null) {
		global $connectBD;
		
		$q = "select count(*) as total
				from ".preBD."publicity_hook_zone 
				where ".preBD."publicity_hook_zone.IDZONE = " . $zone . " and ".preBD."publicity_hook_zone.IDITEM = " . $item;
		$r = checkingQuery($connectBD, $q);
		
		$data = mysqli_fetch_object($r);
		if($data->total>0) {
			return true;
		} else {
			return false;
		}
	}
	public function checkingHook($hook = null, $item) {
		global $connectBD;
		
		$q = "select count(*) as total
				from ".preBD."publicity_hook_zone 
				where ".preBD."publicity_hook_zone.HOOK = " . $hook . " and ".preBD."publicity_hook_zone.IDITEM = " . $item;
		$r = checkingQuery($connectBD, $q);
		
		$data = mysqli_fetch_object($r);
	
		if($data->total>0) {
			return true;
		} else {
			return false;
		}
	}
	public function checkingHookZone($hook = null, $zone = null, $item = null) {
		global $connectBD;
		
		$q = "select count(*) as total
				from ".preBD."publicity_hook_zone 
				where true
				and ".preBD."publicity_hook_zone.HOOK = " . $hook . " 
				and ".preBD."publicity_hook_zone.IDZONE = " . $zone . " 
				and ".preBD."publicity_hook_zone.IDITEM = " . $item;
		$r = checkingQuery($connectBD, $q);
		
		$data = mysqli_fetch_object($r);
	
		if($data->total>0) {
			return true;
		} else {
			return false;
		}
	}
	
	
}