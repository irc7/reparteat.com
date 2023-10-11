
          <!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Resumen de pedidos dia <?php echo $dateCheck->format("d/m/Y"); ?></h1>
<p class="mb-4"></p>
<div class='container bgGrayStrong'>
	<div class="separator10"></div>
	<form id="form-filter-day" name='dropdown' method='get' action='index.php'>
		<input type='hidden' name='view' value='<?php echo $view; ?>' />
		<input type='hidden' name='mod' value='<?php echo $mod; ?>' />
		<input type='hidden' name='tpl' value='<?php echo $tpl; ?>' />
		<input type='hidden' name='z' value='<?php echo $idZone; ?>' />
		<div class="form-group">
			<div class="col-md-2 col-sm-4 col-xs-12">
				<label class="label-field white" for="filter">Selecciona fecha:</label>
			</div>
			<div class="col-md-10 col-sm-8 col-xs-12">
				<input type="date" class="form-control form-s" value="<?php echo $filterstring; ?>" name="filter" id="filter" />
			</div>
			<div class="separator10"></div>
		</div>
	</form>
</div>
<div class="separator50"></div>
<div class="container">
	<div class='row'>	
<?php 
		if(count($orders) > 0) {
			$cont = 0;
			//pre($orders);
			for($i=0;$i<count($orders);$i++) {
				$product = array();
?>
				<div class="wrap-data-sumary">	
					<div class='flex-title title'>Restaurante: <?php echo $orders[$i]["data"]->TITLE; ?> 
						<span class="floatRight title">Total pedidos: <?php echo count($orders[$i]["order"]); ?></span>
					</div>
					<div class="wrap-flex flex-header">
					<!--	<div class="item-flex title c-2">Referencia</div> -->
						<div class="item-flex title c-2"></div>
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
						<div class="wrap-flex flex-list<?php if($j == count($orders[$i]["order"])-1){echo " last";} ?>" style="display:none !important;">
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
					<div class="wrap-flex flex-header" style="display:none !important;">
						<div class="item-flex title c-1">ID</div>
						<div class="item-flex title c-2">Nombre</div>
						<div class="item-flex title c-3">Uds</div>
						<div class="item-flex title c-4">Cost</div>
					</div>
<?php				
					$product = orderArrayByCamp($product, "uds");
					foreach($product as $p) { 
?>
						<div class="wrap-flex flex-list" style="display:none !important;">
							<div class="item-flex textBox c-1" style="width:10%;"><?php echo $p["id"]; ?></div>
							<div class="item-flex textBox c-2" style="width:50%;"><?php echo $p["title"]; ?></div>
							<div class="item-flex textBox c-3" style="width:20%;"><?php echo $p["uds"]; ?></div>
							<div class="item-flex textBox c-4" style="width:20%;"><?php echo $p["cost"]; ?></div>
						</div>
<?php 				} ?>					
					
				</div>
				<div class="separator20"></div>
<?php			
			}
		}else {
?>
			<div class='container'>
				<div class='textCenter'>
					<i class="fa fa-info-circle green iconBig"></i>
					<div class="separator10"></div>
					<h5 class="textBox green">No hay pedidos registrados para la fecha <?php echo $dateCheck->format("d/m/Y");; ?></h5>
				</div>
			</div>
<?php		
		}
	
?>				
	</div>
</div>
<div class="separator50"></div>

       

