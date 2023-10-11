
          <!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Listado de pedidos</h1>
<p class="mb-4"></p>

<!-- DataTales Example -->
<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary">Todos los pedidos</h6>
	</div>
	<div class="card-body">
		<div class="row">
	<?php if(is_array($orders)) {
			$orders2 = array();
			$i=0;
			foreach($orders as $item) { 
				
				$zoneOrder = $zObj->infoZone($item->IDZONE);
				$dateCreate = new DateTime($item->DATE_CREATE);
				$dateStart = new DateTime($item->DATE_START);
				$dateSend = new DateTime($item->SEND_START);
				if($item->SEND_START != "0000-00-00 00:00:00") {
					$dateCalcHome = new DateTime($item->SEND_START);
				}else{
					if($item->DATE_START != "0000-00-00 00:00:00") {
						$dateCalcHome = new DateTime($item->DATE_START);
					}else {
						$dateCalcHome = new DateTime($item->DATE_CREATE);
					}
				}
				$timeRep = intval($item->TIMEREPARTIDOR);
				if($timeRep == 0) {
					$timeRep = $zoneOrder->TIME_DELIVERY;
				}
				$timeHome = ($item->TIMESUPPLIER + $item->TIMEREPARTIDOR) * 60;//pasamos a segundos
				//$segs = intval($dateCalcHome->getTimestamp()) + intval($timeHome) - $now->getTimestamp();
				$segs = intval($dateCalcHome->getTimestamp());
				$dateCalcHome->setTimestamp($segs);
				
				$orders2[$i]["obj"] = $item;
				$orders2[$i]["seg"] = $segs;
				$i++;
				
			}	
			$orders2 = orderArrayByCampAsc($orders2, "seg");
			$orders = array();
			for($i=0;$i<count($orders2);$i++) {
				$orders[] = $orders2[$i]["obj"];
			}
			
			foreach($orders as $item) { 
				$zoneOrder = $zObj->infoZone($item->IDZONE);
				$supplier = $supObj->infoSupplierById($item->IDSUPPLIER);
				$statusOrder = $orderObj->infoStatusOrder($item->STATUS); 
				$userOrder = $userObj->infoUserWebById($item->IDUSER);
				$name = $userOrder->NAME ." ".$userOrder->SURNAME; 
				
				$method = $orderObj->orderMethodPay($item->IDMETHODPAY);
				
				
				$dateCreate = new DateTime($item->DATE_CREATE);
				$dateStart = new DateTime($item->DATE_START);
				if($item->DATE_START != "0000-00-00 00:00:00") {
					$dateCalcChicken = new DateTime($item->DATE_START);
					$dateCalcHome = new DateTime($item->DATE_START);
				}else {
					$dateCalcChicken = new DateTime($item->DATE_CREATE);
					$dateCalcHome = new DateTime($item->DATE_CREATE);
				}
				$timeChicken = $item->TIMESUPPLIER * 60;//pasamos a segundos
				$segs = $dateCalcChicken->getTimestamp() + $timeChicken;
				$dateCalcChicken->setTimestamp($segs);
				
				$timeRep = intval($item->TIMEREPARTIDOR);
				if($timeRep == 0) {
					$timeRep = $zoneOrder->TIME_DELIVERY;
				}
				$timeHome = ($item->TIMESUPPLIER + $item->TIMEREPARTIDOR) * 60;//pasamos a segundos
				$segs = intval($dateCalcHome->getTimestamp()) + intval($timeHome);
				$dateCalcHome->setTimestamp($segs);
	?>
				<div class="col-xl-3 col-md-6 mb-4 wrap-list-delivery border-delivery-<?php echo $statusOrder->COLOR; ?> bgGrayLight">
					<div class="header-list-delivery">
						<div class="col-xs-8 no-padding">
							<a href="<?php echo DOMAINZP; ?>?view=order&mod=order&ref=<?php echo $item->REF; ?>" >
								<h4 class="arial <?php echo $statusOrder->COLOR; ?>"><?php echo $item->REF; ?></h4>
							</a>
						</div>
						<div class="col-xs-4 no-padding textRight">
							<h5 class="arial grayStrong"><?php echo $item->COST; ?> €</h5>
						</div>
						<div class="separator1"></div>
					</div>
					<div class="order-delivery-status textBox white bg<?php echo ucfirst($statusOrder->COLOR); ?>">
						<i class="fa fa-<?php echo $statusOrder->ICON; ?>" title="<?php echo $statusOrder->TITLE; ?>"></i>
						<span class="white"><?php echo $statusOrder->TITLE; ?></span>
					</div>	
					<div class="order-delivery-supplier textBoxBold white bgGrayStrong">
						<em><?php echo $supplier->TITLE; ?></em>
					</div>
					<?php if($item->IDMETHODPAY == 2) { ?>
					<div class="order-delivery-date">
						<h6 class="textBoxBold orange">PAGADO por TPV Virtual</h6>
					</div>
					<?php }else if($item->IDMETHODPAY == 3) { ?>
					<div class="order-delivery-date">
						<h6 class="textBoxBold orange">PAGADO por BIZUM</h6>
					</div>
					<?php } ?>
					<div class="textBoxBold grayStrong text-one-line">
						<?php echo $name; ?>
					</div>
					<div class="order-delivery-address">
						<?php $addressSend = $orderObj->orderAddress($item->IDADDRESS); 
							echo $addressSend->STREET."<br/>".$addressSend->CITY/*."<br/>".$addressSend->CP." - ". $addressSend->PROVINCE*/;
						?>
					</div>
					<?php if(trim($userOrder->PHONE != "")) { ?>
					<div class="order-delivery-phone">
						<a class="green textBoxBold" href="tel:<?php echo str_replace(" ", "", $userOrder->PHONE); ?>"><?php echo $userOrder->PHONE; ?></a>
					</div>
					<?php } ?>
					<div class="order-delivery-date">
						<h6 class="textBoxBold">
						<em>Entrega</em>: <?php /*echo $dateCalcHome->format("H:i")*/echo $orderObj->franjaInfoNoDate($item); ?>
							
						</h6>
					<?php /*
						<ul>
						<li class="textBox">
						Realizado: <?php if($now->format("d") == $dateCalcChicken->format("d") && $now->format("m") == $dateCalcChicken->format("m") && $now->format("Y") == $dateCalcChicken->format("Y")) {
							echo "Hoy a las ".$dateCreate->format("H:i");
						}else{
							echo $dateCreate->format("d-m-Y H:i"); 
						}
						?>
						</li>
						<li class="textBox"><em>Aceptado</em>: <?php echo $dateStart->format("H:i"); ?></li>
						<li class="textBox"><em>Acabado</em>: <?php echo $dateCalcChicken->format("H:i"); ?></li>
						</ul>
						</div>
						<div class="order-delivery-cost textRight textBoxBold bgWhite">
						<?php echo $item->COST; ?> €
						*/ ?>
					</div>
					
					<div class="textCenter">
						<?php require("template/modules/order/orders-delivery-action.tpl.php"); ?>
					</div>
					
				</div>
	<?php 	}
		}
	?>				
		</div>
	</div>
</div>

       

