

<button type="button" id="btn-action-order-accept-<?php echo $refSend; ?>" class="btn btn-primary floatLeft transition btn-action-order-accept"><?php echo $btnText; ?></button>

<div id="msg-alert-order-accept-<?php echo $refSend; ?>" class="msg-alert msg-alert-order">
	<div class="container">
		<div class="row">
			<div class="wrap-msg-alert text-center">
				<button class="btn-close-alert-order floatRight"><i class="fa fa-times grayStrong"></i></button>
				<div class="separator5"></div>
				<h4 class="green"><?php echo $text1; ?></h4>
				<div class="separator5"></div>
				<form method="post" action="<?php echo DOMAINZP; ?>actualizar-pedido" name="accept-order" id="accept-order">
					<input type="hidden" name="ref" value="<?php echo $refSend; ?>" />
					<div class="form-group ">
						<label for="aux" class="textLeft"><?php echo $label; ?>:</label>
						<select class="form-control" name="next" id="nextChangeStatus" required title="Estado">
							<option value='0' selected="selected">Selecciona el estado deseado</option>
						<?php foreach($statusList as $st) { 
							if($st->ID != $statusOrder->ID){ 
								?>
									<option value='<?php echo $st->ID ?>' class="<?php echo $st->COLOR; ?>"><?php echo $st->TITLE ?></option>
						<?php 	}
							} ?>
						</select>
						<p class="error-auxAccept"></p>
					</div>
					<div class="separator5"></div>
					<div class="col-xs-12">
						<h6 class="textLeft"><i class="fa fa-exclamation-triangle orange iconSmall"></i>&nbsp;<em class="orange">Esta operación puede provocar cambios importantes en la gestión del pedido, asegurese de que cambia al estado correcto.</em></h6>
					</div>
					<div class="separator10"></div>
					<div class="col-xs-12">
						<button type="submit" class="btn btn-primary floatRight transition btn-send-order">Aceptar</button>
					</div>
					<div class="separator5"></div>
				</form>
				<?php if($inputView) { ?>
					<script type="text/javascript">
					//Validacion del formulario		
						var validation_options1 = {
							form: document.getElementById("accept-order"),
							fields: [
								{
									id: "nextChangeStatus",
									type: "selectNumber",
									min: 1,
									max: 120
								}
							]
						};
						var v1 = new Validation(validation_options);
					</script>
				<?php } ?>
			</div>
		</div>
	</div>
</div>