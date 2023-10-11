<?php 
		$products = $proObj->listProductBySupplier($supplierCart->ID);
	//	pre($products);
		$refSend = $order->REF;
?>

	<button type="button" id="btn-add-product-order-<?php echo $refSend; ?>" class="btn tf-btn btn-default transition floatRight bgGreen white bold btn-add-product-order">Añadir producto</button>

	<div id="msg-add-product-order-<?php echo $refSend; ?>" class="msg-alert msg-alert-order">
		<div class="container">
			<div class="row">
				<div class="wrap-msg-alert text-left">
					<button id="btn-close-add-order" class="btn-close-alert-order floatRight"><i class="fa fa-times grayStrong"></i></button>
					<div class="separator5"></div>
					<h4 class="green">Añadir pruducto:</h4>
					<div class="separator5"></div>
					<form method="post" action="modules/order/edit_order.php" name="add-product" id="add-product">
						<input type="hidden" name="mnu" value="<?php echo $mnu; ?>" />
						<input type="hidden" name="tpl" value="<?php echo $tpl; ?>" />
						<input type="hidden" name="action" value="addProduct" />
						<input type="hidden" name="ref" value="<?php echo $refSend; ?>" />
						<input type="hidden" name="idOrder" value="<?php echo $order->ID; ?>" />
						<div class="form-group">
							<label for="uds" class="textLeft">Unidades:</label>
							<input class="form-control uds-add-product" type="number" name="uds" id="uds" value="1" step="1" />
							<p id="error-uds"></p>
						</div>
						<div class="separator20"></div>
						<div class="form-group">
							<label for="aux" class="textLeft">Selecciona un producto:</label>
							<select class="form-control select-add-product" name="idProduct" id="idProduct" required title="producto">
								<option value='0' selected="selected">Selecciona un producto</option>
							<?php foreach($products as $prod) { ?>
									<option value='<?php echo $prod->ID ?>'><?php echo $prod->TITLE . "(".$prod->COST." €)"; ?></option>
							<?php 	} ?>
							</select>
							<p id="error-idProduct"></p>
						</div>
						<div class="separator5"></div>
						<?php foreach($products as $prod) { 
								$comp = $proObj->productComponentsType($prod->ID, "optional");
								
								if(count($comp)>0){
								//listarlos en la caja que mostramos cuando se seleccione el producto en el select
						?>
									<div id="comp-aux-<?php echo $prod->ID; ?>" class="form-group comp-aux-product" style="display:none;">
										<h4>Extras</h4>
										<ul>	
											<?php 
												for($i=0;$i<count($comp);$i++) { 
													if($comp[$i]->TYPE == "optional") {
											?>
													<li>
														<input type="checkbox" class="comp-input-product comp-input-<?php echo $prod->ID; ?>" name="addCom-<?php echo $prod->ID; ?>[]" id="addCom-<?php echo $comp[$i]->ID; ?>" value="<?php echo $comp[$i]->ID; ?>" disabled />
														<label for="addCom-<?php echo $prod->ID; ?>"><?php echo $comp[$i]->TITLE; ?></label>
														<?php if($comp[$i]->COST > 0){ ?>
															<label for="addCom-<?php echo $prod->ID; ?>" class="floatRight"><?php echo $comp[$i]->COST; ?> €</label>
														<?php } else { ?>
															<label for="addCom-<?php echo $prod->ID; ?>" class="floatRight">Gratis</label>
														<?php } ?>
														<div class="separator1 bgWhite"></div>
													</li>
											<?php		
													}
												} 
											?>
										</ul>
									</div>
						<?php	} 	
							} ?>
						<div class="col-xs-12">
							<h6 class="textLeft">
								<i class="fa fa-exclamation-triangle orange iconSmall"></i>&nbsp;
								<em class="orange">
									Va a cambiar el pedido. ¿desea continuar?
								</em>
							</h6>
						</div>
						<div class="separator10"></div>
						<div class="col-xs-12">
							<button id="btn-add-product" type="submit" class="btn tf-btn btn-default transition floatRight bgGreen white bold btn-send-order" disabled>CONTINUAR</button>
						</div>
						<div class="separator5"></div>
					</form>
					<script type="text/javascript">
						var validation_options = {
							form: document.getElementById("add-product"),
							fields: [
								{
									id: "idProduct",
									type: "selectNumber",
									min: 1,
									max: 99999999
								}
							]
						};
						var v1 = new Validation(validation_options);
					</script>
				</div>
			</div>
		</div>
	</div>