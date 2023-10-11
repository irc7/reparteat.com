<?php if (allowed($mnu)) : ?>
	<div class='cp_mnu_title title_header_mod'>Nuevo Banner publicidad</div>
	<?php
		if (isset($_GET['msg'])) {
			$msg = utf8_encode($_GET['msg']);
			echo "<div class='cp_info'><img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' /><p>".$msg."</p></div>\r\n";
		}
		require_once("includes/classes/Image/class.Image.php");
		require_once("includes/classes/Multislide/class.Multislide.php");
		require_once("includes/classes/Multislide/class.MultislideHook.php");
		
		if (isset($_GET['id']) && intval($_GET['id']) > 0) {
			$id = intval($_GET['id']);
		}else {
			$location = "Location: ../index.php?mnu=".$mnu."&com=".$com."&tpl=create";
			header($location);
		}	
		
		$itemObj = new Multislide();
		$hookObj = new MultislideHook();
		$hooks = array(); 
		$hooks = $hookObj->listHook(); 
		
		$item = $itemObj->infoMultislideById($id);
		
	?>	
	<div class='cp_alert noerror' id='info-Name'></div>
	<form method='post' action='modules/multislide/edit.php' enctype='multipart/form-data' id='mainform' name='mainform'>
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
						<option value='video'<?php if($item->TYPE == "video"){echo " selected='selected'";} ?>>Video</option>
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
				<div class="form-group" style="display:none;">
					<label class="label-field" for="Subtitle">Texto secundario:<br/><span>Max 128 caracteres</span></label>
					<input type="text" name="Subtitle" id="Subtitle" class="form-control form-s" title="Subtitle" value="<?php echo $item->SUBTITLE; ?>" placeholder="Subtitle" />
					<p id="error-Subtitle"></p>
				</div>
				<div class="separator10"></div>
				<div class="form-group">
					<label class="label-field" for="idHook">Zonas de visualización *:</label>
						<select name="idHook" id="idHook" class="form-control form-s">
						<?php foreach($hooks as $h){ ?>
							<option value="<?php echo $h->ID; ?>" <?php if($h->ID == $item->IDHOOK){echo "selected";} ?>><?php echo $h->TITLE; ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="separator10"></div>
				<div class='container container-admin darkshaded-space bgGrayLight white'>
					<div class="form-group">
						<div class="separator20">&nbsp;</div>
						<div class="white bgGrayStrong bold" style="font-size:14px;padding:10px;border-radius:5px;">Imagen:</div>
						<div class="separator30">&nbsp;</div>
						<?php if($item->IMAGE != ""): 
							$image = new Image();
							$image->path = "multislide";
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
							$imgM->path = "multislide";
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
					<div class="separator1">&nbsp;</div>
				</div>
				<div class="separator20"></div>
				<div class='container container-admin darkshaded-space bgGrayLight white'>
					<div class="separator10">&nbsp;</div>	
					<div class="form-group">
						<label class="label-field" for="Link">Código youtube</label>
						<input type="text" name="Video" id="Video" class="form-control form-m" title="Código de youtube" placeholder="Código de youtube" value="<?php echo $item->VIDEO; ?>" />
						<p id="error-video"></p>
					</div>
					<div style="width:100%;font-style:italic;font-size:11px;margin-left:20%;">
						Pega aquí sólo el códido del video, lo equivalente del ejemplo en rojo:<br/>
						https://www.youtube.com/watch?v=<span style="color:#c00;">otBFnp1EOl8</span>
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

