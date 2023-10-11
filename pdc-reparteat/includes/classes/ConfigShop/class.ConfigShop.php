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


class ConfigShop extends System {
	
	protected $id;
	protected $title;
	protected $value;
	protected $text;
	protected $auxiliary;
	
	
	public function __construct() {
	}
	
	
	public function listParams() {
		global $connectBD;
		
		$q = "select * from ".preBD."configuration where true and ID = 20 or ID = 21 or ID = 22";
		$res = checkingQuery($connectBD, $q);
		
		$data = array();
		while($row = mysqli_fetch_object($res)) {
			$data[] = $row;
		} 
		return $data;
			
	}
	
	public function update(){
		global $connectBD;
		$q = "UPDATE `".preBD."configuration` SET 
				`VALUE`='".$this->value."'
			WHERE ID = " . $this->id;
		
			if(!checkingQuery($connectBD, $q)) {
				return false;	
			}else {
				return true;					
		}
	}
	
}