<?php	
	function getUserGroup($id) {//array de grupos a los que estaasociado el usuario
		global $connectBD;
		$q = "SELECT 
				".preBDzp."user_group_assoc.IDGROUP, 
				".preBDzp."user_group_assoc.IDGROUP, 
				".preBDzp."user_group_assoc.IDTYPEUSER, 
				".preBDzp."user_group.NAME
				FROM ".preBDzp."user_group_assoc 
				inner join ".preBDzp."user_group on ".preBDzp."user_group.ID = ".preBDzp."user_group_assoc.IDGROUP
				WHERE ".preBDzp."user_group_assoc.IDUSER = '" . $id . "' and ".preBDzp."user_group_assoc.STATUS = 1";
		
		$result = checkingQuery($connectBD, $q);
		$groups = array();
		while($row = mysqli_fetch_object($result)) {
			$groups[] = $row;
		}
		if(count($groups)>0) {
			return $groups;
		}else {
			return false;
		}
	}
	
	function totalGroupUser($id) {
		global $connectBD;
		$q = "SELECT count(*) as total
				FROM ".preBDzp."user_group_assoc 
				WHERE ".preBDzp."user_group_assoc.IDGROUP = '" . $id . "' and ".preBDzp."user_group_assoc.STATUS = 1";
		
		$result = checkingQuery($connectBD, $q);
		$row = mysqli_fetch_object($result);
		return $row->total;
	}
	
	function converMinusc($texto){
		$find = array('Á','É','Í','Ó','Ú','Ñ');
		$replac = array('á','é','í','ó','ú','ñ');
		$text = str_replace ($find, $replac, $texto); 
		$text = strtolower($text);
		return ($text);
	}
	function converMayusc($texto){
		$find = array('á','é','í','ó','ú','ñ');
		$replac = array('Á','É','Í','Ó','Ú','Ñ');
		$text = str_replace ($find, $replac, $texto); 
		$text = strtoupper($text);
		return ($text);
	}
?>