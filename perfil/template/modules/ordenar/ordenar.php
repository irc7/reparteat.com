<?php
	
	$proObj = new Product();
	$supObj = new Supplier();

	if(isset($_GET["sup"]) && intval($_GET["sup"]) > 0 && $supObj->isUserWebSupplier(intval($_GET["sup"]), $_SESSION[nameSessionZP])) {
		$idSup = intval($_GET["sup"]);
		$supBD = $supObj->infoSupplierById($idSup);
		 
		
		if(isset($_GET["tpl"]) && trim($_GET["tpl"]) == "product") {
			$list = $proObj->listProductBySupplierAllPosition($idSup);
			require("template/modules/ordenar/ord.product.tpl.php");

		}else {
			require("template/modules/error.tpl.php");
		}
	}else{
		require("template/modules/error.tpl.php");
	}
	
?>		
	