<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	$V_PHP = explode(".", phpversion());
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}
	
	require_once("../../includes/classes/Order/class.Order.php");
	require_once("../../includes/classes/Product/class.Product.php");	

	require_once("../../includes/classes/Supplier/class.Supplier.php");
	

	$result = 0;
	$msg = "";
	if($_POST) {
		$mnu = trim($_POST["mnu"]);
		$com = "order";
		$opt = trim($_POST["opt"]);
		if(!isset($_POST["mnu"]) || !allowed($_POST["mnu"])) { 	
			disconnectdb($connectBD);
			$msg = "No tiene permisos para realizar esta acción";
			$location = "Location: ../../index.php?msg=".utf8_decode($msg);
			header($location);
		} else {
			$action = trim($_POST["action"]);
			$ref = intval($_POST["ref"]);
			$ordObj = new Order();
			$order = $ordObj->infoOrderByRef($ref);
			if($order) {
				$supObj = new Supplier();
				$supplierCart = $supObj->infoSupplierById($order->IDSUPPLIER);
				$proObj = new Product();
				$typeProduct = "edit";
				if($action == "addProduct") {
				
					$uds = intval($_POST["uds"]);
					$idProduct = intval($_POST["idProduct"]);
					

					$product = $proObj->infoProductById($idProduct);
					$titleProduct = $product->TITLE;
					$cost = $product->COST;
					
					$comps = array();
					$stringPostCom = "addCom-".$idProduct;
					$idCom = "";
					if(isset($_POST[$stringPostCom]) && count($_POST[$stringPostCom]) > 0) {
						
						$comps = $_POST[$stringPostCom];
						sort($comps,SORT_NUMERIC);
						
						$costComps = 0;
						for($c=0;$c<count($comps);$c++) {
							$com = $proObj->infoProductCompByIdAssoc($comps[$c]);
							$cost = $cost + $com->COST;
							if($c >= 0 && $c < count($comps)){
								$titleProduct .=  " + ";
							}
							$titleProduct .=  $com->TITLE;
							$idCom .= $com->ID;
							if($c < count($comps)-1) {
								$idCom .= "#-#";
							}
						}
					}
					$costTotal = $cost * $uds;

					$newId = $ordObj->addProductByIdOrder($order->ID, $idProduct, $uds, $costTotal, $idCom, $typeProduct);
					if($newId != false && $newId > 0){
						$ordObj->updateTotalOrder($order);
						$ordObj->insertRegMod("Añadir", $order->REF, $newId, $titleProduct, $costTotal, $_SESSION[PDCLOG]["idUserLog"]);
						
						$msg = "Pedido modificado correctamente.";
					}else{
						$msg = "Error al insertar el producto, vuelva a intentarlo, si el problema persiste, consulte con el administrador.";		
					}
				}else if($action == "deleteProduct"){
					$idProductOrder = intval($_POST["idProductOrder"]);
					$productOrder = $ordObj->infoProductOrderByIdAssoc($idProductOrder);
					
					if($ordObj->deleteProductByIdOrder($order->ID, $idProductOrder)) {

						$ordObj->updateTotalOrder($order);
						$ordObj->insertRegMod("Borrar", $order->REF, 0, $productOrder->TITLE, $productOrder->COST, $_SESSION[PDCLOG]["idUserLog"]);
						
						$msg = "Pedido modificado correctamente.";
					}else{
						$msg = "Error al borrar el producto, vuelva a intentarlo, si el problema persiste, consulte con el administrador.";		
					}
				}
			}else{
				$msg = "No se encuentra ningún pedido con esa referencia (<em>" . $ref. "</em>)";
			}
		}
		$location = "Location: ../../index.php?mnu=".$mnu."&com=order&tpl=view&ref=".$ref."&msg=".utf8_decode($msg);
		header($location);

	}else{
		disconnectdb($connectBD);
		$msg = "Se ha producido un error, si el problema persiste, póngase en contacto con el administrador.";
		$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&opt=".$opt."&tpl=option&msg=".utf8_decode($msg);
		header($location);
	}

?>
