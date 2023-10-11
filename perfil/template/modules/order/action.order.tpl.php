<?php 
		$refSend = $order->REF;
		
		if($orderObj->checkViewOrderZone($_SESSION[nameSessionZP], $order) && $_SESSION[nameSessionZP]->IDTYPE == 5) {
			$btnText = "Cambiar estado";
			$text1 = "Va a cambiar el estado del pedido";
			$text2 = "";
			$label = "Pasar pedido a ";
			$statusOrder = $orderObj->infoStatusOrder($order->STATUS);
			$statusList = $orderObj->listStatusOrder();
			include("template/modules/order/formstatus.tpl.php");
		}else if($orderObj->checkingProveedorOrder($_SESSION[nameSessionZP]->ID, $order->IDSUPPLIER) && $order->STATUS == 2) {
			$btnText = "Aceptar pedido";
			$text1 = "Va a aceptar el pedido";
			$text2 = "<i class='fas fas-alert orange'></i><strong>".$orderObj->franjaSendSupplier($order)."</strong>";
			$inputView = false;
			//$label = "Tiempo de preparación";
			//$valueInput = $supplierCart->TIME;
			//$inputTitle = "Tiempo de preparación";
			$nextStatus = 3;
			include("template/modules/order/btn.accept.tpl.php");
			$statusNext = 8;
			include("template/modules/order/btn.cancel.tpl.php");
		}else if($orderObj->checkingProveedorOrder($_SESSION[nameSessionZP]->ID, $order->IDSUPPLIER) && $order->STATUS == 3) {
			$nextStatus = 4;
			$infoStatus = $orderObj->infoStatusOrder($nextStatus);
			$btnText = "Pasar a " . $infoStatus->TITLE;
			$text1 = "Va a pasar el pedido a " . $infoStatus->TITLE;
			$text2 = "";
			$inputView = false;
			$label = "";
			$inputTitle = "";
			$valueInput = 0;
			include("template/modules/order/btn.accept.tpl.php");
			
		}else if($_SESSION[nameSessionZP]->ID == $order->IDREPARTIDOR && $order->STATUS == 4) {
			$nextStatus = 5;
			$infoStatus = $orderObj->infoStatusOrder($nextStatus);
			$btnText = "Pasar a " . $infoStatus->TITLE;
			$text1 = "Va a pasar el pedido a " . $infoStatus->TITLE;
			$text2 = "Observaciones del pedido:<br/><em>" .$order->COMMENT."</em>";
			$inputView = false;
			$label = "";
			$inputTitle = "";
			$valueInput = 0;
			include("template/modules/order/btn.accept.tpl.php");
			
			$statusNext = 10;
			include("template/modules/order/btn.cancel.tpl.php");
		}else if($_SESSION[nameSessionZP]->ID == $order->IDREPARTIDOR && $order->STATUS == 5) {
			$nextStatus = 6;
			$infoStatus = $orderObj->infoStatusOrder($nextStatus);
			
			$btnText = "Pasar a " . $infoStatus->TITLE;
			$text1 = "Va a pasar el pedido a " . $infoStatus->TITLE;
			$text2 = "Observaciones del pedido:<br/><em>" .$order->COMMENT."</em>";
			$inputView = false;
			$label = "";
			$inputTitle = "";
			include("template/modules/order/btn.accept.tpl.php");
			
			$statusNext = 10;
			include("template/modules/order/btn.cancel.tpl.php");
		}
	?>	