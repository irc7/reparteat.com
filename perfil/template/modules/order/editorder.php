<?php
	session_start();
	header ('Content-type: text/html; charset=utf-8');
	$V_PHP = explode(".", phpversion());
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}
	require_once ("../../../../pdc-reparteat/includes/database.php");
	$connectBD = connectdb();
	require_once ("../../../../pdc-reparteat/includes/config.inc.php");
	require_once ("../head/strings.php");
	require_once ("../../../../includes/functions.inc.php");
	require_once ("../../../includes/functions.php");

	require_once ("../../../../lib/Util/class.Util.php");
	require_once ("../../../../lib/FileAccess/class.FileAccess.php");
	require_once("../../../../includes/class/class.phpmailer.php");
	require_once("../../../../includes/class/class.smtp.php");


	require_once "../../../../includes/class/class.System.php";
	require_once("../../../../includes/class/UserWeb/class.UserWeb.php");	
	require_once("../../../../includes/class/Supplier/class.Supplier.php");
	require_once("../../../../includes/class/Product/class.Product.php");	
	require_once("../../../../includes/class/Order/class.Order.php");	
	require_once("../../../../includes/class/TelegramBot/class.TelegramBot.php");	
		
	
	

	$result = 0;
	$msg = "";
	if($_POST) {
		$action = trim($_POST["action"]);
		$ref = intval($_POST["ref"]);
		$ordObj = new Order();
		$order = $ordObj->infoOrderByRef($ref);
		if($ordObj->checkingProveedorOrder($_SESSION[nameSessionZP]->ID, $order->IDSUPPLIER) || $ordObj->checkViewOrderZone($_SESSION[nameSessionZP], $order)){ 
			
			if($order->IDMETHODPAY == 1 && ($order->STATUS == 2 || $order->STATUS == 3) && $order) {
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
						$ordObj->insertRegMod("Añadir", $order->REF, $newId, $titleProduct, $costTotal, $_SESSION[nameSessionZP]->ID);
						
						$msg = "Pedido modificado correctamente.";
					}else{
						$result = 1;
						$msg = "Error al insertar el producto, vuelva a intentarlo, si el problema persiste, consulte con el administrador.";		
					}
				}else if($action == "deleteProduct"){
					$idProductOrder = intval($_POST["idProductOrder"]);
					$productOrder = $ordObj->infoProductOrderByIdAssoc($idProductOrder);
					
					if($ordObj->deleteProductByIdOrder($order->ID, $idProductOrder)) {

						$ordObj->updateTotalOrder($order);
						$ordObj->insertRegMod("Borrar", $order->REF, 0, $productOrder->TITLE, $productOrder->COST, $_SESSION[nameSessionZP]->ID);
						
						$msg = "Pedido modificado correctamente.";
					}else{
						$result = 1;
						$msg = "Error al borrar el producto, vuelva a intentarlo, si el problema persiste, consulte con el administrador.";		
					}
				}
			}else{
				$result = 1;
				$msg = "No se encuentra ningún pedido con esa referencia (<em>" . $ref. "</em>) , o el pedido no puede modificarse en su estado actual.";
			}
		}else{
			$result = 1;
			$msg = "No tiene permisos para realizar esta acción.";
		}
	}else{
		$result = 1;
		$msg = "Ha ocurrido un error inesperado, vuelva a intentarlo, si el error persiste consulte con el administrador.";
	}

	$_SESSION[msgError]["result"] = $result;
	$_SESSION[msgError]["msg"] = $msg;
	if($result == 1) {
		header("Location: " . DOMAINZP);
	}else {
		if(($next == 5 || $next == 6) && $order->IDREPARTIDOR == $_SESSION[nameSessionZP]->ID) {
			header("Location: " . DOMAINZP . "perfil/?view=order&mod=order&tpl=delivery&filter=to-deliver");
		}else {
			header("Location: " . DOMAINZP . "?view=order&mod=order&ref=" . $order->REF);
		}
	}

?>
