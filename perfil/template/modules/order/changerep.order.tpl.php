<?php 
	$repartidor = $userObj->infoUserWebById($order->IDREPARTIDOR);

	$userRep = array(); 
	$userRep = $supObj->infoSupplierUserPositionZone($order->IDSUPPLIER, $idZone, 'repartidor');
	
	$viewFormRep = false;
	if(count($userRep)-1 > 0) {
		$viewFormRep = true;
	}

?>
<div class="wrap-rep-assing">
	<h5 class="textRight">Repartidor asignado: <?php echo $repartidor->NAME ." ". $repartidor->SURNAME; ?></h5>
</div>
<button type="button" id="btn-action-order-cangerep-<?php echo $refSend; ?>" class="btn btn-primary floatRight transition btn-action-order-cangerep">Cambiar repartidor</button>

<div id="msg-alert-order-cangerep-<?php echo $refSend; ?>" class="msg-alert msg-alert-order">
	<div class="container">
		<div class="row">
			<div class="wrap-msg-alert text-center">
				<button class="btn-close-alert-order floatRight"><i class="fa fa-times grayStrong"></i></button>
				<div class="separator5"></div>
				<?php if($viewFormRep){ ?>
					<h4 class="green">Cambiar repartidor</h4>
					<div class="separator5"></div>
					<form method="post" action="<?php echo DOMAINZP; ?>cambiar-repartidor" name="changerep-order" id="changerep-order">
						<input type="hidden" name="ref" value="<?php echo $refSend; ?>" />
						<div class="form-group ">
							<select class="form-control" name="newRep" id="newRep" required title="Rapartidor">
							<?php foreach($userRep as $rep) { 
								if($rep->IDUSER != $order->IDREPARTIDOR){ 
									$repUser = $userObj->infoUserWebById($rep->IDUSER);
									?>
										<option value='<?php echo $rep->IDUSER ?>'><?php echo $repUser->NAME." ".$repUser->SURNAME ?>(Prioridad <?php echo $rep->POSITION; ?>)</option>
							<?php 	}
								} ?>
							</select>
							<p class="error-newRep"></p>
						</div>
						<div class="separator5"></div>
						<div class="col-xs-12">
							<h6 class="textLeft"><i class="fa fa-exclamation-triangle orange iconSmall"></i>&nbsp;<em class="orange">Va a cambiar la asignación del repartidor para este pedido.</em></h6>
						</div>
						<div class="separator10"></div>
						<div class="col-xs-12">
							<button type="submit" class="btn btn-primary floatRight transition btn-send-order">Aceptar</button>
						</div>
						<div class="separator5"></div>
					</form>
					<script type="text/javascript">
					//Validacion del formulario		
						var validation_options1 = {
							form: document.getElementById("changerep-order"),
							fields: [
								{
									id: "newRep",
									type: "selectNumber",
									min: 1,
									max: 999999999
								}
							]
						};
						var v1 = new Validation(validation_options);
					</script>
				<?php }else{ ?>
						<div class="col-xs-12">
							<h6 class="textLeft"><i class="fa fa-exclamation-triangle orange iconSmall"></i>&nbsp;<em class="orange">No hay más repartidores asignados a este proveedor.</em></h6>
						</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>