
          <!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Resumen de pedidos</h1>
<p class="mb-4"></p>
<div class='bgGrayStrong'>
	<div class="separator10"></div>
	<form id="form-filter-day" name='dropdown' method='get' action='index.php'>
		<input type='hidden' name='view' value='<?php echo $view; ?>' />
		<input type='hidden' name='mod' value='<?php echo $mod; ?>' />
		<input type='hidden' name='tpl' value='<?php echo $tpl; ?>' />
		<input type='hidden' name='filter' value='sumary' />
		<?php if($idZone > 0) { ?>
		<input type='hidden' name='z' value='<?php echo $idZone; ?>' />
		<?php } ?>
		<?php if($idSupplier > 0) { ?>
		<input type='hidden' name='sup' value='<?php echo $idSupplier; ?>' />
		<?php } ?>
		<div class="form-group">
			<div class="col-md-1 col-sm-1 col-xs-2">
				<label class="label-field white" for="filterstart">Desde:</label>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-10">
				<input type="date" class="form-control form-xl" value="<?php echo $filterstringStart; ?>" name="filterstart" id="filterstart" />
			</div>
			<div class="col-md-1 col-sm-1 col-xs-2">
				<label class="label-field white" for="filterfinish"> a </label>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-10">
				<input type="date" class="form-control form-xl" value="<?php echo $filterstringFinish; ?>" name="filterfinish" id="filterfinish" />
			</div>
			<div class="col-md-2 col-sm-2 col-xs-12">
				<button type="submit" class="btn btn-primary floatRight bgGreen yellow">Consultar</button>
			</div>
			<div class="separator10"></div>
		</div>
	</form>
</div>
<div class="separator50"></div>

<div class="card shadow mb-4">
	
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary"></h6>
	</div>
	<div class="card-body">
		<div class="table-responsive">
	<?php 
		//pre($orders);
		if(count($orders) > 0) {
			$cont = 0;
			//pre($orders);
			for($i=0;$i<count($orders);$i++) {
				$product = array();
?>
				<div class="wrap-data-sup">	
					<div class='flex-title title'>Restaurante: <?php echo $orders[$i]["data"]->TITLE; ?> 
						<span class="floatRight title">Total pedidos: <?php echo count($orders[$i]["order"]); ?></span>
					</div>
					<div class="wrap-flex flex-header">
						<div class="item-flex title c-2">Referencia</div>
						<div class="item-flex title c-1">Subtotal</div>
						<div class="item-flex title c-3">Envío</div>
						<div class="item-flex title c-4">Total</div>
					</div>
			<?php
					$tSubtotal = 0;
					$tShipping = 0;
					$tCost = 0;
					for($j=0;$j<count($orders[$i]["order"]);$j++) {
?>
						<div class="wrap-flex flex-list<?php if($j == count($orders[$i]["order"])-1){echo " last";} ?>">
							<div class="item-flex textBox c-2"><?php echo $orders[$i]["order"][$j]["data"]->REF; ?></div>
							<div class="item-flex textBox c-1"><?php echo $orders[$i]["order"][$j]["data"]->SUBTOTAL; ?></div>
							<div class="item-flex textBox c-3"><?php echo $orders[$i]["order"][$j]["data"]->SHIPPING; ?></div>
							<div class="item-flex textBox c-4"><?php echo $orders[$i]["order"][$j]["data"]->COST; ?></div>
						</div>
<?php
						$tSubtotal = $tSubtotal + $orders[$i]["order"][$j]["data"]->SUBTOTAL;
						$tShipping = $tShipping + $orders[$i]["order"][$j]["data"]->SHIPPING;
						$tCost = $tCost + $orders[$i]["order"][$j]["data"]->COST;
						for($z=0;$z<count($orders[$i]["order"][$j]["product"]);$z++) {
							$item = $orders[$i]["order"][$j]["product"][$z];
							$enc = false;
							for($x=0;$x<count($product);$x++) {
								if($product[$x]["id"] == $item["id"]) {
									$enc = true;
									break;
								}
							}
							if($enc) {
								$product[$x]["uds"] = $product[$x]["uds"] + $item["uds"];
								$product[$x]["cost"] = $product[$x]["cost"] + $item["cost"];
							}else {
								$product[] = $item;
							}
						}
					}
?>
					<div class="wrap-flex flex-footer">
						<div class="item-flex title c-2">TOTALES</div>
						<div class="item-flex title c-1"><?php echo $tSubtotal; ?> €</div>
						<div class="item-flex title c-3"><?php echo $tShipping; ?> €</div>
						<div class="item-flex title c-4"><?php echo $tCost; ?> €</div>
					</div>
					<div class="wrap-flex flex-header">
						<div class="item-flex title c-1">ID</div>
						<div class="item-flex title c-2">Nombre</div>
						<div class="item-flex title c-3">Uds</div>
						<div class="item-flex title c-4">Cost</div>
					</div>
<?php				
					$product = orderArrayByCamp($product, "uds");
					foreach($product as $p) { 
?>
						<div class="wrap-flex flex-list">
							<div class="item-flex textBox c-1" style="width:10%;"><?php echo $p["id"]; ?></div>
							<div class="item-flex textBox c-2" style="width:50%;"><?php echo $p["title"]; ?></div>
							<div class="item-flex textBox c-3" style="width:20%;"><?php echo $p["uds"]; ?></div>
							<div class="item-flex textBox c-4" style="width:20%;"><?php echo $p["cost"]; ?></div>
						</div>
<?php 				} ?>					
					
				</div>		
<?php
			}
		}else {
?>
			<div class='container'>
				<div class='textCenter'>
					<i class="fa fa-info-circle green iconBig"></i>
					<div class="separator10"></div>
					<h5 class="textBox green">No hay pedidos registrados para el rango de fecha seleccionado.</h5>
				</div>
			</div>
<?php	} ?>				
			
		</div>
	</div>
</div>


<div class="separator50"></div>

       

