<div id="msg-alert-action-<?php echo $identificador; ?>" class="msg-alert msg-alert-action">
	<div class="container">
		<div class="row">
			<div class="wrap-msg-alert text-center">
				<div class="separator5"></div>
				<h4 class="danger textCenter"><?php echo $textAlert; ?></h4>
				<div class="separator5"></div>
				<?php if($textAlert2 != "") { ?>
					<div class="form-group">
						<p class="textBox> grayNormal"><?php echo $textAlert2; ?></p>
					</div>
				<?php } ?>
				<div class="col-xs-6">
					<a href="<?php echo $urlAlert; ?>">
						<button type="button" class="btn btn-primary floatLeft transition btn-send-order">Aceptar</button>
					</a>
				</div>
				<div class="col-xs-6">
					<button type="button" id="msg-alert-action-close-<?php echo $identificador; ?>" class="btn btn-primary-danger transition btn-close-alert-action floatRight">Cancelar</button>
				</div>
				<div class="separator1"></div>
			</div>
		</div>
	</div>
</div>