<?php if (allowed ($mnu)) { 
		require_once("includes/classes/Product/class.Icon.php");
		require_once("includes/classes/Image/class.Image.php");
?>
	<script src="<?php echo DOMAIN; ?>pdc-reparteat/js/checking.validation.js"></script>
	<script src="<?php echo DOMAIN; ?>pdc-reparteat/js/class.Validation.js"></script>
	<div class='cp_mnu_title title_header_mod'>Listado de iconos para alérgenos</div>
	<div class='container container-admin'>
		<div class="separator20">&nbsp;</div>
		<div class="row">
			<button id="addIcon" class="btn tf-btn btn-default transition floatRight bgGreen white bold">Nuevo Icono</button>
		</div>
		<div class="separator20">&nbsp;</div>
		<div id="wrap-addIcon" class="form-category">
			<form method='post' action='modules/product/create_icon.php' enctype='multipart/form-data' id='mainform' name='mainform'>
				<input type="hidden" name="mnu" value="<?php echo $mnu; ?>" />
				<input type="hidden" name="com" value="<?php echo $com; ?>" />
				<input type="hidden" name="tpl" value="<?php echo $tpl; ?>" />
				<input type="hidden" name="opt" value="<?php echo $opt; ?>" />
				
				<div class='row dotted padding-space'>	
					<div class='cp_mnu_subtitle title_block'><span class="textBox grayStrong">Nuevo Icono</span></div>
					<div class="form-group">
						<label class="label-field" for="Title">Título *:</label>
						<input type="text" name="Title" id="Title" class="form-control form-s" title="Título" placeholder="Título *" disabled />
						<p id="error-Title"></p>
					</div>
					<div class="form-group">
						<label class="label-field" for="Icon">Icono:</label>
						<input type="file" name="Icon" id="Icon" class="form-control form-m" title="Icono" disabled />
						
					</div>
					<div class='form-group'>	
						<div class='col-md-5'>&nbsp;</div>
							<div class='col-md-2'>
							<img class='image middle' src='images/loading.gif' style='visibility: hidden; padding: 10px 20px 0px 0px;' id='loading' >
						</div>
						<div class='col-md-5'>
							<input class="btn tf-btn btn-default transition floatRight bgGreen white bold" type='submit' name='save' value='CREAR' disabled />
						</div>
					</div>
				</div>	
			</form>
			<div class="separator30">&nbsp;</div>
		</div>
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
	</div>	
	<?php if (isset($_GET['msg'])){ ?>
		<?php $msg = utf8_encode($_GET['msg']); ?>
		<div class='cp_info'><img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' /><?php echo $msg ?></div>
		<br/>
	<?php }
		include ("components/product/product.list.icon.php");
}else{

	echo "<p>No tiene permiso para acceder a esta sección.</p>";
	
}?>	