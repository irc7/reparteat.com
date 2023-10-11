<section id="column-left-supplier">
	<div class="separator15"></div>
	<div class="action-order textRight no-mobile">
		<button id="btn-action-order-<?php echo $idCart; ?>" class='btn btn-action-order transition bgGreen yellow<?php if($timeSup["status"] == 1 && $subTotalOrder >= $supplierCart->MIN && count($address)>0){echo " active-confirm' type='submit";}else{echo "' type='button";}?>' style="width:100%;">
			Confirmar pedido
		</button>
	</div>
	<?php 
		$userCheck = new UserWeb();
		$saldo = $userCheck->checkingSaldo($_SESSION[nameSessionZP]->ID);
	?>
	<div class="separator20"></div>
	<div id="box-list-cart">
		<h4 class="arial green">Resumen del carrito</h4>
		<div class="separator5"></div>
		<div class="separator1 bgYellow"></div>
		<div class="separator5"></div>
		<div class="cart-order-total">
			<div class="col-xs-4 no-padding">
				<h5 class="grayStrong textBox">Pedido</h5>
			</div>
			<div class="col-xs-8 textRight grayStrong textBox no-padding">
				<?php echo"<span id='subTotalOrderSumary'>". number_format($subTotalOrder,2,".","") . "</span> €"; ?>
			</div>
			<div class="col-xs-2 no-padding"></div>
		<div class="separator5"></div>
			<div class="col-xs-4 no-padding">
				<h5 class="grayStrong textBox">Gastos de envio</h5>
			</div>
			<div class="col-xs-8 textRight grayStrong textBox no-padding">
				<?php 
				$shippingOrder = $supplierCart->COST + $zoneInfo->SHIPPING;
				echo "<span id='envioOrderSumary'>".number_format($shippingOrder,2,".","") . "</span> €"; ?>
			</div>
		<div class="separator5"></div>
			<div id="line-discount-sumary">
				<div class="col-xs-4 no-padding">
					<h5 class="grayStrong textBox">Descuento</h5>
				</div>
				<div id="discount-sumary-view" class="col-xs-8 textRight grayStrong textBox no-padding">
					- 0 €
				</div>
			</div>
		<div class="separator5"></div>
		<div class="separator1 bgGrayStrong"></div>
		<div class="separator5"></div>
			<div class="col-xs-4 no-padding">
				<h4 class="grayStrong textBoxBold">TOTAL</h4>
			</div>
			<div class="col-xs-8 textRight grayStrong textBoxBold no-padding">
				<h4 class="grayStrong textBoxBold">
				<?php 
					$totalOrder = $subTotalOrder + $shippingOrder;
					echo "<span id='totalOrderSumary'>".number_format($totalOrder,2,".","") . "</span> €"; 
				?>
				</h4>
			</div>
	<?php if($saldo > 0) { ?>
			<div class="separator5"></div>
			<div class="separator1 bgWhite"></div>
			<div class="separator5"></div>	
			<div class="col-xs-10 no-padding">
				<h5 id="discountInfo" class="grayStrong textBoxBold">Utilizar saldo disponible <br/>(<span id="valueSaldo"><?php echo $saldo; ?></span> &euro;)</h5>
			</div>
			<div class="col-xs-2 textRight grayStrong textBoxBold no-padding">
				<input type="checkbox" name="Discount" id="Discount" class="form-control discountSumary" />
			</div>
	<?php } ?>
		</div>
	</div>
	<div class="separator20"></div>
	<div id="box-order-time">
		<?php 
		$ordCheckObj = new Order();
		$dateNow = new DateTime();
		$timeStimed = (($supplierCart->TIME+timeRe) * 60)+$dateNow->getTimestamp();
		$startTime = new DateTime();
		
		
		if($zoneInfo->TYPE == 'central'){
			$startTime->setTimestamp($timeStimed);
			$startTimeHour = new DateTime($dateNow->format('Y-m-d H:00:00'));
			$timeInitSeg = $startTimeHour->getTimestamp();
			$finishSup = $cartSupObj->checkingOpenFranja($supplierCart->ID, $zoneInfo->ID, (($supplierCart->TIME + timeRe) * 60));
			$totalFranjas = 0;
			
			$finishTimeHour = new DateTime($dateNow->format('Y-m-d '.$finishSup["time"]->FINISH_H.':'.$finishSup["time"]->FINISH_M.':00'));
			$textInfoHorario1 = 'Seleccione la franja horaria deseada para recibir su pedido';
			
			$timeFinishSeg = (($supplierCart->TIME + timeRe) * 60)+ $finishTimeHour->getTimestamp();
			
		}else if($zoneInfo->TYPE == 'pedania'){
			
			$finishSup = $cartSupObj->checkingOpenFranjaPedania($supplierCart->ID, $zoneInfo->ID, (($supplierCart->TIME + timeRe + timeRePedanias) * 60));
			$startTime->setTimestamp($timeStimed);
			$startTimeHour = new DateTime($dateNow->format('Y-m-d '.$finishSup['time']->START_H.':'.$finishSup['time']->START_M.':00'));
			$finishTimeHour = new DateTime($dateNow->format('Y-m-d '.$finishSup["time"]->FINISH_H.':'.$finishSup["time"]->FINISH_M.':00'));
			$timeInitSeg = $finishTimeHour->getTimestamp()+(timeRePedanias * 60);
			$totalFranjas = 0;
			$timeFinishSeg = (($supplierCart->TIME + timeRe + timeRePedanias) * 60)+ $finishTimeHour->getTimestamp();
			$textInfoHorario1 = 'Horarios disponibles de entrega.';
		}
		if($finishSup["status"] == 1){ 
			//Franja de reparto desde el horario de cierre hasta el horario de cierre más el tiempo del proveedor mas el tiempo de reparto
			$cont = 0;
			$franjaStart = new DateTime();
			$franjaStart->setTimestamp($timeInitSeg);
			$franjaFinish = new DateTime();
			$franjaFinish->setTimestamp($timeFinishSeg);
			
		?>
			<h4 class="arial green">Horario de entrega</h4>

			<div class="separator5"></div>
			<div class="separator1 bgYellow"></div>
			<div class="separator15"></div>
			<h5 class="grayStrong textBoxBold"><em><?php echo $textInfoHorario1; ?></em></h5>
			<select class="form-control order-franjas" name="franja" id="franja">
				<?php
					$activeFranja = false;
					
					if($ordCheckObj->chekingOrderFranja($franjaStart,$franjaFinish,$maxOrderZone,$zoneInfo->ID)) {
						$activeFranja = true;
						$totalFranjas++;
					}
					if($zoneInfo->TYPE == 'pedania'){ 
					?>
						<option value="<?php echo $franjaStart->format("Y-m-d H:i")."#-#".$franjaFinish->format("Y-m-d H:i"); ?>"<?php if($cont == 0){echo " selected";} ?><?php if(!$activeFranja){echo " disabled style='color:#ededed;'";} ?>>
							De <?php echo $franjaStart->format("H:i")." a ".$franjaFinish->format("H:i"); ?>
						</option>
					<?php }else if($zoneInfo->TYPE == 'central'){ 
						while(($timeInitSeg - timeFranjas) < $timeFinishSeg) {
							if($timeInitSeg > $timeStimed) {	
								$franjaStart = new DateTime();
								$franjaStart->setTimestamp($timeInitSeg - timeFranjas);
								$franjaFinish = new DateTime();
								$franjaFinish->setTimestamp($timeInitSeg);
								
								$activeFranja = false;
								
								if($ordCheckObj->chekingOrderFranja($franjaStart,$franjaFinish,$maxOrderZone,$zoneInfo->ID)) {
									$activeFranja = true;
									$totalFranjas++;
								}
								?>
								<option value="<?php echo $franjaStart->format("Y-m-d H:i")."#-#".$franjaFinish->format("Y-m-d H:i"); ?>"<?php if($cont == 0){echo " selected";} ?><?php if(!$activeFranja){echo " disabled style='color:#ededed;'";} ?>>
									De <?php echo $franjaStart->format("H:i")." a ".$franjaFinish->format("H:i"); ?>
								</option>
					<?php
							}
							$timeInitSeg = $timeInitSeg + timeFranjas;
							$cont++;
						}
						?>	
					
					<?php } ?>
			</select>
		<?php if($zoneInfo->TYPE == 'pedania'){ ?>
			<h6 class="grayStrong textBox"><em><strong>(*) Para esta zona, los repartos se harán de manera conjunta, por lo que solo hay disponible esta franja horaria para recibir su pedido.</strong></em></h6>	
		<?php } ?>
			<h6 class="grayNormal textBox"><em>Esta página se recarga automaticamente cada 60 seg, mientras confirma su pedido, puede ocurrir que las franjas horarias se completen.<br/>Compruebe que la franja seleccionada es la deseada, en caso de que no este disponible es que se habrá alcanzado el máximo de pedidos por franja</em></h6>

		<?php 
		
			if($totalFranjas == 0) { ?>
				<div class="separator5"></div>
				<div class="col-xs-12 no-padding textCenter">
					<i class="fa fa-lock danger" aria-hidden="true"></i>
					<h5 class="danger textBox textLeft">
						<em>Upss lo sentimos</em> 😟<em>, para garantizar la calidad de servicio La cocina</em> 👩‍🍳 <em>de este establecimiento ya no puede con más pedidos, pero pásate por otro</em> 🍽️ <em>y prueba.</em>
					</h5>
				</div>
				<script type="text/javascript">
					$(document).ready(function(){
						$(".btn-action-order").attr("disabled",true);
						$("#franja").attr("disabled",true);
					});
				</script>
		<?php 
				//insertamos el registro de que no hay franjas
				$q = "INSERT INTO `".preBD."order_no_franja`(`DATE`, `IDUSER`, `IDSUPPLIER`, `COST`) 
						VALUES (NOW(),".$_SESSION[nameSessionZP]->ID.",".$idCart.", ".number_format($totalOrder,2,".","").")";
				checkingQuery($connectBD, $q);
			} 
		}else {
		?>
			<div class="separator5"></div>
			<div class="col-xs-12 no-padding">
				<i class="fa fa-lock danger" aria-hidden="true"></i>
				<span class="textBoxBold danger">No disponible</span><br>
				<span class="info-time textBoxItalic danger">El restaurante ya no está disponible para pedidos.</span>
			</div>
			<script type="text/javascript">
				$(document).ready(function(){
					$(".btn-action-order").attr("disabled",true);
					$("#franja").attr("disabled",true);
				});
			</script>
<?php 
		}
	?>
		
<?php /*
		<div class="separator5"></div>
		<div class="col-xs-8 no-padding">
			<h5 class="grayStrong textBoxBold"><em>Tiempo estimado</em></h5>
		</div>
		<div class="col-xs-4 textRight grayStrong textBoxBold no-padding">
			<h5 class="grayStrong textBoxBold">
				<em><?php echo $supplierCart->TIME + timeRe; ?> min</em>
			</h5>
		</div>
*/ ?>
	</div>
	<div class="separator20"></div>
	<div id="box-order-comment">
		<div class="col-xs-12 no-padding">
			<h5 class="grayStrong textBoxBold">Comentario</h5>
		</div>
		<div class="col-xs-12 grayStrong textBoxBold no-padding">
			<textarea class="form-control order-comment textBox" name="comment" id="comment" placeholder="Escribe aquí tu comentario, alergia o intolerancia alimentaria ... "></textarea>
		</div>
	</div>
	<div class="separator20 no-pc"></div>
	<div class="mnu-btn-order no-pc">
		<div class="col-xs-5 no-padding">
			<div class="action-order textLeft">
				<a href="<?php echo DOMAIN.SLUGSUP."/".$supplierCart->SLUG; ?>" title="Volver a <?php echo $supplierCart->TITLE; ?>">
					<button id="btn-action-order-<?php echo $idCart; ?>" type="button" class="btn btn-action-order transition bgGreen yellow active-confirm">
						<i class="fa fa-arrow-circle-left" aria-hidden="true"></i> volver
					</button>
				</a>
			</div>
		</div>
		<div class="col-xs-7 no-padding">
			<div class="action-order textRight">
				<button id="btn-action-order-<?php echo $idCart; ?>" type="submit" class="btn btn-action-order transition bgGreen yellow<?php if($timeSup["status"] == 1 && $subTotalOrder >= $supplierCart->MIN){echo " active-confirm";}?>">
					Confirmar pedido
				</button>
			</div>
			<div class="separator15"></div>
			<div class="textBoxItalic textRight <?php echo $classTimeSup; ?>">
				<i class="fa fa-<?php echo $iconTimeSup . " ".$classTimeSup; ?>" aria-hidden="true"></i>
				<span class="textBoxItalic <?php echo $classTimeSup; ?>">
					<?php echo $textTime; ?>
				</span>
			</div>
		</div>
	</div>
	<div class="separator20"></div>
</section>

