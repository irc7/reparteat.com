<?php	
	
	if(isset($_SESSION[sha1("zone")]) && intval($_SESSION[sha1("zone")])>0){
		$zoneObj = new Zone();
		$zoneAct = $zoneObj->infoZone($_SESSION[sha1("zone")]);
		$ordObj = new Order();
		$orderPending = $ordObj->infoLastOrderByUser($_SESSION[nameSessionZP]->ID, $id, $_SESSION[sha1("zone")]);
	}else {
		$orderPending = false;
	}
	
	$supObj = new Supplier();
	$catsObj = new CategorySup();
	$timeObj = new TimeControl();
		
	if ($id != 0) {
		//Consulta al Proveedor
			$proObj = new Product();
			$catproObj = new CategoryPro();
		
			$supView = $supObj->infoSupplierById($id);
			$address = $supObj->supplierAddress($id);
			$catSup = $supObj->infoCategories($id);
			$timeControl = $supObj->supplierTimeControlZone($id,$_SESSION[sha1("zone")]);
			
			$catFilter = $catproObj->allCategoriesSupplier($id);
			
			$products = $proObj->listProductBySupplier($id);
		
			require_once ("template/modules/supplier/template.supplier.product.php");
			
	} else {
		//Consulta de restaurantes
		$search = "";
		if(isset($_SESSION[sha1("zone")]) && trim($_SESSION[sha1("zone")]) != "") {
			$search = intval($_SESSION[sha1("zone")]);
			$idZone = intval($_SESSION[sha1("zone")]);
		}
		$filterCat = "";
		if(isset($_GET["filterCat"])) {
			$filterCat = intval($_GET["filterCat"]);
		}
			 
		
			
		$resultByPages = $supObj->totalListSupplier($search, $filterCat);
		if(isset($_GET["filter"])) {
			$filter = trim($_GET["filter"]);
		}else{
			$filter = "datestart";
		}
		if (!isset($_GET["page"])){
			$page = 1;
		} else {
			$page = $_GET["page"];
		}
		$start = ($page - 1) * $resultByPages;
		$finish = $resultByPages;

		$suppliers = $supObj->listSupplier($search, $filterCat, $start, $finish);

		$supplierList = array();
		$supplierOpen = array();
		$supplierNext = array();
		$supplierClose = array();
		foreach($suppliers as $sup){ 
			$timeSup = $supObj->checkingOpen($sup->ID,$idZone);
			if($sup->STATUS == 1) {
				if($timeSup["status"] == 1) {
					$supplierOpen[] = $sup;
				} else {
					if($timeSup["time"] == null) {
						$supplierClose[] = $sup;	
					}else {
						$supplierNext[] = $sup;
					}
				}
			}else{
				$supplierClose[] = $sup;	
			}
		}
		
		foreach($supplierOpen as $sup){ 
			array_push($supplierList, $sup);
		}
		foreach($supplierNext as $sup){ 
			array_push($supplierList, $sup);
		}
		foreach($supplierClose as $sup){ 
			array_push($supplierList, $sup);
		}
		
		
		$catsAll = $catsObj->allCategories();
		$totalQuery = count($suppliers);
		if($resultByPages > 0) {
			$num_pages = ceil($totalQuery / $resultByPages);
		}else{
			$num_pages = 1;
		}	
		
		require_once ("template/modules/supplier/template.supplier.php");

	}
	
?>
