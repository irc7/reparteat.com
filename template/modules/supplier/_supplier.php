<?php	
	
	
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
			$timeControl = $supObj->supplierTimeControl($id);
			
			$catFilter = $catproObj->allCategoriesSupplier($id);
			
			$products = $proObj->listProductBySupplier($id);
			require_once ("template/modules/supplier/template.supplier.product.php");
			
	} else {
		//Consulta de restaurantes
		$search = "";
		if(isset($_SESSION[sha1("zone")]) && trim($_SESSION[sha1("zone")]) != "") {
			$search = intval($_SESSION[sha1("zone")]);
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
		$catsAll = $catsObj->allCategories();
		$totalQuery = count($suppliers);
		$num_pages = ceil($totalQuery / $resultByPages);
		
		//pre($suppliers);
		require_once ("template/modules/supplier/template.supplier.php");

	}
	
?>
