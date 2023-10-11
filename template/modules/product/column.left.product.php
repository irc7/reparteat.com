<?php require("template/modules/cart/cart.time.php"); ?>
<section id="column-left-supplier">
	
	<div class="separator15">&nbsp;</div>
	<div class="info-supplier-order">
<?php /*
		<div class="cost-supplier">
			<!--<i class="fa fa-money" aria-hidden="true"></i>-->
			<img class="img-responsive icon-list-supplier" src="<?php echo DOMAIN; ?>template/images/icon-money.png" />
			<span class="textBoxBold grayNormal">
				Gastos de envío: <?php echo $supView->COST; ?> &euro;
			</span>
		</div>
*/ ?>
		<div class="separator15">&nbsp;</div>
		<div class="min-supplier">
			<!--<i class="fa fa-motorcycle" aria-hidden="true"></i>-->
			<img class="img-responsive icon-list-supplier" src="<?php echo DOMAIN; ?>template/images/icon-flaw.png" />
			<span class="textBoxBold grayNormal">
				Pedido mínimo: <?php echo $supView->MIN; ?> &euro;
			</span>
		</div>
		<?php /*
		<div class="separator15">&nbsp;</div>
		<div class="min-supplier">
			<!--<i class="fa fa-clock-o" aria-hidden="true"></i>-->
			<img class="img-responsive icon-list-supplier" src="<?php echo DOMAIN; ?>template/images/icon-supplier.png" />
			<span class="textBoxBold grayNormal">
			Tiempo de entrega estimado: <?php echo $supView->TIME + timeRe; ?> min
			</span>
			</div>
			*/ ?>
	</div>
	<div class="separator15"></div>
	<div class="time-supplier textRight row bgWhite">
	<div class="separator15"></div>
		<h3 class="textBoxBold green no-margin">
			<?php echo $proView->COST; ?> €
		</h3>
	<div class="separator15"></div>
	</div>
	<?php /*
	<div class="separator15">&nbsp;</div>
	*/ ?>
	<form method="post" id="form-add-to-cart">
		<input type="hidden" id="idProduct-<?php echo $proView->ID; ?>" name="idProduct" value="<?php echo $proView->ID; ?>" />
		<input type="hidden" id="idSupplier-<?php echo $proView->ID; ?>" name="idSupplier" value="<?php echo $supView->ID; ?>" />
		<input type="hidden" class="form-control" name="totalPro" id="totalPro-<?php echo $proView->ID; ?>" value="1" />
		<input type="hidden" class="form-control" name="inTime" id="inTime-<?php echo $proView->ID; ?>" value="<?php if($timeSup["status"] == 1){echo "1";}else{echo "0";} ?>" />
		<h3>Ingredientes</h3>
		<ul>	
			<?php 
				for($i=0;$i<count($coms);$i++) { 
					if($coms[$i]->TYPE == "basic") {
						echo "<li>".$coms[$i]->TITLE."</li>";
					}
				}
			?>
		</ul>
		<?php 
			$cont = 0;
			for($i=0;$i<count($coms);$i++) { 
				if($coms[$i]->TYPE == "optional") {
					$cont++;
				}
			}
			if($cont > 0) {
		?>
				<h4>Extras</h4>
				<ul>	
					<?php 
						for($i=0;$i<count($coms);$i++) { 
							if($coms[$i]->TYPE == "optional") {
					?>
							<li>
								<input type="checkbox" class="addComCart-<?php echo $proView->ID; ?>" name="addCom[]" id="addCom-<?php echo $coms[$i]->ID; ?>" value="<?php echo $coms[$i]->ID; ?>"/>
								<input type="hidden" class="addComCart-<?php echo $proView->ID; ?>" name="CostCom-<?php echo $coms[$i]->ID; ?>" id="CostCom-<?php echo $coms[$i]->ID; ?>" value="<?php echo $coms[$i]->COST; ?>"/>
								<label for="addCom"><?php echo $coms[$i]->TITLE; ?></label>
								<?php if($coms[$i]->COST > 0){ ?>
									<label for="addCom" class="floatRight"><?php echo $coms[$i]->COST; ?> €</label>
								<?php } else { ?>
									<label for="addCom" class="floatRight">Gratis</label>
								<?php } ?>
								<div class="separator1 bgWhite"></div>
							</li>
					<?php		
							}
						} 
					?>
				</ul>
	<?php 	} ?>
		<div class="separator5">&nbsp;</div>
	<?php /*	
		<div class="add-cart-ud">
			<label for="totalPro" class="floatLeft">
				<h4>Cantidad</h4>
			</label>
			<input type="number" class="form-control form-xs floatRight" name="totalPro" id="totalPro" value="1" step="1" />
		</div>
		<div class="separator15">&nbsp;</div>
	*/ 
	if(((!isset($_SESSION[nameSessionZP]) && $supView->STATUS == 1 && $timeSup["status"] == 1) || (isset($_SESSION[nameSessionZP]) && $_SESSION[nameSessionZP]->IDTYPE == 4 && $supView->STATUS == 1 && $timeSup["status"] == 1)) && isset($_SESSION[sha1("zone")]) && intval($_SESSION[sha1("zone")]) > 0) {
	?>
	
		<div class="box-add-cart">
			<button type="button" id="add-to-cart-product-<?php echo $proView->ID; ?>" class="btn add-to-cart-product transition bgGreen yellow floatRight">
				Añadir al pedido&nbsp;&nbsp;<i class="fa fa-plus"></i>
			</button>
		</div>
<?php } ?>
		<div class="separator15">&nbsp;</div>
	</form>
	
	<div id="wrap-cart">
	<?php if(isset($_SESSION[nameCartReparteat][$section]) && count($_SESSION[nameCartReparteat][$section]["data"]) > 0){ ?>
		<?php require("template/modules/cart/cart.tpl.php"); ?>
	<?php } ?>
	</div>
	<div class="separator20"></div>
	<?php require("template/modules/cart/cart.time.tpl.php"); ?>
</section>

