<?php if (allowed ($mnu)) { ?>
	<div class='cp_mnu_title title_header_mod'>Nuevo producto</div>
	<?php
		if (isset($_GET['msg'])) {
			$msg = utf8_encode($_GET['msg']);
			echo "<div class='cp_info'><img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' /><p>".$msg."</p></div>\r\n";
		}
		require_once("includes/classes/Product/class.Product.php");
		require_once("includes/classes/Product/class.Category.php");
		require_once("includes/classes/Product/class.Component.php");
		require_once("includes/classes/Supplier/class.Supplier.php");
		require_once("includes/classes/Image/class.Image.php");
		
		$catObj = new Category();
		$categories = array(); 
		$categories = $catObj->allCategories(); 
		
		$supObj = new Supplier();
		$suppliers = array(); 
		$suppliers = $supObj->allSupplier(); 
		
		$comObj = new Component();
		$coms = array(); 
		$coms = $comObj->allComponent(); 

		$dateNow = new DateTime();
	?>		
	<br/>
	<script src="<?php echo DOMAIN; ?>pdc-reparteat/js/checking.validation.js"></script>
	<script src="<?php echo DOMAIN; ?>pdc-reparteat/js/class.Validation.js"></script>
	<form method='post' action='modules/product/create.php' enctype='multipart/form-data' id='mainform' name='mainform'>
		<input type="hidden" name="mnu" value="<?php echo $mnu; ?>" />
		<input type="hidden" name="com" value="<?php echo $com; ?>" />
		<input type="hidden" name="tpl" value="<?php echo $tpl; ?>" />
		<input type="hidden" name="opt" value="<?php echo $opt; ?>" />
		<div class='container container-admin darkshaded-space bgGrayNormal white'>
			<div class="separator10">&nbsp;</div>
			<div class='form-group'> 
				<label class="label-field white" for="date-day">Fecha/hora publicación:</label>
				<input maxlength="100" size="12" class="form-control" value="<?php echo $dateNow->format("d-m-Y"); ?>" name="date_day" id="date_day" readonly="readonly" style="max-width:150px" />
				<span class='fecha-hora-min white'> | </span>
				<input type='text' class="form-control" name='Date_start_hh' id='Date_start_hh' size='1' value='<?php echo $dateNow->format("H"); ?>' style="max-width:40px"/>
				<span class='fecha-hora-min white'> : </span>
				<input type='text' class="form-control" name='Date_start_ii' id='Date_start_ii' size='1' value='<?php echo $dateNow->format("i"); ?>' style="max-width:40px"/>
			</div>
			<div class="form-group">
				<label class="label-field white" for="status">Estado:</label>
				<select class="form-control form-s" name="status" id="status" title="Estado">
					<option value='1'>Activado</option>
					<option value='0' selected>Desactivado</option>
				</select>
			</div>
		</div>
		<div class="separator20">&nbsp;</div>
		<div class='container container-admin'>
			<div class='row'>	
				<div class="form-group">
					<label class="label-field" for="Title">Nombre *:</label>
					<input type="text" name="Title" id="Title" class="form-control form-s" title="Nombre" placeholder="Nombre *" required />
					<p id="error-Title"></p>
				</div>
				<div class="form-group">
					<label class="label-field" for="Supplier">Proveedor *:</label>
					<select class="form-control form-s" name="Supplier" id="Supplier" title="Supplier" required> 
					<?php foreach($suppliers as $sup) { ?>
						<option value="<?php echo $sup->ID; ?>">
							<?php echo $sup->TITLE; ?>
						</option>
					<?php } ?>
					</select>
					<p id="error-Supplier"></p>
				</div>
				<div class="form-group">
					<label class="label-field" for="Cost">Precio *:</label>
					<input type="number" name="Cost" id="Cost" class="form-control form-xs" title="Precio" placeholder="Precio *" step="0.01" required />
					<p id="error-Cost"></p>
				</div>
			</div>
			<div class="separator30">&nbsp;</div>
			<div class='row dotted padding-space'>	
				<div class='cp_mnu_subtitle title_block'><span class="textBox grayStrong">Categorias</span></div>
			<?php foreach($categories as $cat) { ?>	
				<div class="col-md-4 col-xs-6 col-sm-12">
					<input type="checkbox" name="Category[]" title="Category" value="<?php echo $cat->ID; ?>" />
					<label for="Category"><?php echo $cat->TITLE; ?></label>
				</div>
			<?php } ?>	
			</div>
			<div class="separator30">&nbsp;</div>
			<div class='row dotted padding-space'>	
				<div class='cp_mnu_subtitle title_block'><span class="textBox grayStrong">Ingredientes</span></div>
				<div class="separator20">&nbsp;</div>
				<div class='row'>
					<div class='col-xs-5 cp_title top'>
						<div class='bold textLeft'>Nombre</div>
					</div>
					<div class='col-xs-3 cp_title top no-padding'>
						<div class='bold textLeft'>Tipo</div>
					</div>
					<div class='col-xs-3 cp_title top no-padding'>
						<div class='bold textLeft'>Precio</div>
					</div>
					<div class='col-xs-1 cp_title top'>
						<div class='bold textLeft'>&nbsp;</div>
					</div>
				</div>
				<div class="separator20">&nbsp;</div>
				<?php //$dateNow = new DateTime(); ?>
				<div id="wrap-product-coms">
				<?php for($i=1;$i<=20;$i++) { ?>
					<div id="boxproduct-coms-<?php echo $i; ?>" class="form-group box-product-coms" data="<?php if($i==1){echo "com-on";}else{echo "com-off";} ?>"<?php if($i>1){echo " style='display:none;'";} ?>>
						<div class="col-xs-5 no-padding">
							<select name="IdCom-<?php echo $i; ?>" id="IdCom-<?php echo $i; ?>" class="form-control form-l com-name"<?php if($i>1){echo " disabled";} ?>>
							<?php 
								$cont = 0;
								$firstCost = 0;
								foreach($coms as $com) { 
									if($cont == 0) {
										$firstCost = $com->COST;
									}
							?>
								<option value="<?php echo $com->ID; ?>" data="<?php echo $com->COST; ?>"><?php echo $com->TITLE; ?></option>
							<?php 
									$cont++;
								} ?>
							</select>	
						</div>
						<div class="col-xs-3 no-padding">
							<select name="TypeCom-<?php echo $i; ?>" id="TypeCom-<?php echo $i; ?>" class="form-control form-l com-type"<?php if($i>1){echo " disabled";} ?>>
								<option value="basic" selected>Básico</option>
								<option value="optional">Opcional</option>
							</select>	
						</div>
						<div class="col-xs-3 no-padding">
							<span id="TextCostCom-<?php echo $i; ?>">Sin costes</span>
							<input type="number" name="CostCom-<?php echo $i; ?>" id="CostCom-<?php echo $i; ?>" class="form-control form-s com-cost"<?php if($i>1){echo " disabled";} ?> title="Precio" value="<?php echo $firstCost; ?>" step="0.01" style="display:none;" />
						</div>
						<div class="col-xs-1">
						<?php if($i > 1) { ?>
							<i id="delete-product-com-<?php echo $i; ?>" class="fa fa-trash grayStrong pointer deleteCom" title="Eliminar" style="font-size:18px;"></i>
						<?php } ?>
						</div>
						<div class="separator">&nbsp;</div>
						<hr>
					</div>
				<?php } ?>
				</div>
				<div class="col-xs-10">&nbsp;</div>
				<div class="col-xs-2">
					<i id="add-product-com" class="fa fa-plus-circle grayStyrong floatRight pointer" title="Agregar ingrediente" style="font-size:18px;"></i>
				</div>
			</div>
			<div class='row dotted padding-space'>	
				<div class='cp_mnu_subtitle title_block'><span class="textBox grayStrong">Descripción</span></div>
				<div class="form-group">
					<label class="label-field" for="Sumary">Texto en portada:</label>
					<textarea name="Sumary" id="Sumary" class="form-control textarea-field-100x3"></textarea>
					<p id="error-Sumary"></p>
				</div>
				<div class="form-group">
					<label class="label-field" for="Image">Descripción:</label>
					<?php require_once("js/jscripts/tiny_mce/tiny_mce.php"); ?>
					<textarea name='Text' id='Text' class='spl_editable' style="width:100%;"></textarea>
				</div>
			</div>
			<div class='row dotted padding-space'>	
				<div class='cp_mnu_subtitle title_block'><span class="textBox grayStrong">Galería de fotos</span></div>
				<div class="form-group">
					<label class="label-field" for='Image'>Imagenes:</label>
					<input class="form-control form-l" type='file' name='Image[]' id='Image'  multiple="multiple "/>
				</div>
				<div class="form-group">
					<div style="font-style:italic;color:#c00;font-size:11px;margin-left:20%;">
						JPG, GIF o PNG
						<br/>
						Máximo número de archivos simultaneos a subir: <?php echo ini_get('max_file_uploads'); ?>
						<br/>
						Tamaño total máximo de la subida: <?php echo ini_get('post_max_size'); ?>
					</div>
				</div>
			</div>
			
		</div>
		<div class='container container-admin'>
			<div class='row'>	
				<div class='col-md-5'>&nbsp;</div>
					<div class='col-md-2'>
					<img class='image middle' src='images/loading.gif' style='visibility: hidden; padding: 10px 20px 0px 0px;' id='loading'>
				</div>
				<div class='col-md-5'>
					<input class="btn tf-btn btn-default transition floatRight bgGreen white bold" type='submit' name='save' value='CREAR PRODUCTO' />
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
				},
				{
					id: "Supplier",
					type: "selectNumber",
					min: 1,
					max: 9999
				},
				{
					id: "Cost",
					type: "number",
					min: 0,
					max: 99999
				}
			]
		};
		var v2 = new Validation(validation_options);

	</script>
<?php 
}else{
	echo "<p>No tiene permiso para acceder a esta sección.</p>";
}?>	