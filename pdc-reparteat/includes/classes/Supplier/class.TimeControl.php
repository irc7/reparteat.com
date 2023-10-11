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


class TimeControl extends System {
	
	protected $id;
	protected $idassoc;
	protected $idzone;
	protected $type;
	protected $day;
	protected $start_h;
	protected $start_m;
	protected $finish_h;
	protected $finish_m;
	
	
	public function __construct() {
	}
	
	
	public function add(){
		global $connectBD;
		
		if($this->validateTime()) {
			$q = "INSERT INTO `".preBD."supplier_horario`(`IDASSOC`, `IDZONE`, `TYPE`, `DAY`, `START_H`, `START_M`, `FINISH_H`, `FINISH_M`) 
					VALUES 
				('".$this->idassoc."','".$this->idzone."','".$this->type."','".$this->day."','".$this->start_h."','".$this->start_m."','".$this->finish_h."','".$this->finish_m."')";
			if(!checkingQuery($connectBD, $q)) {
				return "Se ha producido un error al insertar en tiempo de reparto, vuelva a intentarlo si el problema persiste consulte con el administrador";	
			}else {
				return "";					
			}
		}else {
			return "Los datos proporcionados en los tiempo de control no son correctos";
		}
		
	}
	public function update($id = null, $idassoc = null){
		
		global $connectBD;
		$q = "UPDATE `".preBD."supplier_horario` SET
				`IDZONE`='".$this->idzone. "', 
				`DAY`='".$this->day. "', 
				`START_H`='".$this->start_h. "', 
				`START_M`='".$this->start_m. "', 
				`FINISH_H`='".$this->finish_h. "', 
				`FINISH_M`='".$this->finish_m . "'
			WHERE ID = " . $id . " and IDASSOC = " . $idassoc;
		if(!checkingQuery($connectBD, $q)) {
			return false;	
		}else {
			return "";					
		}
	}
	public function deleteTimeControl($id = null){
		global $connectBD;
		
		$q = "DELETE FROM `ree_supplier_horario` WHERE ID = " . $id;
		if(!checkingQuery($connectBD, $q)) {
			return false;	
		}else {
			return true;					
		}
	}
	public function validateTime() {
		$noerror = true;
		if($this->day < 0 || $this->day > 6) {
			$noerror = false;
		}
		if(intval($this->start_h) < 0 || intval($this->start_h) > 23) {
			$noerror = false;
		}
		if(intval($this->start_m) < 0 || intval($this->start_m) > 59) {
			$noerror = false;
		}
		if(intval($this->finish_h) < 0 || intval($this->finish_h) > 23) {
			$noerror = false;
		}
		if(intval($this->finish_m) < 0 || intval($this->finish_m) > 59) {
			$noerror = false;
		}
		return $noerror;
	}
}