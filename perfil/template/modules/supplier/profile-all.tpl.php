	
<!-- Page Heading -->
<div class='container'>
	<div class='row'>
		<h1 class="h3 mb-2 text-gray-800">Editar <?php echo $supplier->TITLE; ?></h1>
		<div class="separator10">&nbsp;</div>
		<div class="separator1 bgYellow">&nbsp;</div>
		<div class="separator20">&nbsp;</div>
		<p class="mb-4"></p>
	</div>
</div>
	
<form method='post' action='<?php echo DOMAINZP; ?>template/modules/supplier/edit.php' id='userform' name='userform' enctype='multipart/form-data'>
	<input type="hidden" name="id" value="<?php echo $sup; ?>" />
	<input type="hidden" name="idZone" value="<?php echo $idZone; ?>" />
	<div class='container bgGrayNormal white'>
		<div class="separator10">&nbsp;</div>
		<div class="form-group">
			<label class="label-field white" for="status">Estado:</label>
			<select class="form-control form-s" name="status" id="status" title="Estado">
				<option value='1'<?php if($supplier->STATUS == 1){ echo ' selected="selected"';} ?>>Activado</option>
				<option value='2'<?php if($supplier->STATUS == 2){ echo ' selected="selected"';} ?>>No disponible</option>
			</select>
		</div>
	</div>
	
	<div class="separator50">&nbsp;</div>
	<div class='container'>	
		<div class="form-group">
			<label class="label-field" for="Title">Nombre *:</label>
			<input type="text" name="Title" id="Title" class="form-control form-s" title="Nombre" value="<?php echo $supplier->TITLE; ?>" value="Nombre *" />
			<p id="error-Title"></p>
		</div>
		<div class="form-group">
			<label class="label-field" for="Proveedor">Proveedor *:</label>
			<select class="form-control form-s" name="Proveedor" id="Proveedor" title="Proveedor"> 
			<?php foreach($userSup as $us) { ?>
				<option value="<?php echo $us->ID; ?>"<?php if($us->ID == $idPro[0]){ echo ' selected';} ?>>
					<?php echo $us->SURNAME.", ".$us->NAME; ?>
				</option>
			<?php } ?>
			</select>
		</div>
		<div class="form-group">
			<label class="label-field" for="Phone">Teléfono:</label>
			<input type="phone" class="form-control form-xs" name="Phone" id="Phone" title="Teléfono" value="<?php echo $supplier->PHONE; ?>" placeholder="Teléfono" />
			<p id="error-Phone"></p>
		</div>
		<div class="form-group">
			<label class="label-field" for="Movil">Móvil:</label>
			<input type="phone" class="form-control form-xs" name="Movil" id="Movil" title="Móvil" value="<?php echo $supplier->MOVIL; ?>" placeholder="Móvil" />
			<p id="error-Phone"></p>
		</div>
		<div class="form-group">
			<label class="label-field" for="Cost">Gastos de envío *:</label>
			<input type="number" name="Cost" id="Cost" class="form-control form-xs" title="Gastos de envio" value="<?php echo $supplier->COST; ?>" step="0.01" required />
			<p id="error-Cost"></p>
		</div>
		<div class="form-group">
			<label class="label-field" for="Min">Pedido mínimo *:</label>
			<input type="number" name="Min" id="Min" class="form-control form-xs" title="Pedido mínimo" value="<?php echo $supplier->MIN; ?>" step="0.01" required />
			<p id="error-Cost"></p>
		</div>
		<div class="form-group">
			<label class="label-field" for="Min">Tiempo para pedidos *:</label>
			<input type="number" name="Time" id="Time" class="form-control form-xs" title="Tiempo para pedidos" value="<?php echo $supplier->TIME; ?>" required />
			<p id="error-Time"></p>
		</div>
		<div class="form-group">
			<label class="label-field" for="Extra">Extra pedidos por franja *:</label>
			<input type="number" name="Extra" id="Extra" class="form-control form-xs" title="Extra pedidos por franja" placeholder="Extra pedidos por franja *" value="<?php echo $supplier->EXTRA_ORDER; ?>" step="1" required />
			<p id="error-Extra"></p>
		</div>
		<div class="form-group">
			<label class="label-field" for="Title">ID Telegram *:</label>
			<input type="text" name="IDTelegram" id="IDTelegram" class="form-control form-xs" title="ID Telegram" value="<?php echo $supplier->IDTELEGRAM; ?>" required />
			<p id="error-IDTelegram"></p>
		</div>
		<div class="separator30">&nbsp;</div>
		<div class='container bgWhite'>	
			<div class="separator20">&nbsp;</div>
			<label class="label-field green">ZONAS DE REPARTO</label>
			<div class="separator1 bgGrayLight">&nbsp;</div>
			<div class="separator10">&nbsp;</div>
			<?php if(count($address) == 0) { ?>
				<div class="form-group">
					<label class="label-field orange" style="width:100%;"><i class="fa fa-exclamation-triangle orange iconBotton"></i> Para crear una zona de reparto primero tienes que guardar el proveedor.</label>
				</div>
				<?php }else{ ?>
					<div class='row'>
						<div class='col-xs-3 cp_title top'>
							<div class='bold textLeft'><h5 class='textLeft'>Zona</h5></div>
						</div>
						<div class='col-xs-3 cp_title top'>
							<div class='bold textLeft'><h5 class='textLeft'>Día de la semana</h5></div>
						</div>
						<div class='col-xs-1 cp_title top'>
							<div class='bold textCenter'><h5 class='textLeft'>Inicio</h5></div>
						</div>
						<div class='col-xs-1 cp_title top'>
							<div class='bold textLeft'>&nbsp;</div>
						</div>
						<div class='col-xs-1 cp_title top'>
							<div class='bold textCenter'><h5 class='textLeft'>Fin</h5></div>
						</div>
						<div class='col-xs-3 cp_title top'>
							<div class='bold textLeft'>&nbsp;</div>
						</div>
					</div>
					<div class="separator5">&nbsp;</div>
					<div class="separator1 bgGrayNormal">&nbsp;</div>
					<div class="separator10">&nbsp;</div>
					<?php foreach($address as $item){ 
						
							$zoneItem = $zObj->infoById($item->IDZONE);
							$timeControl = $supObj->supplierTimeControlZone($sup, $item->IDZONE);
					?>
							<div class='row'>
								<div class='col-xs-3'>
									<p class='textLeft <?php if($item->ACTIVE == 0){echo "danger";}else{echo "green";} ?>'><?php echo $zoneItem->CITY; ?></p>
								</div>
								<div class='col-xs-3'>
									<?php foreach($timeControl as $time){ ?>
										<p class='textLeft <?php if($item->ACTIVE == 0){echo "danger";}else{echo "green";} ?>'><?php echo $days[$time->DAY-1]; ?></p>
									<?php } ?>
								</div>
								<div class='col-xs-1'>
									<?php foreach($timeControl as $time){ 
										if(strlen($time->START_H) == 1) {
											$time->START_H = "0". $time->START_H;
										}
										if(strlen($time->START_M) == 1) {
											$time->START_M = "0". $time->START_M;
										}	
									?>
										<p class='textCenter <?php if($item->ACTIVE == 0){echo "danger";}else{echo "green";} ?>'><?php echo $time->START_H . ":" . $time->START_M; ?></p>
									<?php } ?>
								</div>
								<div class='col-xs-1'>
									<div class='textLeft'>&nbsp;</div>
								</div>
								<div class='col-xs-1'>
									<?php foreach($timeControl as $time){ 
										if(strlen($time->FINISH_H) == 1) {
											$time->FINISH_H = "0". $time->FINISH_H;
										}
										if(strlen($time->FINISH_M) == 1) {
											$time->FINISH_M = "0". $time->FINISH_M;
										}
									?>
										<p class='textCenter <?php if($item->ACTIVE == 0){echo "danger";}else{echo "green";} ?>'><?php echo $time->FINISH_H . ":" . $time->FINISH_M; ?></p>
									<?php } ?>
								</div>
						<?php if($zObj->isUserWebZone($item->IDZONE, $_SESSION[nameSessionZP])){ ?>
								<div class='col-xs-3 top'>
									<div class='col-xs-6 textCenter'>
										<?php $urledit = "?&view=supplier&mod=supplier&tpl=delivery&action=edit&sup=".$sup."&z=".$item->IDZONE; ?>
										<a href="<?php echo DOMAINZP. $urledit; ?>" title="Editar zona de reparto">
											<i class="fa fa-edit pointer iconBotton transition"></i>
										</a>
									</div>
									<div class='col-xs-6 textCenter'>
										<?php 
											$identificador = "zonareparto_".$item->ID;
											$urlAlert = DOMAINZP."template/modules/supplier/delete.delivery.php?view=".$view."&mod=".$mod."&tpl=".$tpl."&supplier=".$sup."&zone=".$item->IDZONE;
											$textAlert = "¡ATENCI&Oacute;N!";
											$textAlert2 = "Va a eliminar la zona de reparto ".$zoneItem->CITY."-".$zoneItem->CP.". &iquest;Est&aacute; seguro?";
										?>
											<i id="btn-action-open-<?php echo $identificador; ?>" class="fa fa-trash grayStrong pointer iconBotton transition btn-action-open" title='Eliminar zona de reparto'></i>
											<?php include("template/modules/alert/btn.action.tpl.php"); ?>
									</div>
								</div>
						<?php }else{ ?>
								<div class='col-xs-3 top'>
									<div class='col-xs-6 textCenter'>
										<i class="fa fa-edit iconBotton grayLight" title="No tiene permisos para realizar esta acción"></i>
									</div>
									<div class='col-xs-6 textCenter'>
										<i class="fa fa-trash grayStrong iconBotton grayLight" title="No tiene permisos para realizar esta acción"></i>
									</div>
								</div>
						<?php } ?>
							</div>
							<div class="separator1 bgGrayLight">&nbsp;</div>
							<div class="separator10">&nbsp;</div>
				<?php 	
						} 
					} 
				$urlCreate = "?&view=supplier&mod=supplier&tpl=delivery&action=create&sup=".$sup;
			?>
			<div class="separator30">&nbsp;</div>
			<a href='<?php echo DOMAINZP.$urlCreate; ?>' class="btn btn-primary transition floatRight bgGreen yellow" type="button" id="">
				Crear zona de reparto
			</a>
			<div class="separator20">&nbsp;</div>
		</div>
		<div class="separator50">&nbsp;</div>
		
		
		<div class='container bgWhite'>	
			<div class="separator20">&nbsp;</div>
			<label class="label-field green">CATEGORIAS</label>
			<div class="separator1 bgGrayLight">&nbsp;</div>
			<div class="separator10">&nbsp;</div>
			<div class="form-group">
			<?php foreach($categories as $cat) { 
					$catSelected = false;
					for($i=0;$i<count($cats);$i++){
						if($cats[$i]->IDCAT == $cat->ID){
							$catSelected = true;
							break;
						}
					}
			?>	
				<div class="col-md-4 col-xs-6 col-sm-12">
					<input type="checkbox" name="Category[]" title="Category" value="<?php echo $cat->ID; ?>"<?php if($catSelected){echo " checked";} ?> />
					<label for="Category" class="<?php if($catSelected){echo "green";} ?>"><?php echo $cat->TITLE; ?></label>
				</div>
			<?php } ?>	
			</div>
		</div>
	<div class="separator30">&nbsp;</div>
	<div class='container bgWhite'>	
		<div class="separator20">&nbsp;</div>
		<label class="label-field green">DISEÑO</label>
		<div class="separator1 bgGrayLight">&nbsp;</div>
		<div class="separator10">&nbsp;</div>
		<div class="form-group">
			<label class="label-field" for="Name">Eslogan:</label>
			<input type="text" name="Eslogan" id="Eslogan" class="form-control form-m" title="Eslogan" placeholder="Eslogan" value="<?php echo $supplier->ESLOGAN; ?>" />
			<p id="error-Name"></p>
		</div>
		<div class="form-group">
			<label class="label-field" for="View_img">Ver imágenes de productos:</label>
			<select class="form-control form-xs" name="View_img" id="View_img" title="Ver imágenes de productos en los listados"> 
				<option value="0"<?php if($supplier->VIEW_IMG == 0){echo " selected";} ?>>No</option>
				<option value="1"<?php if($supplier->VIEW_IMG == 1){echo " selected";} ?>>Si</option>
			</select>
			<p id="error-View_img"></p>
		</div>
		<div class="form-group">
			<label class="label-field" for="Text">Descripción:</label>
			<?php require_once("template/vendor/ckeditor/ckeditor.php"); ?>
			<div class='cp_table cp_height300' style='width:100%'>
				<textarea name='Text' id='Text'><?php echo $supplier->TEXT; ?></textarea>
				<script>
					CKEDITOR.replace( 'Text' );
				</script>
			</div>
		</div>
		<div class="form-group">
			<label class="label-field" for="Logo">Logo:</label>
		<?php if($supplier->LOGO != ""): 
				$imgLogo = new Image();
				$imgLogo->path = "supplier";
				$imgLogo->pathoriginal = "original";
				$imgLogo->paththumb = "thumb";
		?>
			<div id="wrap-logo" class="col-sm-3">
				<a href='<?php echo DOMAIN.$imgLogo->dirbasename.$imgLogo->path."/".$imgLogo->pathoriginal."/".$supplier->LOGO; ?>' class='lytebox' data-lyte-options='group:<?php echo $supplier->ID; ?>'>
					<img src="<?php echo $imgLogo->dirView.$imgLogo->path."/".$imgLogo->paththumb."/1-".$supplier->LOGO; ?>" style="max-width:100px;margin-bottom:10px;" />
				</a>
			</div>
			<div class="col-sm-5">
				<label for="Logo" style="float:none;">Modificar: </label>
		<?php else: ?>
			<div class="col-sm-8">
		<?php endif; ?>
				<br/>
				<input class="form-control form-l" type="file" name="Logo" id="Logo" style="float:none;" />
				<div class="form-group" style="margin:0px;">
					<div style="font-style:italic;color:#c00;font-size:11px;">
						Dimensiones optimas del logo 300 x 300px
					</div>
				</div>
				<div class="separator10">&nbsp;</div>
				<input type="hidden" name="action-logo" id="action-logo" value="0" />
		<?php if($supplier->LOGO != ""): ?>
				<button class="btn btn-primary transition floatLeft grayStrong delete-img" type="button" id="delete-logo">Eliminar logo</button>
		<?php endif; ?>
			</div>
		</div>
		<div class="separator20" style="border-bottom:1px solid #999;">&nbsp;</div>
		<div class="separator20">&nbsp;</div>
		<div class="form-group">
			<label class="label-field" for="Image">Imagen:</label>
		<?php if($supplier->IMAGE != ""): 
				$image = new Image();
				$image->path = "supplier";
				$image->pathoriginal = "original";
				$image->paththumb = "thumb";
		?>
			<div id="wrap-image" class="col-sm-3">
				<a href='<?php echo DOMAIN.$image->dirbasename.$image->path."/".$image->pathoriginal."/".$supplier->IMAGE; ?>' class='lytebox' data-lyte-options='group:<?php echo $supplier->ID; ?>'>
					<img src="<?php echo $image->dirView.$image->path."/".$image->paththumb."/1-".$supplier->IMAGE; ?>" style="max-width:100px;margin-bottom:10px;" />
				</a>
			</div>
			<div class="col-sm-6">
				<label for="Image" style="float:none;">Modificar: </label>
		<?php else: ?>
			<div class="col-sm-8">
		<?php endif; ?>
				<br/>
				<input class="form-control form-l" type="file" name="Image" id="Image" style="float:none;" />
				<input type="hidden" name="action-image" id="action-image" value="0" />
				<div class="separator10">&nbsp;</div>
		<?php if($supplier->IMAGE != ""): ?>
				<button class="btn btn-primary transition floatLeft bgYellow grayStrong delete-img" type="button" id="delete-image">Eliminar imagen</button>
		<?php endif; ?>
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