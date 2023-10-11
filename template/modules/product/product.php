<?php	
	//Consulta al Proveedor
		
	$proObj = new Product();
	$supObj = new Supplier();
	$supView = $supObj->infoSupplierById($section);
	$catSup = $supObj->infoCategories($section);
	$timeControl = $supObj->supplierTimeControl($id);
	
	$proView = $proObj->infoProductById($id);
	$catPro = $proObj->infoCategories($id);
	$images = $proObj->productImages($id);
	$coms = $proObj->productComs($id);
	$icons = $proObj->productIcon($id);
	
	require_once ("template/modules/product/template.product.php");
		
	
?>