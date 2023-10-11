<?php
/*
Author: info@ismaelrc.es
Date: 2022-10-04

Proveedor
*/

class Multislide extends System {
	
	protected $id;
	public $idhook;
	protected $title;
	protected $subtitle;
	protected $image;
	protected $image_mobile;
	protected $video;
	protected $type;
	protected $status;
	protected $position;
	
	
	public function __construct() {
		$this->hook = array();
		$this->zone = array();
	}
	
	public function infoMultislideById($id = null) {
		global $connectBD;
		
		$q = "select * from ".preBD."multislide where true and ID = '" . $id . "'";
		$r = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($r)) {
			return $data;
		} else {
			return false;
		}
	}
	
	public function multislideByHook($hook = null) {
		global $connectBD;
		$data = array();
		$q = "select * from ".preBD."multislide_hook where true and IDCHAR = '$hook'";
		$r = checkingQuery($connectBD, $q);
		$row = mysqli_fetch_object($r);
		$data["hook"] = $row;

		$q = "select ".preBD."multislide.*
				from ".preBD."multislide 
				where true and ".preBD."multislide.STATUS = 1
				and ".preBD."multislide.IDHOOK = '" . $data["hook"]->ID."'
				order by ".preBD."multislide.POSITION asc";

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
	public function allMultislide() {
		global $connectBD;
		
		$q = "select * from ".preBD."multislide where true";
		$r = checkingQuery($connectBD, $q);
		
		$sup = array();
		while($data = mysqli_fetch_object($r)) {
			$sup[] = $data;
		}
		return $sup;
	}
	
	public function lastPositionByHook($idhook = null) {
		global $connectBD;
		
		$q = "select count(*) as total
				from ".preBD."multislide
				where ".preBD."multislide.IDHOOK = " . $idhook;
		$r = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($r)) {
			return $data->total;
		} else {
			return false;
		}
	}
	public function infoPositionById($id) {
		global $connectBD;
		$q = "select POSITION from ".preBD."multislide where true and IDITEM = ". $id;
		$r = checkingQuery($connectBD, $q);
		$data = mysqli_fetch_object($r);

		return $data->POSITION;;
	}
	public function checkingHook($hook = null, $item) {
		global $connectBD;
		
		$q = "select count(*) as total
				from ".preBD."multislide
				where ".preBD."multislide.IDHOOK = " . $hook . " and ".preBD."multislide.ID = " . $item;
		$r = checkingQuery($connectBD, $q);
		
		$data = mysqli_fetch_object($r);
	
		if($data->total>0) {
			return true;
		} else {
			return false;
		}
	}
		
}