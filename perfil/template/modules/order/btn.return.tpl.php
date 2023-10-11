<button type="button" id="btn-action-order-return-<?php echo $refSend; ?>" class="btn btn-primary floatLeft transition btn-action-order-return"><?php echo $btnText; ?></button>
<div id="msg-alert-order-return-<?php echo $refSend; ?>" class="msg-alert msg-alert-order">
	<div class="container">
		<div class="row">
			<div class="wrap-msg-alert text-center">
				<button class="btn-close-alert-order floatRight"><i class="fa fa-times grayStrong"></i></button>
				<div class="separator5"></div>
				<h4 class="green"><?php echo $text1; ?></h4>
				<div class="separator5"></div>
				<form method="post" action="<?php echo DOMAINZP; ?>actualizar-pedido" name="accept-order" id="accept-order">
					<input type="hidden" name="next" value="<?php echo $nextStatus; ?>" />
					<input type="hidden" name="ref" value="<?php echo $refSend; ?>" />
					
					<?php if($text2 != "") { ?>
						<div class="form-group">
							<h5 class="grayNormal"><?php echo $text2; ?></h5>
						</div>
					<?php } ?>
					<?php if($inputView) { ?>
						<div class="form-group ">
							<label for="aux" class="textLeft"><?php echo $label; ?>:</label>
							<input type="number" class="form-control form-control-user" name="aux" id="auxAccept" title="<?php echo $inputTitle; ?>" value="<?php echo $valueInput; ?>" placeholder="<?php echo $inputTitle; ?>" required />
							<p class="error-auxAccept"></p>
						</div>
					<?php } ?>
					<button type="submit" class="btn btn-primary floatRight transition btn-send-order">Aceptar</button>
				</form>
				<?php if($inputView) { ?>
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
				<?php } ?>
			</div>
		</div>
	</div>
</div>