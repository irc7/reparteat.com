<?php
	function isSpeciality($ids, $idc) {
		global $connectBD;
		
		$q = "select * from ".preBD."cm_cs where IDCENTER = " . $idc . " and IDSPECIALITY = " . $ids;
		$r = checkingQuery($connectBD, $q);
		if($row = mysqli_fetch_object($r)) {
			return true;
		}else{
			return false;
		}
		
	}
	function isSpecialityDoctor($ids, $idd) {
		global $connectBD;
		
		$q = "select * from ".preBD."cm_ds where IDDOCTOR = " . $idd . " and IDSPECIALITY = " . $ids;
		$r = checkingQuery($connectBD, $q);
		if($row = mysqli_fetch_object($r)) {
			return true;
		}else{
			return false;
		}
		
	}
	
	function isCenterDoctor($idc, $idd) {
		global $connectBD;
		
		$q = "select * from ".preBD."cm_dc where IDDOCTOR = " . $idd . " and IDCENTER = " . $idc;
		$r = checkingQuery($connectBD, $q);
		if($row = mysqli_fetch_object($r)) {
			return true;
		}else{
			return false;
		}
		
	}
	
	
	
?>