<?php if (allowed($mnu)) { ?>
	<div class='cp_mnu_title title_header_mod'>Colores corporativos</div>
	<div class="cp_box">
		<?php 
		if (isset($_GET['msg'])) {
			$msg = utf8_encode($_GET['msg']); ?>
			<div class='cp_info' style="margin-bottom:20px;">
				<img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' />
				<p><?php echo $msg; ?></p>
			</div>
		<?php } ?>
		<p>La selección del este color afectará directamente al diseño, tanto de la web como del panel de control.</p>
      	<br/>
		<?php 
			$codeColor = substr(CORPORATIVE_COLOR, -6);
		?>
		<link rel="stylesheet" href="<?php echo DOMAIN; ?>/pdc-reparteat/js/jscripts/colorpicker/css/colorpicker.css" type="text/css" />
		<link rel="stylesheet" media="screen" type="text/css" href="<?php echo DOMAIN; ?>/pdc-reparteat/js/jscripts/colorpicker/css/layout.css" />
		<script type="text/javascript">
			var COLOR_BD = "<?php echo $codeColor; ?>";
		</script>
		<script type="text/javascript" src="<?php echo DOMAIN; ?>/pdc-reparteat/js/jscripts/colorpicker/js/colorpicker.js"></script>
		<script type="text/javascript" src="<?php echo DOMAIN; ?>/pdc-reparteat/js/jscripts/colorpicker/js/eye.js"></script>
		<script type="text/javascript" src="<?php echo DOMAIN; ?>/pdc-reparteat/js/jscripts/colorpicker/js/utils.js"></script>
		<script type="text/javascript" src="<?php echo DOMAIN; ?>/pdc-reparteat/js/jscripts/colorpicker/js/layout.js?ver=1.0.2"></script>
		<form method='post' action='modules/configuration/change_corporative_color.php' enctype='multipart/form-data' id='mainform' name='mainform'>
			<div class="cp_table650" style="overflow:visible;">
				<div class='cp_formfield bold'>
					<label for='codeColor'>Seleccione color:</label>
				</div>
				<div id="customWidget" style="margin-left:150px;">
					<div id="colorSelector2">
						<div style="background-color: <?php echo CORPORATIVE_COLOR; ?>;"></div>
					</div>
					<div id="colorpickerHolder2"></div>
				</div>
			</div>
			
			<div style="clear:both;height:30px;"></div>
			
			<div class='cp_formfield bold'>&nbsp;</div>
			<input type='button' name='save' value='Guardar' onclick='showloading(1);validate(this); return false;' style="float: right;padding-left: 5px;padding-right: 5px;" />
			<img class='image middle' src='images/loading.gif' style='visibility: hidden; padding: 10px 0px 0px 20px;' id='loading' />
		</form>
    </div>
	<?php
}else{
	echo "<p>No tiene permiso para acceder a esta sección.</p>";
}?>		