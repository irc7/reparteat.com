<?php
	$userObj = new UserWeb();
	$zoneObj = new Zone();
	if(isset($_GET["tpl"]) && trim($_GET["tpl"]) == "profile") {
		
		$user = $_SESSION[nameSessionZP];
			
		require("template/modules/user/profile.tpl.php");
	}else if(isset($_GET["tpl"]) && trim($_GET["tpl"]) == "address") {
		$zones = array(); 
		$zones = $zoneObj->listZones();
		
		$redirect = $_SERVER['HTTP_REFERER'];

		require("template/modules/user/address.tpl.php");
	}
	
?>		
	