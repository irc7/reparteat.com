<?php if (allowed($mnu)) : ?>
	<div class='cp_mnu_title title_header_mod'>Nuevo Banner publicidad</div>
	<?php
		if (isset($_GET['msg'])) {
			$msg = utf8_encode($_GET['msg']);
			echo "<div class='cp_info'><img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' /><p>".$msg."</p></div>\r\n";
		}
		require_once("includes/classes/Zone/class.Zone.php");
		require_once("includes/classes/Image/class.Image.php");
		require_once("includes/classes/Publicity/class.Publicity.php");
		require_once("includes/classes/Publicity/class.PublicityHook.php");
		
		if (isset($_GET['id']) && intval($_GET['id']) > 0) {
			$id = intval($_GET['id']);
		}else {
			$location = "Location: ../index.php?mnu=".$mnu."&com=".$com."&tpl=create";
			header($location);
		}	
		$zoneObj = new Zone();
		$itemObj = new Publicity();
		$hookObj = new PublicityHook();
		$hooks = array(); 
		$hooks = $hookObj->listHook(); 
		$zones = array(); 
		$zones = $zoneObj->allZones(); 
		
		$item = $itemObj->infoPublicityById($id);
		
	?>	
	<div class='cp_alert noerror' id='info-Name'></div>
	<form method='post' action='modules/publicity/edit.php' enctype='multipart/form-data' id='mainform' name='mainform'>
		<input type="hidden" name="mnu" value="<?php echo $mnu; ?>" />
		<input type="hidden" name="com" value="<?php echo $com; ?>" />
		<input type="hidden" name="tpl" value="<?php echo $tpl; ?>" />
		<input type="hidden" name="opt" value="<?php echo $opt; ?>" />
		<input type="hidden" name="id" value="<?php echo $id; ?>" />
		<div class='container container-admin darkshaded-space bgGrayNormal white'>
			<div class="separator10">&nbsp;</div>	
			<div class="separator10">&nbsp;</div>	
			<div class="col-md-7 col-sm-12">
				<div class="form-group">
					<label for='Type' class="label-field white">Mostrar:</label>
					<select name='Type' id='Type' class="form-control form-l">						
						<option value='image'<?php if($item->TYPE == "image"){echo " selected='selected'";} ?>>Imagen</option>
						<option value='text'<?php if($item->TYPE == "text"){echo " selected='selected'";} ?>>Texto</option>
						<option value='all'<?php if($item->TYPE == "all"){echo " selected='selected'";} ?>>Ambos</option>
					</select>
				</div>
			</div>
			<div class="col-md-5 col-sm-12">
				<div class="form-group">
					<label for='Status' class="label-field white">Estado:</label>
					<select name='Status' id='Status' class="form-control form-s">						
					<option value='1'<?php if($item->STATUS == 1){echo " selected='selected'";} ?>>Publicado</option>
					<option value='0'<?php if($item->STATUS == 0){echo " selected='selected'";} ?>>Borrador</option>
					</select>
				</div>
			</div>
		</div>
		<div class="separator30">&nbsp;</div>	
		<div class='container container-admin'>
			<div class='row'>	
				<div class="form-group">
					<label class="label-field" for="Title">Texto destacado *:</label>
					<input type="text" name="Title" id="Title" class="form-control form-s" title="Título" value="<?php echo $item->TITLE; ?>" placeholder="Título *" required />
					<p id="error-Title"></p>
				</div>
				<div class="form-group">
					<label class="label-field" for="Subtitle">Texto secundario:<br/><span>Max 128 caracteres</span></label>
					<input type="text" name="Subtitle" id="Subtitle" class="form-control form-s" title="Subtitle" value="<?php echo $item->SUBTITLE; ?>" placeholder="Subtitle" />
					<p id="error-Subtitle"></p>
				</div>
				<div class="separator1"></div>
				
				<div class="col-md-6 col-sm-12 bgGrayLight">
					<div class="separator10"></div>
					<div class="form-group">
						<label class="label-field" for="Hook" style="width:100%">Zonas de visualización *:</label>
							<select name="Hook[]" id="Hook" class="form-control form-l" multiple style="min-height:200px;">
							<?php foreach($hooks as $h){ 
								$selected = $itemObj->checkingHook($h->ID, $item->ID);
								?>
								<option value="<?php echo $h->ID; ?>" <?php if($selected){echo "selected";} ?>><?php echo $h->TITLE; ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="separator1"></div>
				</div>
				<div class="col-md-6 col-sm-12 bgGrayLight">
					<div class="separator10"></div>
					<div class="form-group">
						<label class="label-field" for="Zone" style="width:100%">Zonas de reparto *:</label>
							<select name="Zone[]" id="Zone" class="form-control form-l" multiple style="min-height:200px;">
							<?php foreach($zones as $z){ 
									$selected = $itemObj->checkingZone($z->ID, $item->ID);
								?>
								<option value="<?php echo $z->ID; ?>" <?php if($selected){echo "selected";} ?>><?php echo $z->CITY ." - ". $z->CP; ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="separator1"></div>
				</div>
				<div class="separator1"></div>
				<div class='container container-admin darkshaded-space bgGrayLight white'>
					<div class="form-group">
						<div class="separator20">&nbsp;</div>
						<div class="white bgGrayStrong bold" style="font-size:14px;padding:10px;border-radius:5px;">Imagen:</div>
						<div class="separator30">&nbsp;</div>
						<?php if($item->IMAGE != ""): 
							$image = new Image();
							$image->path = "publicity";
							$image->paththumb = "image";
					?>
						<div id="wrap-image" class="col-sm-12">
							<a href='<?php echo DOMAIN.$image->dirbasename.$image->path."/".$image->paththumb."/1-".$item->IMAGE; ?>' class='lytebox' data-lyte-options='group:<?php echo $item->ID; ?>'>
								<img src="<?php echo $image->dirView.$image->path."/".$image->paththumb."/1-".$item->IMAGE; ?>" style="max-width:100%;margin-bottom:10px;" />
							</a>
						</div>
						<div class="col-sm-12">
							<label class="label-field" for="Image" style="float:none;">Modificar: </label>
					<?php else: ?>
						<div class="col-sm-12">
					<?php endif; ?>
							<br/>
							<input class="form-control form-l" type="file" name="Image" id="Image" style="float:none;" />
							<div class="form-group" style="margin:0px;">
								<div style="font-style:italic;color:#c00;font-size:11px;">
									<?php foreach($hooks as $h){ ?>
										Dimensiones optimas de la imagen <?php echo $h->WIDTH; ?> x <?php echo $h->HEIGHT; ?> px (<?php echo $h->TITLE; ?>)<br/>
									<?php } ?>
								</div>
							</div>
							<div class="separator10">&nbsp;</div>
							<input type="hidden" name="action-image" id="action-image" value="0" />
					<?php if($item->IMAGE != ""): ?>
							<button class="btn btn-default transition floatLeft bgGreen white bold delete-img" type="button" id="delete-image">Eliminar</button>
					<?php endif; ?>
						</div>
					</div>
					<div class="separator10">&nbsp;</div>
				</div>
				<div class="separator20" style="border-bottom:1px solid #999;">&nbsp;</div>
				<div class="separator20">&nbsp;</div>
				<div class='container container-admin darkshaded-space bgGrayLight white'>
					<div class="form-group">
						<div class="separator20">&nbsp;</div>
						<div class="white bgGrayStrong bold" style="font-size:14px;padding:10px;border-radius:5px;">Imagen versión móvil:</div>
						<div class="separator30">&nbsp;</div>
					<?php if($item->IMAGE_MOBILE != ""): 
							$imgM = new Image();
							$imgM->path = "publicity";
							$imgM->paththumb = "mobile";
					?>
						<div id="wrap-image_mobile" class="col-sm-3">
							<a href='<?php echo DOMAIN.$imgM->dirbasename.$imgM->path."/".$imgM->paththumb."/1-".$item->IMAGE_MOBILE; ?>' class='lytebox' data-lyte-options='group:<?php echo $item->ID; ?>'>
								<img src="<?php echo $imgM->dirView.$imgM->path."/".$imgM->paththumb."/1-".$item->IMAGE_MOBILE; ?>" style="max-width:100%;margin-bottom:10px;" />
							</a>
						</div>
						<div class="col-sm-5">
							<label for="ImageMobile" style="float:none;">Modificar: </label>
					<?php else: ?>
						<div class="col-sm-8">
					<?php endif; ?>
							<br/>
							<input class="form-control form-l" type="file" name="ImageMobile" id="ImageMobile" style="float:none;" />
							<div class="form-group" style="margin:0px;">
								<div style="font-style:italic;color:#c00;font-size:11px;">
									<?php foreach($hooks as $h){ ?>
										Dimensiones optimas de la imagen <?php echo $h->WIDTH_MOBILE; ?> x <?php echo $h->HEIGHT_MOBILE; ?> px (<?php echo $h->TITLE; ?>)<br/>
									<?php } ?>
								</div>
							</div>
							<div class="separator10">&nbsp;</div>
							<input type="hidden" name="action-image_mobile" id="action-image_mobile" value="0" />
					<?php if($item->IMAGE_MOBILE != ""): ?>
							<button class="btn tf-btn btn-default transition floatLeft bgGreen white bold delete-img" type="button" id="delete-image_mobile">Eliminar</button>
					<?php endif; ?>
						</div>
					</div>
					<div class="separator10">&nbsp;</div>
				</div>

				<div class="form-group">
					<label class="label-field" for="Title">Texto:</label>
					<?php require_once("js/ckeditor/ckeditor.php"); ?>
					<div class='cp_table cp_height300' style='width:100%'>
						<textarea name='Text' id='Text'><?php echo $item->TEXT; ?></textarea>
						<script>
							CKEDITOR.replace( 'Text' );
						</script>
					</div>
					<div class="separator10"></div>
				</div>
				<div class='container container-admin darkshaded-space bgGrayLight white'>
					<div class="separator10">&nbsp;</div>	
					<div class="form-group">
						<label class="label-field" for="Link">Enlace</label>
						<input type="text" name="Link" id="Link" class="form-control form-m" title="Enlace" value="<?php echo $item->LINK; ?>" placeholder="https://" />
						<p id="error-Subtitle"></p>
					</div>
					<div class="form-group">
						<label class="label-field" for="Target">Abrir en: </label>
						<select name="Target" id="Target" class="form-control form-s">
							<option value="_self" <?php if($item->TARGET == "_self"){echo " selected='selected'";} ?>>Misma pestaña</option>
							<option value="_blank" <?php if($item->TARGET == "_blank"){echo " selected='selected'";} ?>>Nueva pestaña</option>
						</select>
					</div>
					<div class="separator10">&nbsp;</div>	
				</div>
				<div class="separator20"></div>
				<div class="separator20"></div>
				<div class="separator1 bgGrayNormal"></div>
				<div class="separator20"></div>
				<div class="row">	
					<div class="col-md-5">&nbsp;</div>
						<div class="col-md-2">
						<img class="image middle" src="images/loading.gif" style="visibility: hidden; padding: 10px 20px 0px 0px;" id="loading">
					</div>
					<div class="col-md-5">
						<input class="btn tf-btn btn-default transition floatRight bgGreen white bold" type="submit" name="save" value="GUARDAR" onclick='showloading(1);validate();return false;'>
					</div>
				</div>
			</div>
		</div>
	</form>
	<script type='text/javascript'>
		includeField('Title','string');	
	</script>
<?php else: ?>
	<p>No tiene permiso para acceder a esta sección.</p>
<?php endif; ?>	

