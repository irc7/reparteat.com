<?php if (allowed ($mnu)){ ?>
<div class='cp_mnu_title title_header_mod'>Editar proveedor</div>
<?php
	
	require_once("includes/classes/Zone/class.Zone.php");
	require_once("includes/classes/Address/class.Address.php");
	require_once("includes/classes/Image/class.Image.php");
	$zone = new Zone();
	$zones = array(); 
	$zones = $zone->listZones(); 
	$addressObj = new Address();
	
	if (isset($_GET['msg'])) {
		$msg = utf8_encode($_GET['msg']);
		echo "<div class='container container-admin'><div class='cp_info'><img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' /><p>".$msg."</p></div></div>";
	}

	if(isset($_GET["id"]) && intval($_GET["id"]) > 0) {
		$id = $_GET["id"];	
	}else {
		$location = "index.php?mnu=".$mnu."&com=".$com."&tpl=option&opt=".$opt."&msg=".utf8_decode("Usuario desconocido");
?>
		<script type="text/javascript">
			window.location.href = "<?php echo $location; ?>";
		</script>
<?php
	}
	$point = $addressObj->infoByID($id);
	
?>	
	<script src="<?php echo DOMAIN; ?>pdc-reparteat/js/checking.validation.js"></script>
	<script src="<?php echo DOMAIN; ?>pdc-reparteat/js/class.Validation.js"></script>
	<form method='post' action='modules/points/edit.php' enctype='multipart/form-data' id='mainform' name='mainform'>
		<input type="hidden" name="mnu" value="<?php echo $mnu; ?>" />
		<input type="hidden" name="com" value="<?php echo $com; ?>" />
		<input type="hidden" name="tpl" value="<?php echo $tpl; ?>" />
		<input type="hidden" name="opt" value="<?php echo $opt; ?>" />
		<input type="hidden" name="idPoint" value="<?php echo $id; ?>" />
		<div class='container container-admin darkshaded-space bgGrayNormal white'>
			<div class="separator10">&nbsp;</div>
			<div class="form-group">
				<label class="label-field white" for="status">Estado:</label>
				<select class="form-control form-s" name="status" id="status" title="Estado">
					<option value='1'<?php if($point->FAV == 1){ echo ' selected="selected"';} ?>>Activado</option>
					<option value='0'<?php if($point->FAV == 0){ echo ' selected="selected"';} ?>>Desactivado</option>
				</select>
			</div>
		</div>
		<div class="separator20">&nbsp;</div>
		<div class='container container-admin'>
			<div class="separator30">&nbsp;</div>
			<div class='row padding-space'>	
				
				<div class="form-group">
					<label class="label-field" for="Title">Dirección o nombre *:</label>
					<input type="text" class="form-control form-m" name="Street" id="Street" title="Dirección o nombre" placeholder="Dirección o nombre" value="<?php echo $point->STREET; ?>" style="margin-right:10px;" />
				</div>
				<div class="form-group">
					<label class="label-field" for="Title">Zona de reparto *:</label>
					<select class="form-control form-s" name="Zone" id="Zone" title="Zona de reparto"> 
						<?php foreach($zones as $zone) { ?>
							<option value="<?php echo $zone->ID; ?>"<?php if($point->IDZONE == $zone->ID){echo " selected";} ?>>
								<?php echo $zone->CITY." (".$zone->CP.")"; ?>
							</option>
						<?php } ?>
					</select>
				</div>
				<div class="separator1"></div>
				<div class='container container-admin darkshaded-space bgGrayLight white'>
					<div class="form-group">
						<div class="separator20">&nbsp;</div>
						<div class="white bgGrayStrong bold" style="font-size:14px;padding:10px;border-radius:5px;">Imagen:</div>
						<div class="separator30">&nbsp;</div>
					<?php if($point->IMAGE != ""): 
							$image = new Image();
							$image->path = "points";
							$image->paththumb = "icon";
					?>
						<div id="wrap-image" class="col-sm-6">
							<a href='<?php echo DOMAIN.$image->dirbasename.$image->path."/".$image->paththumb."/".$point->IMAGE; ?>' class='lytebox' data-lyte-options='group:<?php echo $point->ID; ?>'>
								<img src="<?php echo $image->dirView.$image->path."/".$image->paththumb."/".$point->IMAGE; ?>" style="max-width:100%;margin-bottom:10px;" />
							</a>
						</div>
						<div class="col-sm-6">
							<label class="label-field" for="Image" style="float:none;">Modificar: </label>
					<?php else: ?>
						<div class="col-sm-12">
					<?php endif; ?>
							<br/>
							<input class="form-control form-l" type="file" name="Image" id="Image" style="float:none;" />
							<div class="form-group" style="margin:0px;">
								<div style="font-style:italic;color:#c00;font-size:11px;">
									Imagen óptima: PNG transparente de 400 x 400 px
								</div>
							</div>
							<div class="separator10">&nbsp;</div>
							<input type="hidden" name="action-image" id="action-image" value="0" />
					<?php if($point->IMAGE != ""): ?>
							<button class="btn btn-default transition floatLeft bgGreen white bold delete-img" type="button" id="delete-image">Eliminar</button>
					<?php endif; ?>
						</div>
					</div>
					<div class="separator10">&nbsp;</div>
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
					<input class="btn tf-btn btn-default transition floatRight bgGreen white bold" type='submit' name='save' value='GUARDAR PUNTO DE RECOGIDA' />
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
					id: "Title",
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