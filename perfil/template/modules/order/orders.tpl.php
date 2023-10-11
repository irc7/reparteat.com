
          <!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Listado de pedidos</h1>
<p class="mb-4"></p>

<!-- DataTales Example -->
<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary">Todos los pedidos</h6>
	</div>
	
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" id="<?php if($tpl == "supplier" && trim($_GET["filter"]) == "pending"){echo "dataTableNoOrder";}else{echo "dataTable";} ?>" width="100%" cellspacing="0">
				<thead>
					<tr>
					<?php if($tpl == "supplier" && trim($_GET["filter"]) == "pending"){ ?>
						<th>#</th>
					<?php } ?>
						<th>REFERENCIA</th>
					<?php if($_SESSION[nameSessionZP]->IDTYPE == 2 && $tpl == "supplier" && isset($_GET["sup"]) && intval($_GET["sup"]) > 0) { ?>
						<!--<th>USUARIO</th>-->
						<th>ZONA DE REPARTO</th>
					<?php }else{ ?>
						<th>RESTAURANTE</th>
					<?php } ?>
					<!--	<th>MÉTODO DE PAGO</th>-->
						<th>FECHA</th>
						<th>COSTE</th>
						<th>ESTADO</th>
						<th>DETALLES</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
					<?php if($tpl == "supplier" && trim($_GET["filter"]) == "pending"){ ?>
						<th>#</th>
					<?php } ?>
						<th class="sorting_desc">REFERENCIA</th>
					<?php if($_SESSION[nameSessionZP]->IDTYPE == 2 && $tpl == "supplier" && isset($_GET["sup"]) && intval($_GET["sup"]) > 0) { ?>
						<!--<th>USUARIO</th>-->
						<th>ZONA DE REPARTO</th>
					<?php }else{ ?>
						<th>RESTAURANTE</th>
					<?php } ?>
					<!--	<th>MÉTODO DE PAGO</th>-->
						<th>FECHA</th>
						<th>COSTE</th>
						<th>ESTADO</th>
						<th>DETALLES</th>
					</tr>
				</tfoot>
				<tbody> 
				<?php if(is_array($orders)) {
						$cont = 1;
						foreach($orders as $item) { 
							$supplier = $supObj->infoSupplierById($item->IDSUPPLIER);
							$addressOrder = $orderObj->orderAddress($item->IDADDRESS);
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
							<?php if($tpl == "supplier" && trim($_GET["filter"]) == "pending"){ ?>
								<td><?php echo $cont; ?></td>
							<?php } ?>
								<td>
									<?php echo $item->REF; ?>
								</td>
							<?php if($_SESSION[nameSessionZP]->IDTYPE != 2) { ?>
								<td><?php echo $name; ?></td>
							<?php } ?>
							<?php if($_SESSION[nameSessionZP]->IDTYPE == 2 && $tpl == "supplier" && isset($_GET["sup"]) && intval($_GET["sup"]) > 0) { ?>
								<td><?php echo $addressOrder->CITY ."(".$addressOrder->CP.")"; ?></td>
							<?php } ?>
							<!--	<td><?php echo $method->TITLE; ?></td>-->
								<td>
									<?php 
										echo $date->format("d-m-Y H:i:s"); 
										if($item->SEND_START != null && $item->SEND_FINISH != NULL) {
											echo "<br/><em>Entrega:". $orderObj->franjaInfo($item)."</em>";
										}
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
									<a href="<?php echo DOMAINZP; ?>?view=order&mod=order&ref=<?php echo $item->REF; ?>" >
										<i class="fa fa-edit"></i>
									</a>
								</td>
							</tr>
				<?php 		$cont++;
						}
					}
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>

       

