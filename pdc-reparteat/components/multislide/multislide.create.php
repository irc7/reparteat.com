<?php if (allowed($mnu)) : ?>
	<div class='cp_mnu_title title_header_mod'>Nuevo Banner publicidad</div>
	<?php
		if (isset($_GET['msg'])) {
			$msg = utf8_encode($_GET['msg']);
			echo "<div class='cp_info'><img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' /><p>".$msg."</p></div>\r\n";
		}
		require_once("includes/classes/Zone/class.Zone.php");
		require_once("includes/classes/Multislide/class.MultislideHook.php");
		$zoneObj = new Zone();
		$hookObj = new MultislideHook();
		$hooks = array(); 
		$hooks = $hookObj->listHook(); 
		$zones = array(); 
		$zones = $zoneObj->allZones(); 
		
	?>	
	<div class='cp_alert noerror' id='info-Name'></div>
	<form method='post' action='modules/multislide/create.php' enctype='multipart/form-data' id='mainform' name='mainform'>
		<input type="hidden" name="mnu" value="<?php echo $mnu; ?>" />
		<input type="hidden" name="com" value="<?php echo $com; ?>" />
		
		<div class='container container-admin darkshaded-space bgGrayNormal white'>
			<div class="separator10">&nbsp;</div>	
			<div class="col-md-7 col-sm-12">
				<div class="form-group">
					<label for='Type' class="label-field white">Tipo:</label>
					<select name='Type' id='Type' class="form-control form-l">						
						<option value='image' selected='selected'>Imagen</option>
						<option value='video'>Video</option>
					</select>
				</div>
			</div>
			<div class="col-md-5 col-sm-12">
				<div class="form-group">
					<label for='Status' class="label-field white">Estado:</label>
					<select name='Status' id='Status' class="form-control form-s">						
						<option value='1'>Publicado</option>
						<option value='0' selected='selected'>Borrador</option>
					</select>
				</div>
			</div>
		</div>
		<div class="separator30">&nbsp;</div>	
		<div class='container container-admin'>
			<div class='row'>	
				<div class="form-group">
					<label class="label-field" for="Title">Título *:</label>
					<input type="text" name="Title" id="Title" class="form-control form-s" title="Título" placeholder="Título *" required />
					<p id="error-Title"></p>
				</div>
				<div class="form-group" style="display:none;">
					<label class="label-field" for="Subtitle">Texto secundario:<br/><span>Max 128 caracteres</span></label>
					<input type="text" name="Subtitle" id="Subtitle" class="form-control form-s" title="Subtitle" placeholder="Subtitle" />
					<p id="error-Subtitle"></p>
				</div>
				<div class="separator10"></div>
				<div class="form-group">
					<label class="label-field" for="idHook">Zonas de visualización *:</label>
					<select name="idHook" id="idHook" class="form-control form-s">
						<?php foreach($hooks as $item){ ?>
							<option value="<?php echo $item->ID; ?>"><?php echo $item->TITLE; ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="separator1"></div>
				<div class="separator30"></div>
				<div class="col-md-6 col-sm-12 bgGrayLight">
					<div class="separator10"></div>
					<div class="form-group">
						<label class="label-field" for='Image'>Imagen:</label>
						<input  class="form-control form-m" type='file' name='Image' id='Image' />
						<div class="separator10"></div>
						<div style="width:100%;font-style:italic;color:#c00;font-size:11px;margin-left:20%;">
							<?php foreach($hooks as $item){ ?>
								Dimensiones optimas de la imagen <?php echo $item->WIDTH; ?> x <?php echo $item->HEIGHT; ?>px (<?php echo $item->TITLE; ?>)<br/>
							<?php } ?>
						</div>
					</div>
					<div class="separator1"></div>
				</div>
				<div class="col-md-6 col-sm-12 bgGrayLight">
					<div class="separator10"></div>
					<div class="form-group">
						<label class="label-field" for='ImageMobile'>Imagen movil:</label>
						<input  class="form-control form-m" type='file' name='ImageMobile' id='ImageMobile' />
						<div class="separator10"></div>
						<div style="width:100%;font-style:italic;color:#c00;font-size:11px;margin-left:20%;">
							<?php foreach($hooks as $item){ ?>
								Dimensiones optimas de la imagen <?php echo $item->WIDTH_MOBILE; ?> x <?php echo $item->HEIGHT_MOBILE; ?>px (<?php echo $item->TITLE; ?>)<br/>
							<?php } ?>
						</div>
					</div>
					<div class="separator1"></div>
				</div>
				<div class="separator20"></div>
				<div class='container container-admin darkshaded-space bgGrayLight white'>
					<div class="separator10">&nbsp;</div>	
					<div class="form-group">
						<label class="label-field" for="Link">Código youtube</label>
						<input type="text" name="Video" id="Video" class="form-control form-m" title="Código de youtube" placeholder="Código de youtube" />
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