	
<!-- Page Heading -->
<div class='container'>
	<div class='row'>
		<h1 class="h3 mb-2 text-gray-800">Editar <?php echo $product->TITLE; ?></h1>
		<div class="separator10">&nbsp;</div>
		<div class="separator1 bgYellow">&nbsp;</div>
		<div class="separator20">&nbsp;</div>
		<p class="mb-4"></p>
	</div>
</div>
	
	<form method='post' action='<?php echo DOMAINZP; ?>template/modules/product/edit.php' id='userform' name='userform' enctype='multipart/form-data'>
		<input type="hidden" name="idSup" value="<?php echo $idSup; ?>" />
		<input type="hidden" name="id" value="<?php echo $id; ?>" />
		
		<div class='container bgGrayNormal white'>
			<div class="separator10">&nbsp;</div>
			<div class='form-group'> 
				<label class="label-field white" for="date-day">Fecha/hora publicación:</label>
				<input type="date" class="form-control form-xs" value="<?php echo $datePro->format("Y-m-d"); ?>" name="date_start" id="date_start"  />
				<span class='fecha-hora-min white floatLeft'> | </span>
				<input type='text' class="form-control form-xxs floatLeft" name='Date_start_hh' id='Date_start_hh' size='1' value='<?php echo $datePro->format("H"); ?>' style="max-width:50px;margin-left:10px;margin-right:10px;"/>
				<span class='fecha-hora-min white floatLeft'> : </span>
				<input type='text' class="form-control form-xxs floatLeft" name='Date_start_ii' id='Date_start_ii' size='1' value='<?php echo $datePro->format("i"); ?>' style="max-width:50px;margin-left:10px;margin-right:10px;"/>
			</div>
			<div class="separator20">&nbsp;</div>
			<div class="form-group">
				<label class="label-field white" for="status">Estado:</label>
				<select class="form-control form-s" name="status" id="status" title="Estado">
					<option value='1'<?php if($product->STATUS == 1){ echo ' selected="selected"';} ?>>Activado</option>
					<option value='0'<?php if($product->STATUS == 0){ echo ' selected="selected"';} ?>>Desactivado</option>
				</select>
			</div>
		</div>
		
		<div class="separator50">&nbsp;</div>
		<div class='container'>	
			<div class="form-group">
				<label class="label-field" for="Title">Nombre *:</label>
				<input type="text" name="Title" id="Title" class="form-control form-s" title="Nombre" value="<?php echo $product->TITLE; ?>" placeholder="Nombre *" required />
				<p id="error-Title"></p>
			</div>
			<div class="form-group">
				<label class="label-field" for="Cost">Precio *:</label>
				<input type="number" name="Cost" id="Cost" class="form-control form-xs" title="Precio" value="<?php echo $product->COST; ?>" placeholder="Precio *" step="0.01" required />
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
						<input type="checkbox" name="Category[]" title="Category" value="<?php echo $cat->ID; ?>"<?php for($i=0;$i<count($cats);$i++){if($cats[$i]->ID == $cat->ID){echo " checked";}} ?> />
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
			<div id="wrap-time-frame">
			<?php foreach($comsPro as $comPro) { ?>
				<div id="boxproduct-coms-id-<?php echo $comPro->ID; ?>" class="form-group box-product-coms-id" data="com-on">
					<div class="col-xs-5 no-padding">
						<select name="IdCom-id-<?php echo $comPro->ID; ?>" id="IdCom-id-<?php echo $comPro->ID; ?>" class="form-control form-l com-name-id">
						<?php foreach($coms as $com) { ?>
							<option value="<?php echo $com->ID; ?>" data="<?php echo $com->COST; ?>" <?php for($s=0;$s<count($coms);$s++){if($comPro->IDCOM == $com->ID){echo " selected";break;}} ?>><?php echo $com->TITLE; ?></option>
						<?php } ?>
						</select>	
					</div>
					<div class="col-xs-3 no-padding">
						<select name="TypeCom-id-<?php echo $comPro->ID; ?>" id="TypeCom-id-<?php echo $comPro->ID; ?>" class="form-control form-l com-type-id">
							<option value="basic"<?php if($comPro->TYPE == "basic"){echo " selected";} ?>>Básico</option>
							<option value="optional"<?php if($comPro->TYPE == "optional"){echo " selected";} ?>>Opcional</option>
						</select>	
					</div>
					<div class="col-xs-3 no-padding">
						<span id="TextCostCom-id-<?php echo $comPro->ID; ?>"<?php if($comPro->TYPE == "optional"){echo ' style="display:none;"';} ?>>Sin costes</span>
						<input type="number" name="CostCom-id-<?php echo $comPro->ID; ?>" id="CostCom-id-<?php echo $comPro->ID; ?>" class="form-control form-s com-cost-id" title="Precio" value="<?php echo $comPro->COST; ?>" step="0.01"<?php if($comPro->TYPE != "optional"){echo ' style="display:none;"';} ?> />
					</div>
					<div class="col-xs-1">
						<i id="delete-product-com-id-<?php echo $comPro->ID; ?>" class="fa fa-trash grayStrong pointer deleteComID" title="Eliminar" style="font-size:18px;"></i>
					</div>
					<div class="separator">&nbsp;</div>
					<hr>
				</div>
			<?php } ?>
			<?php for($i=1;$i<=20;$i++) { ?>
				<div id="boxproduct-coms-<?php echo $i; ?>" class="form-group box-product-coms" data="com-off" style='display:none;'>
					<div class="col-xs-5 no-padding">
						<select name="IdCom-<?php echo $i; ?>" id="IdCom-<?php echo $i; ?>" class="form-control form-l com-name" disabled>
							<option value="0" selected>Seleccione un ingrediente</option>
							<?php $cont = 0;
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
						<select name="TypeCom-<?php echo $i; ?>" id="TypeCom-<?php echo $i; ?>" class="form-control form-l com-type" disabled>
							<option value="" selected>Seleccione un tipo</option>
							<option value="basic">Básico</option>
							<option value="optional">Opcional</option>
						</select>	
					</div>
					<div class="col-xs-3 no-padding">
						<span id="TextCostCom-<?php echo $i; ?>">Sin costes</span>
						<input type="number" name="CostCom-<?php echo $i; ?>" id="CostCom-<?php echo $i; ?>" class="form-control form-s com-cost" disabled title="Precio" value="<?php echo $firstCost; ?>" step="0.01" style="display:none;" />
					</div>
					<div class="col-xs-1">
						<i id="delete-product-com-<?php echo $i; ?>" class="fa fa-trash grayStrong pointer deleteCom" title="Eliminar" style="font-size:18px;"></i>
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
				<textarea name="Sumary" id="Sumary" class="form-control textarea-field-100x3"><?php echo $product->SUMARY; ?></textarea>
				<p id="error-Sumary"></p>
			</div>
			<div class="form-group">
				<label class="label-field" for="Image">Descripción:</label>
				<?php require_once("template/vendor/ckeditor/ckeditor.php"); ?>
				<div class='cp_table cp_height300' style='width:100%'>
					<textarea name='Text' id='Text'><?php echo $product->TEXT; ?></textarea>
					<script>
						CKEDITOR.replace( 'Text' );
					</script>
				</div>
			</div>
		</div>
		
		<div class='container bgWhite'>	
			<div class="separator20">&nbsp;</div>
			<label class="label-field green">Imágenes</label>
			<div class="separator1 bgGrayLight">&nbsp;</div>
			<div class="separator10">&nbsp;</div>	
			<?php 
				$image = new Image();
				$image->path = "product";
				$image->pathoriginal = "original";
				$image->paththumb = "thumb";
				foreach($imgs as $row) {
					$urlOriginal = DOMAIN.$image->dirbasename.$image->path."/".$image->pathoriginal."/".$row->URL;
					$urlThumb = $image->dirView.$image->path."/".$image->paththumb."/1-".$row->URL;
			?>
					<div id ="wrap-image-<?php echo $row->ID; ?>" class="form-group <?php if ($row->FAV == 1) {echo "darkshaded";}else{echo "shaded";} ?>">
						<div class="col-xs-3">
							<a href='<?php echo $urlOriginal; ?>' class='lytebox' data-lyte-options='group:<?php echo $product->ID; ?>'>
								<img src="<?php echo $urlThumb; ?>" style="max-width:60%;margin:10px;" />
							</a>
						</div>
						<div class="col-xs-7">
							<div class="cp_box">
								<label class="label-field-small" for='title-img-<?php echo $row->ID; ?>'<?php if($row->FAV == 1){echo ' style="color:#ededed;"';} ?>>Título: </label>
								<input class="form-control form-l" type="text" name="title-img-<?php echo $row->ID; ?>" id="title-img-<?php echo $row->ID; ?>" value="<?php echo $row->TITLE; ?>" />
							</div>
							<div class="separator10">&nbsp;</div>
							<div class="cp_box">
								<div class="col-xs-4">
									<label class="label-field-big" for='position-<?php echo $row->ID; ?>' style="<?php if($row->FAV == 1){echo 'color:#ededed;';} ?>float: left;">Posición:</label>
									<input class="position-img-product form-control form-xs" type='number' name='position-<?php echo $row->ID; ?>' id='position-<?php echo $row->ID; ?>' title='Posición del producto' value='<?php echo $row->POSITION; ?>' style="width:50px;height:30px;padding:5px;" />
								</div>
								<div class="col-xs-8">
									<label class="label-field-big" for='fav-<?php echo $row->ID; ?>' style="<?php if($row->FAV == 1){echo 'color:#ededed;';} ?>">Imagen destacada:</label>
									<input class="position-img-product form-control form-xs" type='radio' name='fav' id='fav-<?php echo $row->ID; ?>' value='<?php echo $row->ID; ?>' title='Imagen destacada'<?php if($row->FAV == 1){echo ' checked';} ?> />
								</div>
							</div>
						</div>
						<div class='col-xs-2 top'>
							<div class="separator10">&nbsp;</div>
							<input type="hidden" name="act-img-<?php echo $row->ID; ?>" id="act-img-<?php echo $row->ID; ?>" value="0" />
							<i id="delete-img-<?php echo $row->ID; ?>" class="fa fa-trash pointer deleteImage floatRight<?php if($row->FAV == 1){echo ' grayLight';}else{echo ' grayStrong';} ?>" title="Eliminar imagen" style="font-size:20px;"></i>
							<div class="separator10">&nbsp;</div>
						</div>
						<div class="separator10"row>&nbsp;</div>
					</div>
					<div id="separator-img-<?php echo $row->ID; ?>" class="separator10" style="border-bottom:1px solid #999;">&nbsp;</div>
					<div class="separator10"row>&nbsp;</div>
			<?php 
				} 
			?>
			<div class='row padding-space'>	
				<div class="form-group">
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