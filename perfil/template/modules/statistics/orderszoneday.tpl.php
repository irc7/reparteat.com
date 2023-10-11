
          <!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Resumen de pedidos</h1>
<p class="mb-4"></p>
<div class='bgGrayStrong'>
	<div class="separator10"></div>
	<form id="form-filter-day" name='dropdown' method='get' action='index.php'>
		<input type='hidden' name='view' value='<?php echo $view; ?>' />
		<input type='hidden' name='mod' value='<?php echo $mod; ?>' />
		<input type='hidden' name='tpl' value='<?php echo $tpl; ?>' />
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
			$ind = 0;
			$rows = array();
			for($i=0;$i<count($orders);$i++) {
				$product = array();
				$tSubtotal = 0;
				$tShipping = 0;
				$tCost = 0;
				if(is_iterable($orders[$i]["order"])) {
					for($j=0;$j<count($orders[$i]["order"]);$j++) {
						$tSubtotal = $tSubtotal + $orders[$i]["order"][$j]["data"]->SUBTOTAL;
						$tShipping = $tShipping + $orders[$i]["order"][$j]["data"]->SHIPPING;
						$tCost = $tCost + $orders[$i]["order"][$j]["data"]->COST;
						if(is_iterable($orders[$i]["order"][$j]["product"])) {
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
					}
				}
				$rows[$ind]['name'] = $orders[$i]["data"]->TITLE;
				$rows[$ind]['tSubtotal'] = $tSubtotal;
				$rows[$ind]['tShipping'] = $tShipping;
				$rows[$ind]['tCost'] = $tCost;
				$rows[$ind]['tOrder'] = count($orders[$i]["order"]);
				$rows[$ind]['product'] = $product;
				$ind++;
			}

	?>
			<table class="table table-bordered" id="" width="100%" cellspacing="0">
				<thead>
					<tr>
					<?php if($idZone > 0) { ?>
						<th>ESTABLECIMIENTO</th>
					<?php }else{ ?>
						<th>#</th>
					<?php } ?>
						<th>PEDIDOS</th>
						<th>SUBTOTAL</th>
						<th>ENVIO</th>
						<th>TOTAL</th>
					</tr>
				</thead>
				<tbody>
				<?php 
					$totalDayOrder = 0;
					$totalDaySubtotal = 0;
					$totalDayShipping = 0;
					$totalDay = 0;
					foreach($rows as $item) { 
						if($idZone > 0) { 
					?>
							<tr>
								<td><?php echo $item['name']; ?></td>
								<td><?php echo $item['tOrder']; ?></td>
								<td><?php echo $item['tSubtotal']; ?></td>
								<td><?php echo $item['tShipping']; ?></td>
								<td><?php echo $item['tCost']; ?></td>
							</tr>
					<?php 
						} 
						$totalDayOrder += $item['tOrder'];
						$totalDaySubtotal += $item['tSubtotal'];
						$totalDayShipping += $item['tShipping'];
						$totalDay += $item['tCost'];
					} ?>
				</tbody>
				<tfoot>
					<tr>
						<th>TOTAL</th>
						<th><?php echo $totalDayOrder; ?></th>
						<th><?php echo $totalDaySubtotal; ?></th>
						<th><?php echo $totalDayShipping; ?></th>
						<th><?php echo $totalDay; ?></th>
					</tr>
				</tfoot>
			</table>
<?php
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

       

