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


class Category extends System {
	
	protected $id;
	protected $title;
	protected $text;
	
	
	public function __construct() {
	}
	
	public function listCategory($searchq = null, $recordq = null,$firstrecord = null,$recordsperpage = null) {
		global $connectBD;
		$q = "SELECT * FROM ".preBD."products_cat where true " . $searchq . $recordq . " ORDER BY TITLE asc LIMIT " . $firstrecord . ", " . $recordsperpage;
		$r = checkingQuery($connectBD, $q);
		$total = mysqli_num_rows($r);
		
		$cats = array();
		while($row = mysqli_fetch_object($r)) {
			$cats[] = $row;
		}
		return $cats;
	}
	public function allCategories() {
		global $connectBD;
		$q = "SELECT * FROM ".preBD."products_cat where true";
		$r = checkingQuery($connectBD, $q);
		
		$cats = array();
		while($row = mysqli_fetch_object($r)) {
			$cats[] = $row;
		}
		return $cats;
	}
	public function totalCategory() {
		global $connectBD;
		$q = "SELECT * FROM ".preBD."products_cat where true";
		$r = checkingQuery($connectBD, $q);
		
		$total = mysqli_num_rows($r);
		
		return $total;
	}
	
	public function infoCategoryById($id = null) {
		global $connectBD;
		
		$q = "select * from ".preBD."products_cat where true and ID = '" . $id . "'";
		$res = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($res)) {
			return $data;
		} else {
			return false;
		}
	}
	public function add(){
		global $connectBD;
		$q = "INSERT INTO ".preBD."products_cat (TITLE, TEXT) 
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
		$q = "UPDATE `".preBD."products_cat` SET
				`TITLE`='".$this->title. "', 
				`TEXT`='".$this->text . "'
			WHERE ID = " . $id;
		if(!checkingQuery($connectBD, $q)) {
			return false;	
		}else {
			return true;					
		}
	}
	public function delete($id){
		global $connectBD;
		
		$q = "delete from ".preBD."products_cat_assoc where IDCAT = " . $id;
		$res = checkingQuery($connectBD, $q);
		
		$q = "delete from ".preBD."products_cat where ID = " . $id;
		$res = checkingQuery($connectBD, $q);
		
		return $res;		
	}
}