
          <!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Seguimiento de pedidos</h1>
<p class="mb-4"></p>

<div class="container">
	<div class='row'>	
		<?php 
			if(count($orders)>0) {
				foreach($orders as $row){ 
					$statusOrder = $orderObj->infoStatusOrder($row->idStatus);
					$orderBDInfo = $orderObj->infoOrderByRef($row->pedido);
					$userOrder = $userObj->infoUserWebById($orderBDInfo->IDUSER);
				?>			
				<div class='box-follow-order col-xs-12 bgWhite'>
					<div class="header-follow row  bg<?php echo ucfirst($statusOrder->COLOR); ?>">
						<div class='col-xs-4 white ref-order-follow'>
							<h4><?php echo $row->pedido; ?></h4>
						</div>
						<div class='col-xs-8 white textRight status-order-follow'>
							<h5><?php echo $statusOrder->TITLE; ?>  <i class="fa fa-<?php echo $statusOrder->ICON; ?>"></i></h5>
						</div>
					</div>
					<div class="wrap-order-mobile">
						<div class='col-xs-12'>
							<ul id="follow-order">
								<li><strong>Ref.:</strong> <?php echo $row->pedido; ?></li>
								<li><strong>Cliente:</strong> <?php echo $userOrder->NAME." ".$userOrder->SURNAME; ?> 
								<li><strong>Tel:</strong> <a href="tel:+34<?php echo $userOrder->PHONE; ?>"><?php echo $userOrder->PHONE; ?></a></li>
								<li><strong>Rest.:</strong> <?php echo $row->establecimiento; ?></li>
								<li><strong>Repartidor:</strong> <?php echo $row->repartidor; ?></li>
								<li>
									<strong>Realización:</strong> 
									<?php 
										$date = new DateTime($row->fecha_pedido);
										echo $date->format("d-m-Y H:i:s");
									?> 
								</li>
								<li><strong>Franja de entrega:</strong>
									<?php 
										if($row->send_start == null && $row->send_finish == null) {
											$date = new DateTime($row->estimacion_entrega);
											echo $date->format("d-m-Y H:i:s");
										}else {
											echo $orderObj->franjaInfoMin($row);
										}
									?>
								</li>
								<li>
									<div class="separator15"></div>
									<a href="<?php echo DOMAINZP; ?>?view=order&mod=order&z=<?php echo $idZone; ?>&ref=<?php echo $row->pedido; ?>">
										<button class="btn btn-primary transition bgGreen yellow">VER PEDIDO</button>
									</a>
								</li>
							
							</ul>
						</div>
					</div>
					<div class="wrap-order-pc">
						<div class='col-md-12 col-sm-12 col-xs-12 no-mobile'>
							<h5 class="textBold">
							<?php 
								$date = new DateTime($row->fecha_pedido);
								echo $date->format("d-m-Y H:i:s");
							?>
							</h5>
						</div> 
						<div class='col-md-6 col-sm-6 col-xs-12'>
							<ul id="follow-order">
								<li><strong>Establecimiento:</strong> <?php echo $row->establecimiento; ?></li>
								<li><strong>Repartidor:</strong> <?php echo $row->repartidor; ?></li>
								<li><strong>Dirección entrega:</strong> <?php echo $row->dir_entrega; ?></li>
								<li>
									<div class="separator15"></div>
									<a href="<?php echo DOMAINZP; ?>?view=order&mod=order&z=<?php echo $idZone; ?>&ref=<?php echo $row->pedido; ?>">
										<button class="btn btn-primary transition bgGreen yellow">VER PEDIDO</button>
									</a>
								</li>
							
							</ul>
						</div>
						<div class='col-md-6 col-sm-6 col-xs-12'>
							<ul id="follow-order">
								<li><strong>Queda cocina:</strong> -<?php echo conversorSegundosHoras($row->queda_cocina*(-1)); ?></li>
								<li><strong>Lleva terminado:</strong> <?php echo conversorSegundosHoras($row->lleva_terminado); ?></li>
								<li><strong>Estimación:</strong> <?php echo conversorSegundosHoras($row->queda_estimacion*(-1)); ?></li>
								<li><strong>Comienzo cocina:</strong>
								<?php 
									$date = new DateTime($row->comienzo_cocina);
									echo $date->format("d-m-Y H:i:s");
								?>
								</li>
								<li><strong>Terminado cocina:</strong>
								<?php 
									$date = new DateTime($row->terminado_cocina);
									echo $date->format("d-m-Y H:i:s");
								?>
								</li>
								<li><strong>Franja de entrega:</strong>
								<?php 
									if($row->send_start == null && $row->send_finish == null) {
										$date = new DateTime($row->estimacion_entrega);
										echo $date->format("d-m-Y H:i:s");
									}else {
										echo $orderObj->franjaInfoMin($row);
									}
								?>
								</li>
							</ul>
						</div>
					</div>
					<div class="separator20"></div>
				</div>
		<?php 	} 
			}else {
				$msgError = "Actualmente no hay pedidos pendientes de seguimiento.";	
			} ?>			
	</div>
</div>
<div class="separator50"></div>

       

