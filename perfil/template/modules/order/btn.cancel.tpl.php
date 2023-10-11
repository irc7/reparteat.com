<button type="button" id="btn-action-order-cancel" class="btn btn-primary-danger floatRight transition">Cancelar pedido</button>
<div id="msg-alert-order-cancel" class="msg-alert msg-alert-order">
	<div class="container">
		<div class="row">
			<div class="wrap-msg-alert text-center">
				<button class="btn-close-alert-order floatRight"><i class="fa fa-times grayStrong"></i></button>
				<div class="separator5"></div>
				<h4 class="danger">Va a cancelar el pedido</h4>
				<div class="separator5"></div>
				<form method="post" action="<?php echo DOMAINZP; ?>actualizar-pedido" name="cancel-order" id="cancel-order">
					<input type="hidden" name="next" value="<?php echo $statusNext; ?>" />
					<input type="hidden" name="ref" value="<?php echo $order->REF; ?>" />
					<div class="form-group ">
						<label for="aux" class="textLeft">Motivo de la cancelación:</label>
						<input type="text" class="form-control form-control-user" name="aux" id="auxCancel" title="Motivo de la cancelación value="" placeholder="Escriba aquí el motivo de la cancelación" required />
						<p class="error-auxCancel"></p>
					</div>
					<button type="submit" class="btn btn-primary-danger floatRight transition btn-send-order">Rechazar</button>
				</form>
				<script type="text/javascript">
				//Validacion del formulario		
					var validation_options2 = {
						form: document.getElementById("cancel-order"),
						fields: [
							{
								id: "auxCancel",
								type: "string",
								min: 1,
								max: 256
							}
						]
					};
					var v2 = new Validation(validation_options);
				</script>
			</div>
		</div>
	</div>
</div>