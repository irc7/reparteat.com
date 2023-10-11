
		<div class="list-cart">
			<h3 class="arial green">Resumen del carrito</h3>
			<div class="loading-cart">
				<img class="img-responsive" src="<?php echo DOMAIN; ?>template/images/loading.gif" />
			</div>
			<div id="box-list-cart">
	<?php 
		//		pre($idCart);
				$cartSupObj = new Supplier();
				$cartProObj = new Product();
				
				$supplierCart = $cartSupObj->infoSupplierById($idCart);
				
				$subtotalOrder = 0;
				
				
				foreach($_SESSION[nameCartReparteat][$idCart]["data"] as $cont => $item) {
					$product = $cartProObj->infoProductByIdNoStatus($item["id"]);
					$comps = $item["comp"]; 
					
					$subtotalOrder = $subtotalOrder + $item["cost"];
	?>
					<div id="item-cart-<?php echo $cont; ?>" class="cart-item">
						<div class="col-xs-3 no-padding cart-item-ud">
							<i id="cart-item-udless-<?php echo $idCart; ?>-<?php echo $cont; ?>" data-ud="<?php echo $item["ud"]-1; ?>" class="fa fa-minus yellow pointer cart-item-udless"></i>
							<span id="cart-item-ud-<?php echo $idCart; ?>-<?php echo $cont; ?>"><?php echo $item["ud"]; ?></span>
							<i id="cart-item-udmore-<?php echo $idCart; ?>-<?php echo $cont; ?>" data-ud="<?php echo $item["ud"]+1; ?>" class="fa fa-plus yellow pointer cart-item-udmore"></i>	
						</div>
						<div class="col-xs-6 cart-item-name ">
							<?php echo $item['title'];
								if($item['comp'] != "") {
									echo " <em>+ ".$item['comp']."</em>";
								}
							?>
						</div>
						<div class="col-xs-2 no-padding cart-item-cost textRight">
							<?php echo $item["cost"] . " €"; ?>
						</div>
						<div class="col-xs-1 no-padding cart-item-delete textRight">
							<i id="cart-item-delete-<?php echo $idCart; ?>-<?php echo $cont; ?>" class="fa fa-trash grayStrong pointer cart-item-delete"></i>
						</div>
					</div>
					<div class="separator1"></div>
	<?php		} ?>
				<div class="separator10"></div>
				<div class="cart-order-total">
					<div class="separator1 bgGrayStrong"></div>
					<div class="separator15"></div>
<?php /*
					<div class="col-xs-5 no-padding">
						<h5 class="grayStrong textBox">Subtotal</h5>
					</div>
					<div class="col-xs-6 textRight grayStrong textBox no-padding">
						<h5 class="grayStrong textBox">
							<?php echo number_format($subtotalOrder,2,".","") . " €"; ?>
						</h5>
					</div>
					<div class="col-xs-1 no-padding"></div>
				<div class="separator1"></div>
					<div class="col-xs-5 no-padding">
						<h5 class="grayStrong textBox">Gastos de envio</h5>
					</div>
					<div class="col-xs-6 textRight grayStrong textBox no-padding">
						<h5 class="grayStrong textBox">
							<?php 
								$shippingOrder = $supplierCart->COST + $zoneAct->SHIPPING; 
								echo number_format($shippingOrder,2,".","") . " €"; 
							?>
						</h5>
					</div>
					<div class="col-xs-1 no-padding"></div>
				<div class="separator5"></div>
				<div class="separator1 bgGrayStrong"></div>
				<div class="separator5"></div>
*/ ?>
					<div class="col-xs-5 no-padding">
						<h4 class="green textBoxBold total-cart-text">TOTAL</h4>
					</div>
					<div class="col-xs-6 textRight green textBoxBold total-cart-num no-padding">
						<h4 class="green textBoxBold total-cart-text">
							<?php echo number_format($subtotalOrder,2,".","") . " €"; ?>
						<?php /*$totalOrder = $subtotalOrder + $shippingOrder;
							echo number_format($totalOrder,2,".","") . " €"; */ ?>
						</h4>
					</div>
					<div class="col-xs-1 no-padding"></div>
				</div>
			</div>
			<?php 
				if($subtotalOrder < $supplierCart->MIN){ ?>
					<div class="separator15"></div>
					<div class="alert-order-min textRight">
						<i class="fa fa-exclamation-triangle"></i> Te quedan <?php echo $supplierCart->MIN - $subtotalOrder; ?>€ para completar el pedido mínimo
					</div>
			<?php
				} 
			?>
			<div class="separator15"></div>
			<div class="action-order textRight">
				<button id="btn-action-order-<?php echo $idCart; ?>" type="button" class="btn btn-action-order transition bgGreen yellow<?php if($timeSup["status"] == 1 && $subtotalOrder >= $supplierCart->MIN && $supplierCart->STATUS == 1){echo " active";}?>">
					Tramitar pedido
				</button>
			</div>
		</div>