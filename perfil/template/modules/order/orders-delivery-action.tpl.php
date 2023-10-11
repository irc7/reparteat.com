<?php $refSend = $item->REF;
	/*if($item->IDREPARTIDOR == 0 && ($item->STATUS == 3 || $item->STATUS == 4)) { ?>
		<button type="button" id="btn-action-rep-accept" class="btn-action-rep-accept btn btn-primary floatLeft transition" data-order="<?php echo $item->ID; ?>">
			Coger pedido
		</button>
		<div id="msg-alert-rep-accept-<?php echo $item->ID; ?>" class="msg-alert msg-alert-order">
			<div class="container">
				<div class="row">
					<div class="wrap-msg-alert text-center">
						<button class="btn-close-alert-order floatRight"><i class="fa fa-times grayStrong"></i></button>
						<div class="separator5"></div>
						<h4 class="green">Va a aceptar el pedido</h4>
						<div class="separator5"></div>
						<form method="post" action="<?php echo DOMAINZP; ?>actualizar-pedido" name="accept-order" id="accept-order">
							<input type="hidden" name="next" value="4" />
							<input type="hidden" name="ref" value="<?php echo $item->REF; ?>" />
							<div class="form-group ">
								<label for="aux" class="textLeft">Tiempo de reparto(min):</label>
								<input type="number" class="form-control form-control-user" name="aux" id="auxAccept" title="Tiempo de reparto" value="<?php echo timeRe; ?>" placeholder="Tiempo de reparto" required />
								<p class="error-auxAccept"></p>
							</div>
							<button type="submit" class="btn btn-primary floatRight transition btn-send-order">Aceptar</button>
						</form>
						<script type="text/javascript">
						//Validacion del formulario		
							var validation_options1 = {
								form: document.getElementById("accept-order"),
								fields: [
									{
										id: "auxAccept",
										type: "number",
										min: 1,
										max: 120
									}
								]
							};
							var v1 = new Validation(validation_options);
						</script>
					</div>
				</div>
			</div>
		</div>

		
<?php }else */if($_SESSION[nameSessionZP]->ID == $item->IDREPARTIDOR && $item->STATUS == 4) {
		$nextStatus = 5;
		$infoStatus = $orderObj->infoStatusOrder($nextStatus);
		
		$btnText = "Pasar a " . $infoStatus->TITLE;
		$text1 = "Va a pasar el pedido a " . $infoStatus->TITLE;
		$text2 = "Observaciones del pedido:<br/><em>" .$item->COMMENT."</em>";
		$inputView = false;
		$label = "";
		$inputTitle = "";
		$valueInput = 0;
		include("template/modules/order/btn.accept.tpl.php");
		
	}else if($_SESSION[nameSessionZP]->ID == $item->IDREPARTIDOR && $item->STATUS == 5) {
		$nextStatus = 6;
		$infoStatus = $orderObj->infoStatusOrder($nextStatus);
		
		$btnText = "Pasar a " . $infoStatus->TITLE;
		$text1 = "Va a pasar el pedido a " . $infoStatus->TITLE;
		$text2 = "Observaciones del pedido:<br/><em>" .$item->COMMENT."</em>";
		$inputView = false;
		$label = "";
		$inputTitle = "";
		include("template/modules/order/btn.accept.tpl.php");
		
	} 
?>
<div class="separator10"></div>
