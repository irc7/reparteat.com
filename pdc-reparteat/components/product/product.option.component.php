<?php if (allowed ($mnu)) { 
		require_once("includes/classes/Product/class.Component.php");
		require_once("includes/classes/Product/class.Icon.php");
		require_once("includes/classes/Image/class.Image.php");
		
		$iconObj = new Icon();
		$allIcon = $iconObj->allIcon();
		
		$imgIcon = new Image();
		$imgIcon->path = "product";
		$imgIcon->pathoriginal = "original";
		$imgIcon->paththumb = "icon";
?>
	<script src="<?php echo DOMAIN; ?>pdc-reparteat/js/checking.validation.js"></script>
	<script src="<?php echo DOMAIN; ?>pdc-reparteat/js/class.Validation.js"></script>
	<div class='cp_mnu_title title_header_mod'>Listado de ingredientes de productos</div>
	<div class='container container-admin'>
		<div class="separator20">&nbsp;</div>
		<div class="row">
			<button id="addComponent" class="btn tf-btn btn-default transition floatRight bgGreen white bold">Nuevo ingrediente</button>
		</div>
		<div class="separator20">&nbsp;</div>
		<div id="wrap-addComponent" class="form-Component">
			<form method='post' action='modules/product/create_component.php' enctype='multipart/form-data' id='mainform' name='mainform'>
				<input type="hidden" name="mnu" value="<?php echo $mnu; ?>" />
				<input type="hidden" name="com" value="<?php echo $com; ?>" />
				<input type="hidden" name="tpl" value="<?php echo $tpl; ?>" />
				<input type="hidden" name="opt" value="<?php echo $opt; ?>" />
				
				<div class='row dotted padding-space'>	
					<div class='cp_mnu_subtitle title_block'><span class="textBox grayStrong">Nuevo ingrediente</span></div>
					<div class="form-group">
						<label class="label-field" for="Name">Título *:</label>
						<input type="text" name="Title" id="Title" class="form-control form-s" title="Título" placeholder="Título *" disabled />
						<p id="error-Title"></p>
					</div>
					<div class="form-group">
						<label class="label-field" for="Cost">Precio *:</label>
						<input type="number" name="Cost" id="Cost" class="form-control form-xs" title="Precio" placeholder="Precio *" step="0.01" required />
						<p id="error-Cost"></p>
					</div>
					<div class="form-group">
						<label class="label-field" for="Icon">Iconos alérgenos:</label>
						<select name="Icon[]" id="Icon" class="form-control form-xl" multiple disabled>
							<?php foreach($allIcon as $row) { ?>
								<option value="<?php echo $row->ID; ?>" class="col-xs-2">
									<?php echo $row->TITLE; ?>
								</option>
							<?php } ?>
						</select>
						<style type="text/css">
								
						<?php foreach($allIcon as $row) { ?>
							<?php if($row->ICON != ""){ ?>
								select#Icon option[value="<?php echo $row->ID; ?>"]{
									background-image:url(<?php echo $imgIcon->dirView.$imgIcon->path."/".$imgIcon->paththumb."/1-".$row->ICON; ?>);   
								}
							<?php } ?>
						<?php } ?>
						</style>
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
		include ("components/product/product.list.component.php");
}else{

	echo "<p>No tiene permiso para acceder a esta sección.</p>";
	
}?>	