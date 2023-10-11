<?php if (allowed($mnu)) { ?>
		<div class='cp_mnu_title'>Secciones de contenido</div>
		<?php 
		include ("components/articles/articles.list.section.php");
		if (isset($_GET['action'])) {
			$action = $_GET['action'];
			$section = $_GET['section'];
				
			$q = "SELECT * FROM ".preBD."articles_sections where TYPE = 'article' and ID = '" . $section . "'";
			$result = checkingQuery($connectBD, $q);
			$row = mysqli_fetch_array($result);
			
			$records =  $row['VIEW_ARTICLES'];
			$title = addslashes($row['TITLE']);
			$title_seo = addslashes($row['TITLE_SEO']);
			$description = addslashes($row['DESCRIPTION']);
			$image_lr = $row['IMAGE_LR'];
			$image_c = $row['IMAGE_C'];
			$thumb_width = $row['THUMB_WIDTH'];
			$thumb_height = $row['THUMB_HEIGHT'];
			$height_image = $row['HEIGHT_IMAGE'];
			$width_image = $row['WIDTH_IMAGE'];
				
	// CHANGE RECORDS
			if ($action == 'Changerecords') { ?>
				<a name="<?php echo $action; ?>"></a>
				<div class='cp_alert'>
					<img class='cp_msgicon' src='images/alert.png' alt='¡INFORMACIÓN!' />
					<p>¡ATENCIÓN! Va a cambiar el número de artículos por página de la sección <?php echo $title; ?></p>
				</div>
				<div class='cp_alert noerror' id='info-records'></div>
				<br/>
				<form method='post' action='modules/articles/changerecords_section.php' onsubmit='validate(this); return false;'>
					<div class='cp_table650'>
						<div class='cp_formfield'>
							<label for='records'>Artículos:</label>
						</div>
						<input type='text' name='records' id='records' title='Artículos' size='2' maxlength='2' value='<?php echo $records; ?>'/>
					</div>
					<input type='hidden' name='section' value='<?php echo $section; ?>' />
					<div class='cp_formfield'>&nbsp;</div>
					<input type='submit' value='Cambiar' />
				</form>
				<script type='text/javascript'>
					includeField('records','id');
				</script>
			<?php }

	// CHANGE IMAGE
			else if ($action == 'Changeimage') {	?>
				<a name="<?php echo $action; ?>"></a>
				<div class='cp_alert'>
					<img class='cp_msgicon' src='images/alert.png' alt='¡INFORMACIÓN!' />
					<p>¡ATENCIÓN! Va a cambiar el ancho de las imágenes de la sección <?php echo $title; ?></p>
				</div>
				<div class='cp_alert noerror' id='info-image_lr'></div>
				<div class='cp_alert noerror' id='info-image_c'></div>
				<br/>
				<form method='post' action='modules/articles/changeimage_section.php' onsubmit='validate(this); return false;'>
					<div class='cp_table650 top'>
						<div class='cp_table150'>
							<label for='image'>Alineadas izquierda/derecha:</label>
						</div>
						<div class='cp_table' style='padding-top:10px;'>
							<input style='float:none;' type='text' name='image_lr' id='image_lr' title='Ancho i/d' size='3' value='<?php echo $image_lr; ?>' />&nbsp;px&nbsp;
						</div>
					</div>
					<div class='cp_table650'>
						<div class='cp_formfield'>
							<label for='image'>Alineadas centro:</label>
						</div>
						<input type='text' name='image_c' id='image_c' title='Ancho c' size='3' value='<?php echo $image_c; ?>'/>&nbsp;px&nbsp;
					</div>
				
					<input type='hidden' name='section' value='<?php echo $section; ?>' />
					<div class='cp_formfield'>&nbsp;</div>
					<input type='submit' value='Cambiar' />
				</form>
				<script type='text/javascript'>
					includeField('image_lr','id');
					includeField('image_c','id');
				</script>
			<?php }		
			
	// CAMBIAR IMAGE DE LA SECCION
			else if ($action == 'Editimagesection') {	?>
				<a name="<?php echo $action; ?>"></a>
				<div class='cp_alert'>
					<img class='cp_msgicon' src='images/alert.png' alt='¡INFORMACIÓN!' />
					<p>¡ATENCIÓN! Va a cambiar la imagen de la sección <?php echo $title; ?></p>
				</div>
				<br/>
				<div class='cp_alert noerror' id='info-Doc'></div>
				<?php
					$q = "select * from ".preBD."articles_sections where TYPE = 'article' and ID = " . $section;
					$result = checkingQuery($connectBD, $q);
					$rowItem = mysqli_fetch_assoc($result); ?>
				<form method='post' action='modules/articles/edit_image_section.php' enctype='multipart/form-data' onsubmit='showloading(1); control_includefield(); validate(this); return false;'>
					<div style="clear:both;">
						<?php if($rowItem['THUMBNAIL'] != ""){ ?>
							<div class='cp_description'>
								<label for='Doc' style='width:60%;vertical-align:middle;'>Imagen actual:</label>
							</div>
							<a href='../files/section/image/<?php echo $rowItem['THUMBNAIL']; ?>' class='lytebox' data-title='<?php echo $rowItem['TITLE']; ?>'>
								<img src="../files/section/image/<?php echo $rowItem['THUMBNAIL']; ?>" width="200"/>
							</a>
							<div class='cp_table650' style="margin-top:10px;">
								<div class='cp_formfield'>
								<label for='Image'>Eliminar imagen:</label></div>	
								<input style="margin-top:7px;" id='delete_image_section' type='checkbox' name='delete_image_section' value="0" onClick='deleteImageSection();'/>
							</div>								
							
							<div class='cp_table650' style="float:left; width:200px;">
								<div class='cp_formfield'>
								<label for='Image'>Modificar imagen:</label></div>	
								<input style="margin-top:7px;" id='Select_image_section' type='checkbox' name='Select_image_section' onClick='openNewImageSection();' />
							</div>						
													
						<?php } ?>
					</div>
					<br/>					
					<div id="new_image_section" style="float:left; width:410px;display:<?php if($rowItem['THUMBNAIL'] != ""){ ?> none <?php }else{ ?> block <?php } ?>">
						<div class='cp_description' style="width:108px;">
							<label for='Doc' style='float:left;'>Nueva imagen:</label>
						</div>
						<input type='file' name='Doc' id='Doc' title='Documento' style='float:left;'/>
						<br/>
						<span style="color: #c00; font-size:10px; margin-top:3px; float:left; clear:both; margin-left:108px;">
							Dimensión óptima: <?php echo $rowItem['WIDTH_IMAGE']."x".$rowItem['HEIGHT_IMAGE']."px"; ?>
						</span>						
					</div>					
					
					<div id="new_size_section" style="display:none;float:left;margin-top:10px;">
						<div class='cp_table650' style="float:left;width:200px;">
							<div class='cp_formfield' style="width:119px;">
								<label for='width_image'>Ancho en píxeles:</label>
							</div>
							<input type='text' name='width_image' id='width_image' title='Ancho' size='3' value='<?php echo $width_image; ?>'/>
						</div>
					
						<div class='cp_table650' style="clear:both;float:left;width:200px;">
							<div class='cp_formfield' style="width:119px;">
								<label for='height_image'>Alto en píxeles:</label>
							</div>
							<input type='text' name='height_image' id='height_image' title='Alto' size='3' value='<?php echo $height_image; ?>'/>
						</div>
					</div>		
					
					<div class='cp_formfield'>&nbsp;</div>	
					
					<input type='hidden' name='section' value='<?php echo $section; ?>' />
					<div class='cp_formfield'>&nbsp;</div>
					<input type='submit' value='Cambiar' style="clear:both;float:right;margin-right:372px;"/>
					<div class='cp_table'><img class='image middle' src='images/loading.gif' style='visibility: hidden; padding: 8px 0px 0px 20px;' id='loading'></div>
				</form>
				<script type='text/javascript'>
					function control_includefield(){
						var aux = document.getElementById('new_image_section');
						if(aux.style.display == "block"){
							includeField('Doc','file');
						}else{
							resetFields();
						}
					}
				</script>
			<?php }			


	// CHANGE THUMB
			else if ($action == 'Changethumb') { ?>
				<a name="<?php echo $action; ?>"></a>
				<div class='cp_alert'>
					<img class='cp_msgicon' src='images/alert.png' alt='¡INFORMACIÓN!' />
					<p>¡ATENCIÓN! Va a cambiar el tamaño de las miniaturas de la sección <?php echo $title; ?></p>
				</div>
				<div class='cp_alert noerror' id='info-thumb_width'></div>
				<div class='cp_alert noerror' id='info-thumb_height'></div>
				<br/>
				<form method='post' action='modules/articles/changethumb_section.php' onsubmit='validate(this); return false;'>
					<div class='cp_table650'>
						<div class='cp_formfield'>
							<label for='thumb_width'>Ancho en píxeles:</label>
						</div>
						<input type='text' name='thumb_width' id='thumb_width' title='Ancho' size='3' value='<?php echo $thumb_width; ?>'/>
					</div>
				
					<div class='cp_table650'>
						<div class='cp_formfield'>
							<label for='thumb_height'>Alto en píxeles:</label>
						</div>
						<input type='text' name='thumb_height' id='thumb_height' title='Alto' size='3' value='<?php echo $thumb_height; ?>'/>
					</div>
				
					<input type='hidden' name='section' value='<?php echo $section; ?>' />
					<div class='cp_formfield'>&nbsp;</div>
					<input type='submit' value='Cambiar' />
				</form>
				<script type='text/javascript'>
					includeField('thumb_width','id');
					includeField('thumb_height','id');
				</script>
			<?php }
			
			
		// CHANGE SIZE IMAGE SECTION
			else if ($action == 'Changesizeimage') { ?>
				<a name="<?php echo $action; ?>"></a>
				<div class='cp_alert'>
					<img class='cp_msgicon' src='images/alert.png' alt='¡INFORMACIÓN!' />
					<p>¡ATENCIÓN! Va a cambiar el tamaño de la imagen de la sección <?php echo $title; ?></p>
				</div>
				<div class='cp_alert noerror' id='info-width_image'></div>
				<div class='cp_alert noerror' id='info-height_image'></div>
				<br/>
				<form method='post' action='modules/articles/change_size_image_section.php' onsubmit='showloading(1); validate(this); return false;'>
					<div class='cp_table650'>
						<div class='cp_formfield'>
							<label for='width_image'>Ancho en píxeles:</label>
						</div>
						<input type='text' name='width_image' id='width_image' title='Ancho' size='3' value='<?php echo $width_image; ?>'/>
					</div>
				
					<div class='cp_table650'>
						<div class='cp_formfield'>
							<label for='height_image'>Alto en píxeles:</label>
						</div>
						<input type='text' name='height_image' id='height_image' title='Alto' size='3' value='<?php echo $height_image; ?>'/>
					</div>
				
					<input type='hidden' name='section' value='<?php echo $section; ?>' />
					<div class='cp_formfield'>&nbsp;</div>
					<input type='submit' value='Cambiar' />
					<div class='cp_table'><img class='image middle' src='images/loading.gif' style='visibility: hidden; padding: 8px 0px 0px 20px;' id='loading'></div>					
				</form>
				<script type='text/javascript'>
					includeField('width_image','number');
					includeField('height_image','number');
				</script>
			<?php }			
			
	// CREATE SECTION
			else if ($action == 'Createsection') { 
				$q = "show columns from ".preBD."articles_sections where Field = 'HEIGHT_IMAGE' or Field = 'WIDTH_IMAGE'";
				$result = checkingQuery($connectBD, $q);
				$i=0;
				while($row = mysqli_fetch_array($result)) {
					if($i == 0){
						$ancho = $row['Default'];
					}else{
						$alto = $row['Default'];
					}
					$i++;
				} ?>
				<a name="<?php echo $action; ?>"></a>
				<div class='cp_info'>
					<img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' />
					<p>¡ATENCIÓN! Va a crear una nueva sección</p>
				</div>
				<div class='cp_alert noerror' id='info-section'></div>
				<br/>
				<form method='post' action='modules/articles/create_section.php' enctype='multipart/form-data' onsubmit='showloading(1); validate(this); return false;'>
					<div class='cp_table650'>
						<div class='cp_formfield'>
							<label for='section'>Nueva sección:</label>
						</div>
						<input type='text' name='section' id='section' title='Sección' size='64' />
					</div>
				
					<div class='cp_table650'>
						<div class='cp_formfield'>
							<label for='Title_seo'>Título SEO:</label>
						</div>
						<input type='text' name='Title_seo' id='Title_seo' title='Title_seo' size='64' />
					</div>
					
					<div class='cp_table650' style='margin-bottom:15px;'>
						<div class='cp_description'>
							<label for='description'>Descripci&oacute;n:</label>
						</div>
						<textarea style='margin-left:-2px;' name='description' id='description' title='Descripción' rows='2' cols='66'></textarea>
					</div>				
					
					<div style="clear:both;">
						<div class='cp_description'>
							<label for='Doc' style='width:15%;vertical-align:middle;'>Imagen:</label>
						</div>
						<input type='file' name='Doc' id='Doc' title='Documento' style="width:70%"/>
						<br/>
						<span style="color: #c00; font-size:10px; margin-top:3px; float:left; clear:both; margin-left:153px;">
							Dimensión óptima: <?php echo $ancho."x".$alto."px"; ?>
						</span>	
					</div>		
					<div class='cp_formfield'>&nbsp;</div>				
					
					<input style="clear:both;" type='submit' value='Crear sección' />
					<div class='cp_table'><img class='image middle' src='images/loading.gif' style='visibility: hidden; padding: 8px 0px 0px 20px;' id='loading'></div>
				</form>
				<script type='text/javascript'>
					includeField('section','string');
				</script>
			<?php }

	// EDIT SECTION
			else if ($action == 'Editsection') { ?>
				<a name="<?php echo $action; ?>"></a>
				<div class='cp_alert'>
					<img class='cp_msgicon' src='images/alert.png' alt='¡INFORMACIÓN!' />
					<p>¡ATENCIÓN! Va a renombrar la sección '<?php echo $title; ?>'</p>
				</div>
				<div class='cp_alert noerror' id='info-newsection'></div>
				<br/>
				<form method='post' action='modules/articles/edit_section.php' onsubmit='validate(this); return false;'>
					<div class='cp_table650'>
						<div class='cp_formfield'>
							<label for='newsection'>Nombre de la sección:</label>
						</div>
						<input type='text' name='newsection' id='newsection' title='Nueva sección' value='<?php echo $title; ?>' size='64' />
					</div>
				
					<div class='cp_table650'>
						<div class='cp_formfield'>
							<label for='Title_seo'>Título SEO:</label>
						</div>			
						<input type='text' name='Title_seo' id='Title_seo' title='Title_seo' value='<?php echo $title_seo; ?>' size='64' />
					</div>
				
					<div class='cp_table650' style='margin-bottom:15px;'>
						<div class='cp_description'>
							<label for='section'>Descripci&oacute;n:</label>
						</div>
						<textarea style='margin-left:-2px;' name='description' id='description' rows='2' cols='66'><?php echo $description; ?></textarea>
					</div>
					<input type='hidden' name='section' value='<?php echo $_GET["section"]; ?>' />
					<div class='cp_formfield'>&nbsp;</div>
					<input type='submit' value='Renombrar sección' />
				</form>
				<script type='text/javascript'>
					includeField('newsection', 'string');
				</script>
			<?php }
		}else {
			$msg = NULL;
		}
		if (isset($_GET['msg'])) {
			$msg = utf8_encode($_GET['msg']); ?>
			<div class='cp_info'>
				<img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' />
				<p><?php echo $msg; ?></p>
			</div>
		<?php }
		else {
			$msg = NULL;
		}
		if (strpos($msg,"desconectado")) {
			session_destroy();
		}
}else{
	echo "<p>No tiene permiso para acceder a esta sección.</p>";
}?>	