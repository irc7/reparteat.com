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


class Component extends System {
	
	protected $id;
	protected $title;
	protected $idicon;
	protected $cost;
	
	
	public function __construct() {
	}
	
	public function listComponent($searchq = null, $recordq = null,$firstrecord = null,$recordsperpage = null) {
		global $connectBD;
		$q = "SELECT * FROM ".preBD."products_com where true " . $searchq . $recordq . " ORDER BY TITLE asc LIMIT " . $firstrecord . ", " . $recordsperpage;
		$r = checkingQuery($connectBD, $q);
		$total = mysqli_num_rows($r);
		
		$cats = array();
		while($row = mysqli_fetch_object($r)) {
			$cats[] = $row;
		}
		return $cats;
	}
	public function allComponent() {
		global $connectBD;
		$q = "SELECT * FROM ".preBD."products_com where true order by TITLE asc";
		$r = checkingQuery($connectBD, $q);
		
		$cats = array();
		while($row = mysqli_fetch_object($r)) {
			$cats[] = $row;
		}
		return $cats;
	}
	public function totalComponent() {
		global $connectBD;
		$q = "SELECT * FROM ".preBD."products_com where true";
		$r = checkingQuery($connectBD, $q);
		
		$total = mysqli_num_rows($r);
		
		return $total;
	}
	
	public function infoComponentById($id = null) {
		global $connectBD;
		
		$q = "select * from ".preBD."products_com where true and ID = '" . $id . "'";
		$res = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($res)) {
			return $data;
		} else {
			return false;
		}
	}
	public function add(){
		global $connectBD;
		$q = "INSERT INTO ".preBD."products_com (TITLE, COST) 
				VALUES 
			('".$this->title."', '".number_format($this->cost,2,'.','')."')";
		if(!checkingQuery($connectBD, $q)) {
			return false;	
		}else {
			$idNew = mysqli_insert_id($connectBD);
			$this->id = $idNew;
			if(count($this->idicon)>0) {
				$q = "INSERT INTO ".preBD."products_com_icon_assoc (IDCOM, IDICON) 
						VALUES ";
				for($i=0;$i<count($this->idicon);$i++) {
					$q.="('".$idNew."','".$this->idicon[$i]."')";
					if($i<count($this->idicon)-1) {
						$q.= ", ";
					}
				}
				checkingQuery($connectBD, $q);
			}
			return $idNew;					
		}
	}
	public function update($id = null){
		
		global $connectBD;
		$q = "UPDATE `".preBD."products_com` SET
				`TITLE`='".$this->title. "',
				`COST`='".number_format($this->cost,2,'.',''). "'
			WHERE ID = " . $id;
		if(!checkingQuery($connectBD, $q)) {
			return false;	
		}else {
			$q = "DELETE FROM `".preBD."products_com_icon_assoc` WHERE IDCOM = " . $id;
			checkingQuery($connectBD, $q);
			
			if(count($this->idicon)>0) {
				$q = "INSERT INTO ".preBD."products_com_icon_assoc (IDCOM, IDICON) 
					VALUES ";
				for($i=0;$i<count($this->idicon);$i++) {
					$q.="('".$id."','".$this->idicon[$i]."')";
					if($i<count($this->idicon)-1) {
						$q.= ", ";
					}
				}
				checkingQuery($connectBD, $q);
			}
			return $id;
		}
	}
	public function delete($id){
		global $connectBD;
		
		$q = "delete from ".preBD."products_com_icon_assoc where IDCOM = " . $id;
		$res = checkingQuery($connectBD, $q);
		
		$q = "delete from ".preBD."products_com_assoc where IDCOM = " . $id;
		$res = checkingQuery($connectBD, $q);
		
		$q = "delete from ".preBD."products_com where ID = " . $id;
		$res = checkingQuery($connectBD, $q);
		
		return $res;		
	}
}