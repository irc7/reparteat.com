<?php if (allowed ($mnu)){ ?>
<div class='cp_mnu_title title_header_mod'>Editar parámetros configuracióna</div>
<?php
	if (isset($_GET['msg'])) {
		$msg = utf8_encode($_GET['msg']);
		echo "<div class='container container-admin'><div class='cp_info'><img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' /><p>".$msg."</p></div></div>";
	}

	
	require_once("includes/classes/UserWeb/class.UserWeb.php");
	require_once("includes/classes/ConfigShop/class.ConfigShop.php");
	
		
	$userObj = new UserWeb();
	$userRes = array(); 
	
	$config =  new ConfigShop();
	$params = $config->listParams();
?>	
	<script src="<?php echo DOMAIN; ?>pdc-reparteat/js/checking.validation.js"></script>
	<script src="<?php echo DOMAIN; ?>pdc-reparteat/js/class.Validation.js"></script>
	<form method='post' action='modules/configshop/edit.php' enctype='multipart/form-data' id='mainform' name='mainform'>
		<input type="hidden" name="mnu" value="<?php echo $mnu; ?>" />
		<input type="hidden" name="com" value="<?php echo $com; ?>" />
		<input type="hidden" name="tpl" value="<?php echo $tpl; ?>" />
		<input type="hidden" name="opt" value="<?php echo $opt; ?>" />
		<div class='container container-admin'>
			<div class='row'>	
			<?php foreach($params as $item) { ?>
				<div class="form-group">
					<label class="label-field" for="Config-<?php echo $item->ID; ?>"><?php echo $item->TITLE; ?> *:</label>
					<input type="number" name="Config-<?php echo $item->ID; ?>" id="Config-<?php echo $item->ID; ?>" class="form-control textRight form-xs" title="<?php echo $item->TITLE; ?>" placeholder="<?php echo $item->TITLE; ?> *" required value="<?php echo $item->VALUE; ?>" step="1" />
					<label class="label-field" for="Config-<?php echo $item->ID; ?>" style="margin-left:10px;"><em><?php echo $item->TEXT; ?></em></label>
					<p id="error-Config-<?php echo $item->ID; ?>"></p>
				</div>
				<div class="separator5">&nbsp;</div>
				<div class="separator1 bgGrayLight">&nbsp;</div>
				<div class="separator20">&nbsp;</div>
			<?php } ?>
			</div>
			<div class="separator30">&nbsp;</div>
			
		</div>
		<div class='container container-admin'>
			<div class='row'>	
				<div class='col-md-5'>&nbsp;</div>
					<div class='col-md-2'>
					<img class='image middle' src='images/loading.gif' style='visibility: hidden; padding: 10px 20px 0px 0px;' id='loading'>
				</div>
				<div class='col-md-5'>
					<input class="btn tf-btn btn-default transition floatRight bgGreen white bold" type='submit' name='save' value='GUARDAR' />
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
				<?php $i=0;foreach($params as $item) { ?>	
				{
					id: "Config-<?php echo $item->ID; ?>",
					type: "number",
					min: 1,
					max: 999999999
				}<?php if($i<count($params)-1){echo ",";} ?>
				<?php $i++; } ?>
			]
		};
		var v2 = new Validation(validation_options);

	</script>
<?php 
}else{
	echo "<p>No tiene permiso para acceder a esta sección.</p>";
}?>	