<?php if (allowed($mnu)) { ?>		
		<div class='cp_mnu_title'>Galer&iacute;as</div>
	<?php
		include ("components/gallery/gallery.list.gallery.php");
		if (isset($_GET['action'])) {
			$action = trim($_GET['action']);
			$gallery = intval($_GET['gallery']);
	// CREATE ALBUM
			if ($action == 'Creategallery') { ?>
				<div class='cp_info'>
					<img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' />
					¡ATENCIÓN! Va a crear una nueva galería
				</div>
				
				<div class='cp_alert noerror' id='info-gallery'></div>
				
				<br/>
				
				<form method='post' action='modules/gallery/create_gallery.php' onsubmit='validate(this); return false;'>
					<input type="hidden" name="mnu" value="<?php echo $mnu; ?>" />
					<div class='cp_table650'>
						<div class='cp_formfield'>
							<label for='Title'>Título:</label>
						</div>
						<input type='text' name='Title' id='Title' title='Title' size='66' />
					</div>
					
					<div class='cp_table'>
						<div class='cp_formfield'>
							<label for="Title_seo">Título SEO:</label>
						</div>
						<input type="text" name="Title_seo" id="Title_seo" size="66" />
					</div>
					
					<div class="cp_table650">			
						<div class="cp_formfield">
							<label for="Section">Álbum:</label>
						</div>			
						<?php
							$q = "select * from ".preBD."images_gallery_sections";
							$result = checkingQuery($connectBD, $q);
							?>
						<select name="Section" id="Section">
							<?php	
							while($row = mysqli_fetch_array($result)) {	?>
								<option value='<?php echo $row['ID']; ?>'>
									<?php echo $row['TITLE']; ?>
								</option>
							<?php } ?>
						</select>
					</div>							
					
					<div class='cp_table650'>
						<div class='cp_description'>
							<label for='description'>Descripci&oacute;n:</label>
						</div>
						<textarea style='margin-left:-2px;margin-bottom:15px;' name='Description' id='Description' title='Descripción' rows='2' cols='64'></textarea>
					</div>
					
					<div class='cp_formfield'>&nbsp;</div>
					<input type='submit' value='Crear galería' />
				</form>
				<script type='text/javascript'>
					includeField('Title','string');
				</script>
			<?php }

	// EDIT ALBUM
			else if ($action == 'Editgallery') { 
				$q = "SELECT * FROM ".preBD."images_gallery WHERE ID = '" . $gallery . "'";
				$result = checkingQuery($connectBD, $q);
				$row = mysqli_fetch_array($result);
				$title = stripslashes($row['TITLE']);
				$title_seo = stripslashes($row['TITLE_SEO']);
				$Section = $row['IDGALLERYSECTION'];
				$description = stripslashes($row['DESCRIPTION']);
			
			?>
				<div class='cp_alert'>
					<img class='cp_msgicon' src='images/alert.png' alt='¡INFORMACIÓN!' />
					¡ATENCIÓN! Va a editar la galería <?php echo $title; ?>
				</div>
				<div class='cp_alert noerror' id='info-newgallery'></div>
				<br/>
				<form method='post' action='modules/gallery/edit_gallery.php' onsubmit='validate(this); return false;'>
					<input type="hidden" name="mnu" value="<?php echo $mnu; ?>" />
					<input type='hidden' name='gallery' value='<?php echo $gallery; ?>' />
					<div class="cp_table650">
						<div class="cp_formfield">
							<label for='newgallery'>Título:</label>
						</div>
						<input type='text' name='Title' id='Title' title='Title' value='<?php echo $title; ?>' size='66' />
					</div>
					<div class="cp_table650">
						<div class="cp_formfield">
							<label for="Title_seo">Título SEO:</label>
						</div>
						<input type="text" name="Title_seo" id="Title_seo" size="66" value="<?php echo $title_seo; ?>" />
					</div>				
				
				<div class="cp_table650">			
					<div class="cp_formfield">
						<label for="Section">Álbum:</label>
					</div>			
					<?php
						$q = "select * from ".preBD."images_gallery_sections";
						$result = checkingQuery($connectBD, $q);
						?>
					<select name="Section" id="Section">
						<?php	
						while($row = mysqli_fetch_array($result)) {	?>
							<option value='<?php echo $row['ID']; ?>' <?php if($Section == $row['ID']){ ?> selected <?php } ?>>
								<?php echo $row['TITLE']; ?>
							</option>
						<?php } ?>
					</select>
				</div>
				<div class='cp_table650'>
					<div class='cp_description'>
						<label for='gallery'>Descripci&oacute;n:</label>
					</div>
					<textarea style='margin-left:-2px;margin-bottom:15px;' name='Description' id='Description' rows='2' cols='64'><?php echo $description; ?></textarea>
				</div>
				<div class='cp_table650'>
					<div class='cp_formfield'>&nbsp;</div>
					<input type='submit' value='Guardar galería' />
				</div>
				</form>
				<script type='text/javascript'>
					includeField('gallery', 'string');
				</script>
			<?php }
		//EDIT STYLES
			else if ($action == 'Editstyles') {
				$q = "select * from ".preBD."images_gallery_style where IDGALLERY = " . $gallery;
				$result = checkingQuery($connectBD, $q);
				$styles = mysqli_fetch_object($result);
			?>
			<div class="cp_box">
				<div class='cp_alert noerror' id='info-ancho_gal'></div>
				<div class='cp_alert noerror' id='info-alto_gal'></div>
				<div class='cp_alert noerror' id='info-ancho_image'></div>
				<div class='cp_alert noerror' id='info-ancho_min_image'></div>
				<div class='cp_alert noerror' id='info-alto_min_image'></div>
				<div class='cp_alert noerror' id='info-padding'></div>
				<div class='cp_alert noerror' id='info-margin'></div>
				<div class='cp_alert noerror' id='info-bordes'></div>
				<div class='cp_alert noerror' id='info-bordes_rad'></div>
				<div class='cp_alert noerror' id='info-fondo'></div>
				<form method='post' action='modules/gallery/edit_gallery_style.php' enctype='multipart/form-data' id='mainform' name='mainform'>
					<input type="hidden" name="mnu" value="<?php echo $mnu; ?>" />
					<input type="hidden" name="gallery" value="<?php echo $gallery; ?>" />
					<div style="clear:both;">
						<div class='cp_formfield bold'>
							<label for='ancho_gal'>Ancho de galería:</label>
						</div>
						<div class='cp_table350'>
							<input type='text' value="<?php echo $styles->WIDTH_BOX; ?>" name='ancho_gal' id='ancho_gal' title='Ancho de la galería' size='10' /> px
						</div>
					</div>
					
					<div style="clear:both;">
						<div class='cp_formfield bold'>
							<label for='alto_gal'>Alto de galería:</label>
						</div>
						<div class='cp_table350'>
							<input type='text' value="<?php echo $styles->HEIGHT_BOX; ?>" name='alto_gal' id='alto_gal' title='Alto de la galería' size='10' /> px
						</div>
					</div>
					
					<div style="clear:both;">
						<div class='cp_formfield bold'>
							<label for='ancho_image'>Ancho máx. de imagen:</label>
						</div>
						<div class='cp_table350'>
							<input type='text' value="<?php echo $styles->WIDTH_IMAGE; ?>" name='ancho_image' id='ancho_image' title='Ancho de la imagen' size='10' /> px
						</div>
					</div>	

					<div style="clear:both;">
						<div class='cp_formfield bold'>
							<label for='ancho_min_image'>Ancho de miniatura:</label>
						</div>
						<div class='cp_table350'>
							<input type='text' value="<?php echo $styles->WIDTH_THUMB; ?>" name='ancho_min_image' id='ancho_min_image' title='Alto de la imagen' size='10' /> px
						</div>
					</div>

					<div style="clear:both;">	
						<div class='cp_formfield bold'>
							<label for='alto_min_image'>Alto de miniatura:</label>
						</div>
						<div class='cp_table350'>
							<input type='text' value="<?php echo $styles->HEIGHT_THUMB; ?>" name='alto_min_image' id='alto_min_image' title='Alto miniatura de la imagen' size='10' /> px
						</div>
					</div>	

					<div style="clear:both;">	
						<div class='cp_formfield bold'>
							<label for='paddingg'>Espaciado (padding):</label>
						</div>
						<div class='cp_table350'>
							<input type='text' value="<?php echo $styles->PADDING; ?>" name='padding' id='padding' title='Espaciado interior' size='10' /> px
						</div>
					</div>	

					<div style="clear:both;">
						<div class='cp_formfield bold'>
							<label for='marging'>Margen (margin):</label>
						</div>
						<div class='cp_table350'>
							<input type='text' value="<?php echo $styles->MARGIN; ?>" name='margin' id='margin' title='Márgenes' size='10' /> px
						</div>
					</div>	

					<div style="clear:both;">
						<div class='cp_formfield bold'>
							<label for='bordes'>Bordes:</label>
						</div>
						<div class='cp_table350'>
							<input type='text' value="<?php echo $styles->BORDER; ?>" name='bordes' id='bordes' title='Bordes' size='10' /> px
						</div>
					</div>	
					
					<div style="clear:both;">
						<div class='cp_formfield bold'>
							<label for='bordes_rad'>Curvatura (radius):</label>
						</div>
						<div class='cp_table350'>
							<input type='text' value="<?php echo $styles->BORDER_RADIUS; ?>" name='bordes_rad' id='bordes_rad' title='Bordes de las esquinas' size='10' /> px
						</div>
					</div>	

					<div style="clear:both;">
						<div class='cp_formfield bold'>
							<label for='fondo'>Color de fondo:</label>
						</div>
						<div class='cp_table350'>
							<input type='text' value="<?php echo $styles->BACKGROUND; ?>" name='fondo' id='fondo' title='Color de fondo' size='10' />#Hex
						</div>
					</div>	
					<div style="clear:both;">
						<div class='cp_formfield bold'>&nbsp;</div>
						<input type='button' name='save' value='Guardar' onclick='showloading(1);validate(this); return false;' />
						<img class='image middle' src='images/loading.gif' style='visibility: hidden; padding: 10px 0px 0px 20px;' id='loading'>
					</div>
				</form>
			</div>
			<script type='text/javascript'>
				includeField('ancho_gal','number');
				includeField('alto_gal','number');
				includeField('ancho_image','number');
				includeField('ancho_min_image','number');		
				includeField('alto_min_image','number');
				includeField('padding','number');
				includeField('margin','number');
				includeField('bordes','number');		
				includeField('bordes_rad','number');
				includeField('fondo','string');		
			</script>
	<?php 
			}
		}else {
			$msg = NULL;
		}
		if (isset($_GET['msg'])) {
			$msg = utf8_encode($_GET['msg']); 
	?>
			<div class='cp_info'>
				<img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' />
				<?php echo $msg; ?>
			</div>
	<?php } else {
			$msg = NULL;
		}
		
		if (strpos($msg,"desconectado")) {
			session_destroy();
		}
}else{
	echo "<p>No tiene permiso para acceder a esta sección.</p>";
}
?>	