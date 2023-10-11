
<!-- Page Heading -->
<div class='container'>
	<div class='row'>
		<h1 class="h3 mb-2 text-gray-800">Nuevo producto - <?php echo $supBD->TITLE; ?></h1>
		<div class="separator10">&nbsp;</div>
		<div class="separator1 bgYellow">&nbsp;</div>
		<div class="separator20">&nbsp;</div>
		<p class="mb-4"></p>
	</div>
</div>
	
	<form method='post' action='<?php echo DOMAINZP; ?>template/modules/product/create.php' id='userform' name='userform' enctype='multipart/form-data'>
		<input type="hidden" name="Supplier" value="<?php echo $idSup; ?>" />
		<div class='container bgGrayNormal white'>
			<div class="separator10">&nbsp;</div>
			<div class='form-group'> 
				<label class="label-field white" for="date-day">Fecha/hora publicación:</label>
				<input type="date" class="form-control form-xs" value="<?php echo $dateNow->format("Y-m-d"); ?>" name="date_start" id="date_start"  />
				<span class='fecha-hora-min white floatLeft'> | </span>
				<input type='text' class="form-control form-xxs floatLeft" name='Date_start_hh' id='Date_start_hh' size='1' value='<?php echo $dateNow->format("H"); ?>' style="max-width:50px;margin-left:10px;margin-right:10px;"/>
				<span class='fecha-hora-min white floatLeft'> : </span>
				<input type='text' class="form-control form-xxs floatLeft" name='Date_start_ii' id='Date_start_ii' size='1' value='<?php echo $dateNow->format("i"); ?>' style="max-width:50px;margin-left:10px;margin-right:10px;"/>
			</div>
			<div class="separator20">&nbsp;</div>
			<div class="form-group">
				<label class="label-field white" for="status">Estado:</label>
				<select class="form-control form-s" name="status" id="status" title="Estado">
					<option value='1'>Activado</option>
					<option value='0' selected>Desactivado</option>
				</select>
			</div>
		</div>
		
		<div class="separator50">&nbsp;</div>
		<div class='container'>	
			<div class="form-group">
				<label class="label-field" for="Title">Nombre *:</label>
				<input type="text" name="Title" id="Title" class="form-control form-s" title="Nombre" placeholder="Nombre *" required />
				<p id="error-Title"></p>
			</div>
			<div class="form-group">
				<label class="label-field" for="Cost">Precio *:</label>
				<input type="number" name="Cost" id="Cost" class="form-control form-xs" title="Precio" placeholder="Precio *" step="0.01" required />
				<p id="error-Cost"></p>
			</div>
		</div>
		<div class="separator50">&nbsp;</div>
		<div class='container bgWhite'>	
			<div class="separator20">&nbsp;</div>
			<div class="form-group">
				<label class="label-field green">Categorias</label>
				<div class="separator1 bgGrayLight">&nbsp;</div>
				<div class="separator10">&nbsp;</div>
				<?php foreach($categories as $cat) { ?>	
					<div class="col-md-4 col-xs-6 col-sm-12">
						<input type="checkbox" name="Category[]" title="Category" value="<?php echo $cat->ID; ?>" />
						<label for="Category" class="perfilText grayNormal"><?php echo $cat->TITLE; ?></label>
					</div>
				<?php } ?>	
			</div>
			<div class="separator20">&nbsp;</div>
		</div>

		<div class="separator30">&nbsp;</div>
		<div class='container bgWhite'>	
			<div class="separator20">&nbsp;</div>
			<label class="label-field green">Ingredientes</label>
			<div class="separator1 bgGrayLight">&nbsp;</div>
			<div class="separator10">&nbsp;</div>	
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
						<?php $cont++;
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
		<div class="separator50">&nbsp;</div>
		<div class='container bgWhite'>	
			<div class="separator20">&nbsp;</div>
			<label class="label-field green" for="sumary">Descripción</label>
			<div class="separator1 bgGrayLight">&nbsp;</div>
			<div class="separator10">&nbsp;</div>	
			<div class="form-group">
				<label class="label-field" for="Sumary">Texto en portada:</label>
				<textarea name="Sumary" id="Sumary" class="form-control textarea-field-100x3"></textarea>
				<p id="error-Sumary"></p>
			</div>
			<div class="form-group">
				<label class="label-field" for="Image">Descripción:</label>
				<?php require_once("template/vendor/ckeditor/ckeditor.php"); ?>
				<div class='cp_table cp_height300' style='width:100%'>
					<textarea name='Text' id='Text'></textarea>
					<script>
						CKEDITOR.replace( 'Text' );
					</script>
				</div>
			</div>
		</div>
		<div class="separator50">&nbsp;</div>
		<div class='container bgWhite'>	
			<div class="separator20">&nbsp;</div>
			<label class="label-field green">Imágenes</label>
			<div class="separator1 bgGrayLight">&nbsp;</div>
			<div class="separator10">&nbsp;</div>	
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
		<div class="separator20">&nbsp;</div>
		<div class="separator1 bgGrayNormal">&nbsp;</div>
		<div class="separator20">&nbsp;</div>
		<div class='container'>
			<button class="btn btn-primary transition floatRight bgGreen yellow" type='submit'>GUARDAR</button>
		</div>
	</form>
	<div class="separator50">&nbsp;</div>
	<script type="text/javascript">
	//Validacion del formulario		
		var validation_options = {
			form: document.getElementById("userform"),
			fields: [
				{
					id: "Name",
					type: "string",
					min: 2,
					max: 256
				},
				{
					id: "Surname",
					type: "string",
					min: 2,
					max: 256
				}
			]
		};
		var v2 = new Validation(validation_options);

	</script>