<?php if (allowed ($mnu)) { 
		require_once("includes/classes/Supplier/class.Category.php");
?>
	<script src="<?php echo DOMAIN; ?>pdc-reparteat/js/checking.validation.js"></script>
	<script src="<?php echo DOMAIN; ?>pdc-reparteat/js/class.Validation.js"></script>
	<div class='cp_mnu_title title_header_mod'>Listado de categorias de proveedores</div>
	<div class='container container-admin'>
		<div class="separator20">&nbsp;</div>
		<div class="row">
			<button id="addCategory" class="btn tf-btn btn-default transition floatRight bgGreen white bold">Nueva categoria</button>
		</div>
		<div class="separator20">&nbsp;</div>
		<div id="wrap-addCategory" class="form-category">
			<form method='post' action='modules/supplier/create_category.php' enctype='multipart/form-data' id='mainform' name='mainform'>
				<input type="hidden" name="mnu" value="<?php echo $mnu; ?>" />
				<input type="hidden" name="com" value="<?php echo $com; ?>" />
				<input type="hidden" name="tpl" value="<?php echo $tpl; ?>" />
				<input type="hidden" name="opt" value="<?php echo $opt; ?>" />
				
				<div class='row dotted padding-space'>	
					<div class='cp_mnu_subtitle title_block'><span class="textBox grayStrong">Nueva categoria</span></div>
					<div class="form-group">
						<label class="label-field" for="Name">Título *:</label>
						<input type="text" name="Title" id="Title" class="form-control form-s" title="Título" placeholder="Título *" disabled />
						<p id="error-Title"></p>
					</div>
					<div class="form-group">
						<label class="label-field" for="Surame">Descripción:</label>
						<textarea name="Text" id="Text" class="form-control textarea-field-100x3" disabled></textarea>
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
		include ("components/supplier/supplier.list.category.php");
}else{

	echo "<p>No tiene permiso para acceder a esta sección.</p>";
	
}?>	