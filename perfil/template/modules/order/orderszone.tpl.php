
          <!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Listado de pedidos</h1>
<p class="mb-4"></p>
<div class='bgGrayNormal'>
	<div class="separator20"></div>
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
			<div class="separator20"></div>
		</div>
	</form>
</div>
<div class="separator50"></div>
<!-- DataTales Example -->
<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary">Todos los pedidos</h6>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>REFERENCIA</th>
						<th>RESTAURANTE</th>
					<!--	<th>MÉTODO DE PAGO</th>-->
						<th>FECHA</th>
						<th>COSTE</th>
						<th>ESTADO</th>
						<th>DETALLES</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th class="sorting_desc">REFERENCIA</th>
					
						<th>RESTAURANTE</th>
					<!--	<th>MÉTODO DE PAGO</th>-->
						<th>FECHA</th>
						<th>COSTE</th>
						<th>ESTADO</th>
						<th>DETALLES</th>
					</tr>
				</tfoot>
				<tbody> 
				<?php if(is_array($orders)) {
						foreach($orders as $item) { 
							$supplier = $supObj->infoSupplierById($item->IDSUPPLIER);
							if($_SESSION[nameSessionZP]->IDTYPE == 2 && $tpl == "supplier" && isset($_GET["sup"]) && intval($_GET["sup"]) > 0) {
								$userOrder = $userObj->infoUserWebById($item->IDUSER);
								if($item->IDREPARTIDOR > 0) {
									$repOrder = $userObj->infoUserWebById($item->IDREPARTIDOR);
									$repartidor = "<span class='green'>".$repOrder->NAME ." ".$repOrder->SURNAME."</span>"; 
								}else {
										$repartidor = "<span class='orange'>Sin asignar</span>"; 
								}
								$name = $userOrder->NAME ." ".$userOrder->SURNAME; 
							}else{
								$name = $supplier->TITLE; 
							}
							$method = $orderObj->orderMethodPay($item->IDMETHODPAY);
							$date = new DateTime($item->DATE_CREATE);
					
				?>
							<tr>
								<td><?php echo $item->REF; ?></td>
						
								<td><?php echo $name; ?></td>
							
								<td><?php 
										echo $date->format("d-m-Y H:i:s"); 
										echo "<br/><em>Entrega:". $orderObj->franjaInfo($item)."</em>";
									?>
								</td>
								<td><?php echo $item->COST; ?> €</td>
								<td class="textCenter">
							
							<?php $statusOrder = $orderObj->infoStatusOrder($item->STATUS); ?>
									<i class="fa fa-<?php echo $statusOrder->ICON . " " . $statusOrder->COLOR; ?>" title="<?php echo $statusOrder->TITLE; ?>"></i>
									</br/>
									<span class="<?php echo $statusOrder->COLOR; ?>"><?php echo $statusOrder->TITLE; ?></span>
								</td>
								<td class="textCenter">
									<a href="<?php echo DOMAINZP; ?>?view=order&mod=order&z=<?php echo $idZone; ?>&ref=<?php echo $item->REF; ?>" >
										<i class="fa fa-edit"></i>
									</a>
								</td>
							</tr>
				<?php 	}
					}
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>

       

