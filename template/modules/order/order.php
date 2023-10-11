<?php	
//La session se comprueba en el index	
	$idCart = intval($_GET["supplier"]);
	
	$zoneObj = new Zone();
	$zoneInfo = $zoneObj->infoZone($_SESSION[sha1("zone")]);
	
	$userSaldoObj = new UserWeb();
	$saldoNew = $userSaldoObj->checkingSaldo($_SESSION[nameSessionZP]->ID);
	$_SESSION[nameSessionZP]->SALDO = $saldoNew;
	$ordObj = new Order();
	if(isset($_SESSION[nameCartReparteat][$idCart]) && count($_SESSION[nameCartReparteat][$idCart]["data"]) > 0) {
	
		
		$cartSupObj = new Supplier();
		$supplierCart = $cartSupObj->infoSupplierById($idCart);
		
		$addressSup = $cartSupObj->supplierAddress($supplierCart->ID); 
		
		$cartProObj = new Product();
	
		$usersRep = $cartSupObj->infoSupplierUserZone($idCart, 'repartidor',$zoneInfo->ID);
		
		$maxOrderZone = ($zoneInfo->ORDER_LIMIT * count($usersRep)) + $supplierCart->EXTRA_ORDER;
				
		$cartUserObj = new UserWeb();
		$user = $_SESSION[nameSessionZP];
		
		$address = $cartUserObj->userWebAddressZone($user->ID, $zoneInfo->ID);
		
		$timeSup = $cartSupObj->checkingOpen($supplierCart->ID,intval($_SESSION[sha1("zone")])); 
		if($timeSup["status"] == 1) {
			$classTimeSup = "green";
			$iconTimeSup = "unlock";
			$textTime = "Disponible hasta las " . $timeSup["time"]->FINISH_H .":";
			if(strlen($timeSup["time"]->FINISH_M) == 1) {
				$textTime .= "0";
			}
			$textTime .= $timeSup["time"]->FINISH_M;
		} else {
			if($timeSup["time"] == null) {
				$textTime = "No disponible";
				$classTimeSup = "danger";
				$iconTimeSup = "lock";
			}else {
				$classTimeSup = "orange";
				$iconTimeSup = "clock-o";
				$textTime = "Disponible a partir de " . $timeSup["time"]->START_H .":";
				if(strlen($timeSup["time"]->START_M) == 1) {
					$textTime .= "0";
				}
				$textTime .= $timeSup["time"]->START_M;
			}
		}

		$pointsObj = new Address();
		$points = $pointsObj->listPointsAddress($zoneInfo->ID);
		//pre($_SESSION[nameCartReparteat][$idCart]);
		$methodPay = $ordObj->listMethodPay();
		
		require("template/modules/order/sumary.order.php");
	}else {
		
		if(isset($_GET["ref"])) {
			$ref = intval($_GET["ref"]);
			
			$order = $ordObj->infoOrderByRef($ref);
			
			$address = $ordObj->orderAddress($order->IDADDRESS);
			$methodPay = $ordObj->orderMethodPay($order->IDMETHODPAY);
			
			$supObj = new Supplier();
			$supplierCart = $supObj->infoSupplierById($order->IDSUPPLIER);
		
			$proObj = new Product();
			
			$products = $ordObj->listProductOrder($order->ID);
			//pre($order);
			//pre($products);
			require("template/modules/order/confirm.order.php");
		}else{ 
			
		//retorno del tpv o bizum
			if(isset($_GET["act"])){
				$act = trim($_GET["act"]);
			} else {
				$act = "none";
			}
			
			$idSupplier = $idCart;
			$cartSupObj = new Supplier();
			$supplierCart = $cartSupObj->infoSupplierById($idSupplier);
			$urlSupplier = DOMAIN."restaurantes/" . $supplierCart->SLUG;
		//Retorno del TPV saco la información del pedido pendiente de pago(status 1) o error del TPV (status 7)
			$order = $ordObj->infoLastOrderByUser($_SESSION[nameSessionZP]->ID, $idCart, $_SESSION[sha1("zone")]);
			if($order && $act == "cancelar") {
				$nextstatus = 15;
				$ordObj->updateStatus($order->ID, $nextstatus, $order->STATUS);
				
				unset($_SESSION[nameCartReparteat][$idSupplier]);
?>
				<script type="text/javascript">
					 window.location.href = "<?php echo $urlSupplier; ?>";
				</script>
<?php
				
			}else if($order && $act != "cancelar") {
				$products = $ordObj->listProductOrder($order->ID);
				
			//elimino el pedido anterior
				//$ordObj->deleteOrder($order->ID);
				//no lo elimino para que se genere correctamente la referencia
				$nextstatus = 15;
				$ordObj->updateStatus($order->ID, $nextstatus, $order->STATUS);

			//creo el nuevo carrito
				$cartProObj = new Product();
				
				$timeSup = $cartSupObj->checkingOpen($idSupplier,intval($_SESSION[sha1("zone")])); 
				
				$carrito = array();
				$carrito["id"] = $supplierCart->ID;
				$carrito["min"] = $supplierCart->MIN;
				$carrito["status"] = $supplierCart->STATUS;
				$carrito["inTime"] = $timeSup["status"];
				$carrito["shipping"] = $supplierCart->COST+$zoneAct->SHIPPING;
				$carrito["data"] = array();
				
				$carrito["discount"] = 0;
				
				foreach($products as $pro) {
					$item = array();
					$comps = array();
					$item['compsArray'] = array();
					$item['comp'] = "";
					$costComp = 0;
					if(isset($pro->IDCOM) && trim($pro->IDCOM) != "") {
						
						$comps = explode("#-#",$pro->IDCOM);
						sort($comps,SORT_NUMERIC);
						$item['compsArray'] = $comps;
						for($c=0;$c<count($comps);$c++) {
							$com = $cartProObj->productComsByIdCom($comps[$c]);
							$item['comp'] .=  $com->TITLE;
							if($c >= 0 && $c < count($comps)-1){
								$item['comp'] .=  " + ";
							}
						}
					}
					$item['id'] = $pro->IDPRODUCT;
					
					$item['ud'] = $pro->UDS;
					
					$product = $cartProObj->infoProductByIdNoStatus($item["id"]);
					
					$item['title'] = $product->TITLE; 
					
					$item['cost'] = $pro->COST;
					
					$carrito["data"][] = $item;	
					
				}
				unset($_SESSION[nameCartReparteat][$idSupplier]);

				$_SESSION[nameCartReparteat][$idSupplier] = $carrito;
		
			//Vuelvo a cargar el resumen
				
				$addressSup = $cartSupObj->supplierAddress($supplierCart->ID); 
				$cartProObj = new Product();
			
				$usersRep = $cartSupObj->infoSupplierUserZone($idCart, 'repartidor',$zoneInfo->ID);
				
				$maxOrderZone = $zoneInfo->ORDER_LIMIT * count($usersRep);
						
				$cartUserObj = new UserWeb();
				$user = $_SESSION[nameSessionZP];
				
				$address = $cartUserObj->userWebAddressZone($user->ID, $zoneInfo->ID);
				
				if($timeSup["status"] == 1) {
					$classTimeSup = "green";
					$iconTimeSup = "unlock";
					$textTime = "Disponible hasta las " . $timeSup["time"]->FINISH_H .":";
					if(strlen($timeSup["time"]->FINISH_M) == 1) {
						$textTime .= "0";
					}
					$textTime .= $timeSup["time"]->FINISH_M;
				} else {
					if($timeSup["time"] == null) {
						$textTime = "No disponible";
						$classTimeSup = "danger";
						$iconTimeSup = "lock";
					}else {
						$classTimeSup = "orange";
						$iconTimeSup = "clock-o";
						$textTime = "Disponible a partir de " . $timeSup["time"]->START_H .":";
						if(strlen($timeSup["time"]->START_M) == 1) {
							$textTime .= "0";
						}
						$textTime .= $timeSup["time"]->START_M;
					}
				}

				$pointsObj = new Address();
				$points = $pointsObj->listPointsAddress($zoneInfo->ID);
				//pre($_SESSION[nameCartReparteat][$idCart]);
				$methodPay = $ordObj->listMethodPay();
				
				require("template/modules/order/sumary.order.php");
			}else{
				unset($_SESSION[nameCartReparteat][$idSupplier]);
?>
				<script type="text/javascript">
					 window.location.href = "<?php echo $urlSupplier; ?>";
				</script>
<?php
			}
		}
	}

?>