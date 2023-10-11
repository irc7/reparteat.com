<?php
/*
Author: info@ismaelrc.es
Date: 2022-10-04

Usuario
*/


if(strpos($_SERVER["SCRIPT_NAME"], "modules")) {
	require_once "../../includes/classes/class.System.php";
}else {
	require_once "includes/classes/class.System.php";
}


class PublicityHook extends System {
	
	protected $id;
	protected $title;
	protected $width;
	protected $width_m;
	protected $height;
	protected $height_m;
	protected $pause;
	protected $speed;
	
	
	public function __construct() {
	}
	
	public function listHook() {
		global $connectBD;
		$q = "SELECT * FROM ".preBD."publicity_hook where true";
		$r = checkingQuery($connectBD, $q);
		
		$cats = array();
		while($row = mysqli_fetch_object($r)) {
			$cats[] = $row;
		}
		return $cats;
	}
	
	public function totalHook() {
		global $connectBD;
		$q = "SELECT * FROM ".preBD."publicity_hook where true";
		$r = checkingQuery($connectBD, $q);
		
		$total = mysqli_num_rows($r);
		
		return $total;
	}
	
	public function infoHookById($id = null) {
		global $connectBD;
		
		$q = "select * from ".preBD."publicity_hook where true and ID = '" . $id . "'";
		$res = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($res)) {
			return $data;
		} else {
			return false;
		}
	}
	/*
	public function add(){
		global $connectBD;
		$q = "INSERT INTO ".preBD."publicity_hook (TITLE, TEXT) 
				VALUES 
			('".$this->title."', '".$this->text."')";
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
		$q = "UPDATE `".preBD."publicity_hook` SET
				`TITLE`='".$this->title. "', 
				`TEXT`='".$this->text . "'
			WHERE ID = " . $id;
		if(!checkingQuery($connectBD, $q)) {
			return false;	
		}else {
			return true;					
		}
	}*/
}