<?php
	$zObj = new Zone();
	if(isset($_GET["z"]) && intval($_GET["z"]) > 0 && $zObj->isUserWebZone(intval($_GET["z"]), $_SESSION[nameSessionZP])) {
		
		$idZone = intval($_GET["z"]);
		$zone = $zObj->infoZone($idZone);
		
		$supObj = new Supplier();
		$proObj = new Product();
		
		if(isset($_GET["tpl"]) && trim($_GET["tpl"]) == "supplier") {
			$list = $zObj->listSupplierZone($idZone);
			
			require("template/modules/zone/supplier.tpl.php");
		}else if(isset($_GET["tpl"]) && trim($_GET["tpl"]) == "config"){
				require("template/modules/zone/config.tpl.php");
		}else{
			require("template/modules/error.tpl.php");
		}
	}else{
		require("template/modules/error.tpl.php");
	}
	
?>		
	