<?php if (allowed ($mnu)) { ?>
	<div class='cp_mnu_title title_header_mod'>Nuevo punto de recogida</div>
	<?php
		if (isset($_GET['msg'])) {
			$msg = utf8_encode($_GET['msg']);
			echo "<div class='cp_info'><img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' /><p>".$msg."</p></div>\r\n";
		}
		require_once("includes/classes/Zone/class.Zone.php");
		$zone = new Zone();
		$zones = array(); 
		$zones = $zone->listZones(); 
		require_once("includes/classes/UserWeb/class.UserWeb.php");
		
		
	?>		
	<br/>
	<script src="<?php echo DOMAIN; ?>pdc-reparteat/js/checking.validation.js"></script>
	<script src="<?php echo DOMAIN; ?>pdc-reparteat/js/class.Validation.js"></script>
	<form method='post' action='modules/points/create.php' enctype='multipart/form-data' id='mainform' name='mainform'>
		<input type="hidden" name="mnu" value="<?php echo $mnu; ?>" />
		<input type="hidden" name="com" value="<?php echo $com; ?>" />
		<input type="hidden" name="tpl" value="<?php echo $tpl; ?>" />
		<input type="hidden" name="opt" value="<?php echo $opt; ?>" />
		<div class='container container-admin darkshaded-space bgGrayNormal white'>
			<div class="separator10">&nbsp;</div>
				<div class="form-group">
					<label class="label-field white" for="status">Estado:</label>
					<select class="form-control form-s" name="status" id="status" title="Estado">
						<option value='1'>Activado</option>
						<option value='0' selected="selected">Desactivado</option>
					</select>
				</div>
		</div>
		<div class="separator20">&nbsp;</div>
		<div class='container container-admin'>
			<div class="separator30">&nbsp;</div>
			<div class='row padding-space'>	
				<div class="form-group">
					<label class="label-field" for="Title">Dirección o nombre *:</label>
					<input type="text" class="form-control form-m" name="Street" id="Street" title="Direccióno nombre" placeholder="Direccióno nombre" />
					<p id="error-Street"></p>
				</div>
				<div class="form-group">
					<label class="label-field" for="Title">Zona de reparto *:</label>
					<select class="form-control form-s" name="Zone" id="Zone" title="Zona de reparto"> 
					<?php foreach($zones as $zone) { ?>
						<option value="<?php echo $zone->ID; ?>"><?php echo $zone->CITY." (".$zone->CP.")"; ?></option>
					<?php } ?>
					</select>
				</div>
				<div class="separator30"></div>
				<div class="col-sm-12 bgGrayLight">
					<div class="separator10"></div>
					<div class="form-group">
						<label class="label-field" for='Image'>Imagen:</label>
						<input  class="form-control form-m" type='file' name='Image' id='Image' />
						<div class="separator10"></div>
						<div style="width:100%;font-style:italic;color:#c00;font-size:11px;margin-left:20%;">
							Imagen óptima: PNG transparente de 400 x 400 px
						</div>
					</div>
					<div class="separator1"></div>
				</div>
			</div>
		</div>
		<div class="separator1 bgGrayStrong">&nbsp;</div>
		<div class="separator20">&nbsp;</div>
		<div class='container container-admin'>
			<div class='row'>	
				<div class='col-md-5'>&nbsp;</div>
					<div class='col-md-2'>
					<img class='image middle' src='images/loading.gif' style='visibility: hidden; padding: 10px 20px 0px 0px;' id='loading'>
				</div>
				<div class='col-md-5'>
					<input class="btn tf-btn btn-default transition floatRight bgGreen white bold" type='submit' name='save' value='CREAR PUNTO' />
				</div>
			</div>
		</div>
	</form>
	<div class="separator50">&nbsp;</div>
	<script type="text/javascript">
	//Validacion del formulario		
		var validation_options = {
			form: document.getElementById("mainform"),
			fields: [
				{
					id: "Street",
					type: "string",
					min: 2,
					max: 256
				}
			]
		};
		var v2 = new Validation(validation_options);

	</script>
<?php 
}else{
	echo "<p>No tiene permiso para acceder a esta sección.</p>";
}?>	