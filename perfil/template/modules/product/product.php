<?php
	require_once("../includes/class/Product/class.Component.php");
	
	$proObj = new Product();
	$catProObj = new CategoryPro();
	
	$supObj = new Supplier();

	if(isset($_GET["sup"]) && intval($_GET["sup"]) > 0 && $supObj->isUserWebSupplier(intval($_GET["sup"]), $_SESSION[nameSessionZP])) {
	
		$categories = array(); 
		$categories = $catProObj->allCategories(); 
		
		$idSup = intval($_GET["sup"]);
		$supBD = $supObj->infoSupplierById($idSup);
		
		$comObj = new Component();
		$coms = array(); 
		$coms = $comObj->allComponent(); 
		
		if(isset($_GET["tpl"]) && trim($_GET["tpl"]) == "list") {
			$list = $proObj->listProductBySupplierAllPosition($idSup);
			require("template/modules/product/list.tpl.php");

		}else if(isset($_GET["tpl"]) && trim($_GET["tpl"]) == "create") {
			
			$dateNow = new DateTime();
			require("template/modules/product/create.tpl.php");

		}else {
			if(isset($_GET["id"]) && intval($_GET["id"]) > 0){
				$id = intval($_GET["id"]);
				 
				$proObj = new Product();
				$product = $proObj->infoProductByIdNoStatus($id);
				$cats = $proObj->infoCategories($id);
				$comsPro = $proObj->productComponents($id);
				$imgs = $proObj->productImages($id);
				$datePro = new DateTime($product->DATE_START);
				
				require("template/modules/product/edit.tpl.php");
			}
		}
	}else{
		require("template/modules/error.tpl.php");
	}
	
?>		
	