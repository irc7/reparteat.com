<?php
/*
Author: info@ismaelrc.es
Date: 2019-08-26

Usuario
*/

class Report extends System {
	
	protected $id;
	protected $idRep;
	protected $name;
	protected $dateCreate;
	protected $day;
	protected $orderDay;
	protected $night;
	protected $orderNight;
	protected $salaryDay;
	protected $salaryNight;
	protected $payCash;
	protected $payTPV;
	protected $cost;
	protected $text;
	protected $total;
	
	
	public function __construct() {
	}
	
	
	public function add() {
		global $connectBD;
		$q = "INSERT INTO `".preBD."report`(`IDREP`, `NAME`, `DATECREATE`, `DAY`, `ORDERDAY`, `NIGHT`, `ORDERNIGHT`, `SALARYDAY`, `SALARYNIGHT`, `PAYCASH`, `PAYTPV`, `COST`, `TEXT`, `TOTAL`) 
				VALUES 
			('".$this->idRep."',
			'".$this->name."',
			'".$this->dateCreate->format('Y-m-d h:s:i')."',
			'".$this->day."',
			'".$this->orderDay."',
			'".$this->night."',
			'".$this->orderNight."',
			'".number_format($this->salaryDay,2,'.','')."',
			'".number_format($this->salaryNight,2,'.','')."',
			'".number_format($this->payCash,2,'.','')."',
			'".number_format($this->payTPV,2,'.','')."',
			'".number_format($this->cost,2,'.','')."',
			'".mysqli_real_escape_string($connectBD, $this->text)."',
			'".number_format($this->total,2,'.','')."')";
		
		if(checkingQuery($connectBD, $q)) {
			$idNew = mysqli_insert_id($connectBD);
			$this->id = $idNew;
			return $idNew;
		} else {
			return false;
		}
	}
	public function update() {
		global $connectBD;
		$q = "UPDATE `".preBD."report` SET 
			`IDREP` = '".$this->idRep."',
			`NAME` = '".$this->name."',
			`DATECREATE` = '".$this->dateCreate->format('Y-m-d h:s:i')."',
			`DAY` = '".$this->day."',
			`ORDERDAY` = '".$this->orderDay."',
			`NIGHT` = '".$this->night."',
			`ORDERNIGHT` = '".$this->orderNight."',
			`SALARYDAY` = '".number_format($this->salaryDay,2,'.','')."',
			`SALARYNIGHT` = '".number_format($this->salaryNight,2,'.','')."',
			`PAYCASH` = '".number_format($this->payCash,2,'.','')."',
			`PAYTPV` = '".number_format($this->payTPV,2,'.','')."',
			`COST` = '".number_format($this->cost,2,'.','')."',
			`TEXT` = '".mysqli_real_escape_string($connectBD, $this->text)."',
			`TOTAL` = '".number_format($this->total,2,'.','')."'
			WHERE ID = " . $this->id;
		
		if(checkingQuery($connectBD, $q)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function checkingReportUser($date, $idUser) {
		global $connectBD;
				
		$aux = explode("-", $date);
		$dateq = " and YEAR(DATECREATE) = " . $aux[0];
		$dateq .= " and MONTH(DATECREATE) = " . $aux[1];
		$dateq .= " and DAY(DATECREATE) = " . $aux[2];
		
		$q = "select * from ".preBD."report where true and IDREP = " . $idUser . $dateq;
		$r = checkingQuery($connectBD, $q);
		
		$data = mysqli_fetch_object($r);
			
		return $data;

	}
	
}