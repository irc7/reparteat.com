<?php if (allowed ($mnu)){ 

	require_once("includes/classes/Image/class.Image.php");
	require_once("includes/classes/Order/class.Order.php");
	require_once("includes/classes/UserWeb/class.UserWeb.php");
	require_once("includes/classes/Supplier/class.Supplier.php");
	require_once("includes/classes/Product/class.Product.php");
	require_once("includes/classes/Supplier/class.Supplier.php");
	
	if(isset($_GET["ref"]) && intval($_GET["ref"]) > 0) {
		$ref = $_GET["ref"];	
	}else {
		$location = "index.php?mnu=".$mnu."&com=".$com."&tpl=option&opt=".$opt."&msg=".utf8_decode("Usuario desconocido");
?>
		<script type="text/javascript">
			window.location.href = "<?php echo $location; ?>";
		</script>
<?php
	}
	
	$supObj = new Supplier();
	$proObj = new Product();
	$orderObj = new Order();
	$userObj = new UserWeb();
	$now = new DateTime();
	
	$order = $orderObj->infoOrderByRef($ref);
	$address = $orderObj->orderAddress($order->IDADDRESS);
			$methodPay = $orderObj->orderMethodPay($order->IDMETHODPAY);
			
			
			$supplierCart = $supObj->infoSupplierById($order->IDSUPPLIER);
			
			
			$products = $orderObj->listProductOrder($order->ID);
?>	
	<script src="<?php echo DOMAIN; ?>pdc-reparteat/js/checking.validation.js"></script>
	<script src="<?php echo DOMAIN; ?>pdc-reparteat/js/class.Validation.js"></script>
	<div class='cp_mnu_title title_header_mod'>Pedido: <?php echo $order->REF; ?></div>
	<div class="separator20"></div>
<?php 
	if (isset($_GET['msg'])) {
		$msg = utf8_encode($_GET['msg']);
		echo "<div class='container container-admin'><div class='cp_info'><img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' /><p>".$msg."</p></div></div>";
	}
?>	
<!-- Page Heading -->

	<div class="step-order" style="padding:30px;">
		<div class="row">
			<div class="col-xs-6">
				<?php if($order->FROM_APP == 1) { ?>
					<h4 class="yellow"><i class="fa fa-mobile yellow iconBotton" title="Pedido realizado desde la APP" style="cursor:help;"></i>  Pedido realizado desde la APP</h4>
				<?php }else { ?>
					<h4 class="green"><i class="fa fa-globe green pointer iconBotton" title="Pedido realizado desde la WEB" style="cursor:help;"></i>  Pedido realizado desde la WEB</h4>
				<?php } ?>
			</div>
			<div class="col-xs-6 <?php echo $statusOrder->COLOR; ?>">
				<div>
					<?php $statusOrder = $orderObj->infoStatusOrder($order->STATUS); ?>
					<h4 class="<?php echo $statusOrder->COLOR; ?> textRight">
						<i class="fa fa-<?php echo $statusOrder->ICON . " " . $statusOrder->COLOR; ?>"></i>  <?php echo $statusOrder->TITLE; ?>  
					</h4>
				</div>
			</div>
			<div class="col-xs-12">
				<div class="separator20"></div>
				<div class="white darkshaded" style="padding:15px;">
					<form method="post" action="modules/order/change_status.php" id="changeStatus">
						<input type="hidden" name="mnu" value="<?php echo $mnu; ?>" />
						<input type="hidden" name="com" value="<?php echo $com; ?>" />
						<input type="hidden" name="tpl" value="<?php echo $tpl; ?>" />
						<input type="hidden" name="opt" value="<?php echo $opt; ?>" />
						<input type="hidden" name="ref" value="<?php echo $order->REF; ?>" />
						<input type="hidden" name="id" value="<?php echo $order->ID; ?>" />
						<input type="hidden" name="statusold" value="<?php echo $order->STATUS; ?>" />
						<div class="form-group white">
							<label class="label-field white" for="status">Cambiar Estado:</label>
							<?php $statusList = $orderObj->listStatusOrder(); ?>
							<select class="form-control form-s" name="status" id="status" title="Estado">
								<option value='0' selected="selected">Selecciona el estado deseado</option>
								<?php foreach($statusList as $st) { 
										if($st->ID != $statusOrder->ID){ 
								?>
								<option value='<?php echo $st->ID ?>' class="<?php echo $st->COLOR; ?>"><?php echo $st->TITLE ?></option>
								<?php 	}
									} ?>
							</select>
							<button type="button" id="btn-change-status" class="btn tf-btn btn-default transition floatRight bgGreen white bold">Modificar estado</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="separator20"></div>
		<div class="row">
			<div class="col-xs-12">
				<h1 class="h3 mb-2 text-gray-800">
					Pedido REFERENCIA: <?php echo $order->REF; ?>
				</h1>
			</div>
			<div class="separator"></div>
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
					if($order->STATUS < 6) {
						echo $orderObj->hourSend($order);
					}
				?>
				</p>
			</div>
		</div>
		
		<div class="separator20"></div>
		<div class="separator1 bgYellow"></div>
		<div class="separator15"></div>
		<?php //if($_SESSION[nameSessionZP]->IDTYPE != 2) { ?>
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
					?>
				</div>
			</div>
			<div class="separator20"></div>
		</div>
		<?php //} ?>
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
					if(count($products) > 1){
				?>
						<i id="btn-delete-product-<?php echo $item->ID; ?>" class="fa fa-trash icon floatRight btn-delete-product grayStrong" title="Eliminar producto"></i>
						<div id="msg-delete-product-<?php echo $item->ID; ?>" class="msg-alert msg-alert-order">
							<div class="container">
								<div class="row">
									<div class="wrap-msg-alert text-left">
										<button id="btn-close-delete-<?php echo $item->ID; ?>" class="btn-close-delete-product floatRight"><i class="fa fa-times grayStrong"></i></button>
										<div class="separator5"></div>
										<h4 class="green">Eliminar pruducto</h4>
										<div class="separator5"></div>
										<form method="post" action="modules/order/edit_order.php" name="deleteProduct" id="deleteProduct-<?php echo $item->ID; ?>">
											<input type="hidden" name="mnu" value="<?php echo $mnu; ?>" />
											<input type="hidden" name="tpl" value="<?php echo $tpl; ?>" />
											<input type="hidden" name="opt" value="<?php echo $opt; ?>" />
											<input type="hidden" name="action" value="deleteProduct" />
											<input type="hidden" name="ref" value="<?php echo $order->REF; ?>" />
											<input type="hidden" name="idOrder" value="<?php echo $order->ID; ?>" />
											<input type="hidden" name="idProductOrder" value="<?php echo $item->ID; ?>" />
											
											<div class="col-xs-12">
												<h4 class="textLeft orange">
													<i class="fa fa-exclamation-triangle orange iconSmall"></i>&nbsp;
													Va a eliminar el pruducto <em><?php echo $product->TITLE; ?></em> y modificar el pedido. ¿desea continuar?
												</h4>
											</div>
											<div class="separator10"></div>
											<div class="col-xs-12">
												<button id="btn-delete-product-order<?php echo $item->ID; ?>" type="submit" class="btn tf-btn btn-default transition floatRight bgGreen white bold btn-send-order" disabled>CONTINUAR</button>
											</div>
											<div class="separator10"></div>
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
			require('components/order/addproduct.tpl.php');
?>
		
		<div class="separator5"></div>
		<div class="separator1 bgGrayStrong"></div>
		<div class="separator5"></div>
		<div class="cart-order-total">
			<div class="col-xs-4 no-padding">
				<h5 class="grayStrong textBox">Subtotal</h5>
			</div>
			<div class="col-xs-8 textRight grayStrong textBox no-padding">
				<?php echo $order->SUBTOTAL . " €"; ?>
			</div>
			<div class="col-xs-2 no-padding"></div>
		<div class="separator5"></div>
			<div class="col-xs-4 no-padding">
				<h5 class="grayStrong textBox">Gastos de envio</h5>
			</div>
			<div class="col-xs-8 textRight grayStrong textBox no-padding">
				<?php echo $order->SHIPPING . " €"; ?>
			</div>
		<div class="separator5"></div>
		<div class="separator1 bgGrayStrong"></div>
		<div class="separator5"></div>
			<div class="col-xs-4 no-padding">
				<h4 class="grayStrong textBoxBold">TOTAL</h4>
			</div>
			<div class="col-xs-8 textRight grayStrong textBoxBold no-padding">
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
		</div>
		<?php if($methodPay->ID == 2) { 
				$regTPV = $orderObj->infoTpvResponse($order->REF);	
				if($regTPV["tpv"]->RESPONSE < 100 || $regTPV["tpv"]->RESPONSE == 400 || $regTPV["tpv"]->RESPONSE == 900) {
		?>
					<h5 class="textBox green">Pago realizado correctamente</h5>
			<?php }else{ ?>
					<h5 class="textBox error">ERROR EN EL PAGO: <?php echo $regTPV["tpv"]->RESPONSE.".-".$regTPV["error"]; ?></h5>
			<?php } ?>
		<?php } ?>

	</div>
	<div class="separator50"></div>
	<?php 
		$reg = $orderObj->infoRegModByRef($order->REF); 
		if(count($reg)>0) {
	?>
		<div id="box-order-comment">
			<div class="col-xs-12 no-padding">
				<h4 class="arial green">Registro de modificaciones</h4>
				<div class="separator5"></div>
				<div class="separator1 bgYellow"></div>
				<div class="separator5"></div>
			</div>
			<div class="col-xs-12 grayStrong textBoxBold no-padding">
				<?php 
					foreach($reg as $item){
				?>
						<div class="content-reg <?php if($item->ACTION == "Borrar"){echo "bgOrange white";}else{echo "grayStrong";} ?>">
							<div class="col-xs-2 textLeft textBox <?php if($item->ACTION == "Borrar"){echo "white";}else{echo "grayStrong";} ?>">
								<?php $dateReg = new DateTime($item->DATE_CREATE); 
									echo $dateReg->format("d-m-Y H:i");
									?>
							</div>
							<div class="col-xs-4 textLeft textBox no-padding <?php if($item->ACTION == "Borrar"){echo "white";}else{echo "grayStrong";} ?>">
								<?php 
									$userReg = $orderObj->infoUserRegMod($item->IDUSER, $item->TABLE_USER); 
									echo $userReg["name"]." - (".$userReg["type"].")";
								?>
							</div>
							<div class="col-xs-4 textLeft textBox no-padding <?php if($item->ACTION == "Borrar"){echo "white";}else{echo "grayStrong";} ?>">
								<?php echo $item->TITLE; ?>
							</div>
							<div class="col-xs-1 textLeft textBox no-padding <?php if($item->ACTION == "Borrar"){echo "white";}else{echo "grayStrong";} ?>">
								<?php echo $item->COST; ?>
							</div>
							<div class="col-xs-1 textLeft textBox no-padding <?php if($item->ACTION == "Borrar"){echo "white";}else{echo "grayStrong";} ?>">
								<?php echo $item->ACTION; ?>
							</div>
							<div class="separator1"></div>
						</div>
						<div class="separator1 bgGrayLight"></div>
						
			<?php 	} ?>
			</div>
		</div>
<?php 	} ?>
	<div class="separator20"></div>

	
<?php 
}else{
	echo "<p>No tiene permiso para acceder a esta sección.</p>";
}?>	