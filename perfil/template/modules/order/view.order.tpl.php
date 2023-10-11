
          <!-- Page Heading -->
<div class="container">
	<div class="step-order">
	
		<div class="row">
			<div class="col-sm-6 col-xs-12">
				<h1 class="h3 mb-2 text-gray-800">
					Pedido REFERENCIA: <?php echo $order->REF; ?>
				</h1>
			</div>
			<?php $statusOrder = $orderObj->infoStatusOrder($order->STATUS); ?>
			<div class="col-sm-6 col-xs-12 <?php echo $statusOrder->COLOR; ?>">
				<h5 class="textRight">
					<?php echo $statusOrder->TITLE; ?>  <i class="fa fa-<?php echo $statusOrder->ICON . " " . $statusOrder->COLOR; ?>"></i>
				</h5>
			</div>
			<div class="col-sm-6 col-xs-12 date-order">
				<p class="grayNormal">
			<?php
				$date = new DateTime($order->DATE_CREATE);
				echo "Pedido realizado el " . $date->format("d-m-Y") . " a las " . $date->format("H:i");
			?>
				</p>
			</div>
			<div class="col-sm-6 col-xs-12 date-order textRight">
				<p class="grayNormal">
				<?php
					if($_SESSION[nameSessionZP]->IDTYPE == 2) {
						if($order->STATUS < 6) {
							echo $orderObj->franjaSendSupplier($order);
						}
					}else{
						if($order->STATUS < 6) {
							echo $orderObj->franjaSend($order);
						//	echo "<br/>";
						//	echo $orderObj->hourSend($order);
						}
					}
				?>
				</p>
			</div>
		</div>
		<div id="action-order" class="action-order">
			<?php if($orderObj->checkViewOrderZone($_SESSION[nameSessionZP], $order) && $_SESSION[nameSessionZP]->IDTYPE == 5) { ?>
				<div class="col-sm-6 col-xs-12">
					<?php require("template/modules/order/action.order.tpl.php"); ?>
				</div>
				<div class="col-sm-6 col-xs-12">
					<?php require("template/modules/order/changerep.order.tpl.php"); ?>
				</div>
			<?php }elseif($_SESSION[nameSessionZP]->IDTYPE == 3 && $_SESSION[nameSessionZP]->ID == $order->IDREPARTIDOR && ($order->STATUS == 3 || $order->STATUS == 4)) { ?>
				<div class="col-sm-6 col-xs-12"></div>
				<div class="col-sm-6 col-xs-12">
					<?php $refSend = $order->REF;
					require("template/modules/order/changerep.order.tpl.php"); ?>
				</div>
			<?php }else{ 
					require("template/modules/order/action.order.tpl.php");  
				} 
			?>
		</div>
		
		<?php if($_SESSION[nameSessionZP]->IDTYPE == 2) { ?>
		<div class="separator20"></div>
		<div class="row">
			<div class="col-sm-12 col-xs-12">
				<div class="box-address-order">
					Teléfono cliente: <a href="tel:<?php echo str_replace(" ", "", $userOrder->PHONE); ?>"><?php echo $userOrder->PHONE; ?></a>
				</div>
			</div>
			<?php if($repOrder) { ?>
			<div class="col-sm-12 col-xs-12">
				<div class="box-address-order">
					Repartidor asignado: <strong><?php echo $repOrder->NAME . " " . $repOrder->SURNAME; ?>(<a href="tel:<?php echo str_replace(" ", "", $repOrder->PHONE); ?>"><?php echo $repOrder->PHONE; ?></a>)
				</div>
			</div>
			<div class="separator20"></div>
			<div class="separator20"></div>
			<?php } ?>
		</div>
		<?php }else { ?>
		<div class="separator20"></div>
		<div class="separator1 bgYellow"></div>
		<div class="separator15"></div>
		<div class="row">
			<div class="col-sm-6 col-xs-12">
				<h4 class="arial green">Dirección del resturante</h4>
				<div class="box-address-order">
					<?php $addressSup = $supObj->supplierAddress($supplierCart->ID); 
						echo $addressSup->STREET."<br/>".$addressSup->CITY."<br/>".$addressSup->CP." - ". $addressSup->PROVINCE;
					?>
				</div>
			</div>
			<div class="col-sm-6 col-xs-12">
				<h4 class="arial green">Dirección de entrega</h4>
				<div class="box-address-order">
					<?php $addressSend = $orderObj->orderAddress($order->IDADDRESS); 
						echo $addressSend->STREET."<br/>".$addressSend->CITY."<br/>".$addressSend->CP." - ". $addressSend->PROVINCE;
						echo "<br/>";
					?>
				</div>
			</div>
			<div class="separator20"></div>
		</div>
		<?php } ?>
		<div class="separator1 bgYellow"></div>
		<div class="separator15"></div>
		<h4 class="arial green">Resumen del pedido</h4>
		<div class="separator5"></div>
		<div class="separator1 bgYellow"></div>
		<div class="separator15"></div>
		
<?php
		
		$subTotalOrder = 0;
		foreach($products as $item) {
			$product = $proObj->infoProductByIdNoStatus($item->IDPRODUCT);
			$icons = $proObj->productIcon($item->IDPRODUCT);
			$comps = $item->IDCOM;
			$compsArray = explode("#-#", $comps);
		
			
			$subTotalOrder = $subTotalOrder + $item->COST;
?>
			<div id="item-order-<?php echo $cont; ?>" class="order-item">
				<div class="col-xs-1 order-item-ud">
					<h4 class="textBoxBold grayStrong"><?php echo $item->UDS; ?></h4>
				</div>
				<div class="col-xs-7 no-padding">
					<div class="order-item-name arial">
						<h5 class="textBoxBold grayStrong">
						<?php echo $product->TITLE;
							for($i=0;$i<count($compsArray);$i++) {
								if($compsArray[$i]>0) {
									$com = $proObj->productComsByIdCom($compsArray[$i]);
									echo " <em>+ ".$com->TITLE."</em>";
								}
							}
							?>
						</h5>
					</div>
					<div class="order-item-icon">
						<ul class="list-product-icon">
						<?php foreach($icons as $icon) { ?>
							<li><img class="img-responsive" src="<?php echo DOMAIN; ?>files/product/icon/1-<?php echo $icon->ICON; ?>" title="<?php echo $icon->TITLE; ?>" /></li>
						<?php } ?>
						</ul>
					</div>
				</div>
				<div class="col-xs-3 no-padding cart-item-cost textRight">
					<h4 class="textBoxBold grayStrong"><?php echo $item->COST . " €"; ?></h4>
				</div>
				<div class="col-xs-1 order-item-ud">
				<?php //borrar producto
					if(count($products) > 1 && $order->IDMETHODPAY == 1 && ($order->STATUS == 2 || $order->STATUS == 3) && ($orderObj->checkingProveedorOrder($_SESSION[nameSessionZP]->ID, $order->IDSUPPLIER) || $orderObj->checkViewOrderZone($_SESSION[nameSessionZP], $order))){ ?>
					<button type="button" id="btn-delete-product-order-<?php echo $item->ID; ?>" class="btn btn-primary floatRight transition btn-delete-product-order"><i class="fas fa-trash icon" title="Eliminar producto"></i></button>
					<div id="msg-delete-product-order-<?php echo $item->ID; ?>" class="msg-alert msg-alert-order">
						<div class="container">
							<div class="row">
								<div class="wrap-msg-alert text-left">
									<button id="btn-close-delete-order-<?php echo $item->ID; ?>" class="btn-close-delete-product-order floatRight"><i class="fa fa-times grayStrong"></i></button>
									<div class="separator5"></div>
									<h4 class="green">Eliminar pruducto</h4>
									<div class="separator5"></div>
									<form method="post" action="<?php echo DOMAINZP; ?>editar-pedido" name="delete-product-<?php echo $item->ID; ?>" id="delete-product-<?php echo $item->ID; ?>">
										<input type="hidden" name="action" value="deleteProduct" />
										<input type="hidden" name="ref" value="<?php echo $refSend; ?>" />
										<input type="hidden" name="idOrder" value="<?php echo $order->ID; ?>" />
										<input type="hidden" name="idProductOrder" value="<?php echo $item->ID; ?>" />
										<div class="col-xs-12">
											<h6 class="textLeft">
												<i class="fa fa-exclamation-triangle orange iconSmall"></i>&nbsp;
												<em class="orange">
													Va a eliminar el pruducto <em><?php echo $product->TITLE; ?></em> y modificar el pedido. ¿desea continuar?
												</em>
											</h6>
										</div>
										<div class="separator10"></div>
										<div class="col-xs-12">
											<button id="btn-delete-product-<?php echo $item->ID; ?>" type="submit" class="btn btn-primary floatRight transition btn-send-order" disabled>CONTINUAR</button>
										</div>
										<div class="separator5"></div>
									</form>
								</div>	
							</div>	
						</div>	
					</div>	
				<?php } 
					//fin liminar producto
				?>	
				</div>
			</div>
			<div class="separator10"></div>
<?php		} 
			require('template/modules/order/addproduct.tpl.php');
?>
		
		<div class="separator10"></div>
		<div class="separator1 bgGrayStrong"></div>
		<div class="separator5"></div>
		<div class="cart-order-total">
			<div class="col-xs-4 no-padding">
				<h5 class="grayStrong textBox">Subtotal</h5>
			</div>
			<div class="col-xs-7 textRight grayStrong textBox no-padding">
				<?php echo $order->SUBTOTAL . " €"; ?>
			</div>
			<div class="col-xs-2 no-padding"></div>
		<div class="separator5"></div>
			<div class="col-xs-4 no-padding">
				<h5 class="grayStrong textBox">Gastos de envio</h5>
			</div>
			<div class="col-xs-7 textRight grayStrong textBox no-padding">
				<?php echo $order->SHIPPING . " €"; ?>
			</div>
		<?php if($order->DISCOUNT > 0) { ?>
		<div class="separator5"></div>
			<div class="col-xs-4 no-padding">
				<h5 class="grayStrong textBox">Descuento</h5>
			</div>
			<div class="col-xs-7 textRight grayStrong textBox no-padding">
				- <?php echo $order->DISCOUNT . " €"; ?>
			</div>
		<?php } ?>
		<div class="separator5"></div>
		<div class="separator1 bgGrayStrong"></div>
		<div class="separator5"></div>
			<div class="col-xs-4 no-padding">
				<h4 class="grayStrong textBoxBold">TOTAL</h4>
			</div>
			<div class="col-xs-7 textRight grayStrong textBoxBold no-padding">
				<h4 class="grayStrong textBoxBold">
				<?php 
					echo $order->COST . " €"; 
				?>
				</h4>
			</div>
		</div>
	</div>

	<div class="separator20"></div>
	<div id="box-order-comment">
		<div class="col-xs-12 no-padding">
			<h4 class="arial green">Observaciones</h4>
			<div class="separator5"></div>
			<div class="separator1 bgYellow"></div>
			<div class="separator5"></div>
		</div>
		<div class="col-xs-12 grayStrong textBoxBold no-padding">
			<h5 class="textBox grayStrong">
			<?php 
				if(trim($order->COMMENT) != "") {
					echo $order->COMMENT; 
				}else{
					echo "<em>Sin observaciones</em>";
				}
			?>
			</h5>
		</div>
	</div>
	<div class="separator50"></div>
	<div id="box-order-comment">
		<div class="col-xs-12 no-padding">
			<h4 class="arial green">Método de pago</h4>
			<div class="separator5"></div>
			<div class="separator1 bgYellow"></div>
			<div class="separator5"></div>
		</div>
		<div class="col-xs-12 grayStrong textBoxBold no-padding">
			<h5 class="textBox grayStrong"><?php echo $methodPay->TITLE; ?></h5>
		<?php if($methodPay->ID == 2 || $methodPay->ID == 3) { 
				$regTPV = $orderObj->infoTpvResponse($order->REF);	
				if($regTPV["tpv"] != "") {
					if($regTPV["tpv"]->RESPONSE < 100 || $regTPV["tpv"]->RESPONSE == 400 || $regTPV["tpv"]->RESPONSE == 900) {
					?>
							<h5 class="textBox green">Pago realizado correctamente</h5>
				<?php 
						if($order->STATUS >= 8 && $order->STATUS <= 12 && $_SESSION[nameSessionZP]->ID == $order->IDUSER && $userOrder->SALDO >= $order->COST) {
							$nextStatus = 13;
							$infoStatus = $orderObj->infoStatusOrder($nextStatus);
							$btnText = "Solicitar devolución";
							$text1 = "Va a solicitar la devolución del pedido.";
							$text2 = "Al solicitar la devolución se le restará el importe del pedido a su saldo de cliente.";
							$inputView = false;
							$label = "";
							$inputTitle = "";
							$valueInput = 0;
							include("template/modules/order/btn.return.tpl.php");
						}
					}else{ ?>
							<h5 class="textBox error">ERROR EN EL PAGO: <?php echo $regTPV["tpv"]->RESPONSE.".-".$regTPV["error"]; ?></h5>
		<?php 		} 
				}else if($regTPV["tpv"] == "" && $order->STATUS == 15){ ?>
						<h5 class="textBox error">ERROR EN EL PAGO: No ha completado el pago en el TPV Virtual. </h5>
		<?php 	}
			} ?>
		</div>
	</div>
	<div class="separator20"></div>

	
</div>

       

