
<?php if (allowed($mnu)) { ?>	

	<?php
		date_default_timezone_set("Europe/Madrid");

		$now = date('Y').date('m').date('d').date('H').date('i').date('s');
		$date_joker = "0000-00-00 00:00:00";
	if(isset($_GET["filtermenu"])) {
		$filtermenu = intval($_GET["filtermenu"]);
		$q="select * from ".preBD."menu where ID = " . $filtermenu;
		$result_menu = checkingQuery($connectBD, $q);
		$edit_menu = mysqli_fetch_assoc($result_menu);
		echo "<div class='cp_mnu_title title_header_mod'>".$edit_menu["TITLE"]."</div>\r\n";
		if (isset($_GET['msg']) && !isset($_GET['action'])) {
			$msg = $_GET['msg'];
			if(!mb_check_encoding($msg, 'UTF-8')){
				$msg = utf8_encode($msg); 
			} ?>
			<div class='cp_table' style='width:100%;clear:both;'>
			<div class='cp_info'>
			<img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' /><?php echo $msg; ?>
			</div>
			</div>
			<br/>
		<?php }
		
		include ("components/menu/menu.list.php");
	}
		if (isset($_GET['action'])) {
			$action = $_GET['action'];

	// DELETE ITEM
			if ($action == 'DeleteItem') {
				if (isset($_GET['msg'])) {
					if(!mb_check_encoding($msg, 'UTF-8')){
						$msg = utf8_encode($msg); 
					}
					$msg = $_GET['msg']; ?>
					<div class='cp_info' style="clear:both;"><img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' /><?php echo $msg; ?></div>
					<br/>
				<?php }
				$item = $_GET['item']; ?>
				<br/>
				<div class='cp_alert'><img class='cp_msgicon' src='images/alert.png' alt='¡INFORMACIÓN!' />¡ATENCIÓN! Va a eliminar el elemento de menú <?php echo $item; ?>.</div></br>
				<br/>
				<form method='post' action='modules/menu/delete_item_menu.php'>
				<input type='hidden' name='item' value='<?php echo $item; ?>' />
				<input type='hidden' name='Menu' value='<?php echo $filtermenu; ?>' />
				<div class='cp_table'>
				<input type='submit' value='Eliminar' />
				</div>
				</form>
			<?php }

	// CREATE MENU
			else if ($action == 'CreateMenu') { ?>
				<div class='cp_mnu_title title_header_mod'>Crear menú</div>
				<?php if (isset($_GET['msg'])) {
					$msg = $_GET['msg'];
					if(!mb_check_encoding($msg, 'UTF-8')){
						$msg = utf8_encode($msg); 
					} ?>
					<div class='cp_info' style="clear:both;"><img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' /><?php echo $msg; ?></div>
					<br/>
				<?php } ?>
				<a name='CreateItem'></a>
				<div class='cp_alert noerror' id='info-Title'></div>
				<div class='cp_alert noerror' id='info-Parent'></div>
				<div class='cp_info'><img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' />¡ATENCIÓN! Va a crear un nuevo menú</div></b>
				<br/>
				<form method='post' action='modules/menu/create_menu.php' id='mainform' name='mainform'>
					<div class='cp_table650'>
						<div class='cp_table' style='width:180px;padding-top:3px;font-weight:bold;float:left;'>Nombre del menú:&nbsp;</div>
						<input type='text' name='Title' id='Title' title='Nombre de menú' style='width:200px;' />
					</div>
					<div class='cp_table650'>
						<div class='cp_table' style='width:180px;padding-top:3px;font-weight:bold;float:left;'>Nº de elementos principales:&nbsp;</div>
						<input type='text' name='Parent' id='Parent' title='Elementos principales' value='0' style='width:40px;text-align:right' />
						<img class='image middle' src='images/loading.gif' style='margin-left:20px;visibility: hidden; padding: 10px 20px 0px 0px;' id='loading'>
					</div>
					<div style='display:block;clear:both;'>
					<div class='cp_table' style='width:160px;'>&nbsp;</div>
					<div class='cp_table'><input type='button' name='save' value='Crear menú' onclick='showloading(1); validate(this); return false;' /></div>
					</div>
				</form>
				<script type='text/javascript'>
					includeField('Title','string');
					includeField('Parent','number');
				</script>
			<?php }
			
	// CREATE ITEM
			else if ($action == 'CreateItem') {
				if (isset($_GET['msg'])) {
					$msg = $_GET['msg'];
					if(!mb_check_encoding($msg, 'UTF-8')){
						$msg = utf8_encode($msg); 
					} ?>
					<div class='cp_info' style="clear:both;"><img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' /><?php echo $msg; ?></div>
					<br/>
				<?php } ?>
				
				<a name='CreateItem'></a>
				<div class='cp_alert noerror' id='info-title_link' style="clear:both;"></div>
				<div class='cp_alert noerror' id='info-Parent' style="clear:both;"></div>
				<div class='cp_alert noerror' id='info-red_social' style="clear:both;"></div>
				<div class='cp_info' style="clear:both;"><img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' />¡ATENCIÓN! Va a crear un nuevo elemento de menú</div></b>
				<br/>
				<form method='post' action='modules/menu/create_item_menu.php' enctype='multipart/form-data'  id='mainform' name='mainform'>
				<div style='display:block;clear:both;margin-bottom:10px;width:100%;'>
					<div class='cp_table650'>
					<div class='cp_formfield'>Título:&nbsp;</div>
						<input type='text' name='Title' id='title_link' title='Título' style='width:400px;' />
						<input type='hidden' name='Menu' id='Menu' title='Menu' value='<?php echo $filtermenu; ?>' />
					</div>
					<div class='cp_table'>
						<div class='cp_formfield'>Raíz del elemento&nbsp;</div>
						<div class='cp_table550'>
							<select name='Parent' id='Parent' title='Raíz del elemento'>
								<?php 
								$q_s = "SELECT * FROM ".preBD."menu_item where PARENT = 0 AND IDMENU=".$filtermenu." ORDER BY POSITION ASC";
								$result_s = checkingQuery($connectBD, $q_s);
								while($row_item = mysqli_fetch_assoc($result_s)) { ?>
									<option value='<?php echo $row_item["ID"]; ?>' style='color:#01356f;font-size:12px;'><?php echo $row_item["TITLE"]; ?></option>
									<?php 
									$q_sub = "select * from ".preBD."menu_item where PARENT = " . $row_item["ID"]. " and LEVEL = 1 AND IDMENU=".$filtermenu;
									$result_sub = checkingQuery($connectBD, $q_sub);
									while($row_subitem = mysqli_fetch_assoc($result_sub)) { ?>
										<option value='<?php echo $row_subitem["ID"]; ?>' style='margin-left:10px;'>&nbsp;&nbsp;- <?php echo $row_subitem["TITLE"]; ?></option>				
									<?php }
								} ?>
								<option value="0" style='color:red;font-size:12px;'>Nuevo elemento raíz</option>
							</select>
						</div>
					</div>
				
					<div class='cp_table'>
						<div class='cp_formfield'>Mostrar&nbsp;</div>
						<div class='cp_table550'>
							<select name='enlace_item' id='enlace_item' title='Mostrar enlace' onchange ="mostrar_ocultar();" >
								<option value='1' selected >&nbsp;&nbsp;T&iacute;tulo</option>				
								<option value='2' >&nbsp;&nbsp;Imagen</option>
								<option value='3' >&nbsp;&nbsp;Título + Imagen</option>
							</select>
						</div>
					</div>	

					<div class='cp_table650' id="new_image" style="display:none">
						<div class='cp_formfield'>
						<label for='Image'>Imagen:</label></div>
						<input type='file' name='red_social' id='red_social' title='imagen' size='20' />
					</div>
					
					<div class='cp_formfield'>Destino: </div>
					<select id="select_item_menu" onchange="opt_select_type_item()" name='type_link'>				
						<?php if (icon_header(1)) { 
							if(module_active(1)){ ?>
								<optgroup>
									<option value="2">Artículo</option>
									<option value="1">Sección de contenido</option>
								</optgroup>
							<?php } 	
							if(module_active(5)){ ?>							
								<optgroup label="---------------------------------------------------" style="width:150px;">
									<option style="width:100%;" value="9">Documentos</option>
									<option style="width:100%;" value="4">Descarga</option>
									<option style="width:100%;" value="3">Sección de descarga</option>	
								</optgroup>
							<?php } 	
							if(module_active(2)){ ?>										
								<optgroup label="---------------------------------------------------" style="width:150px;">
									<option style="width:100%;" value="8">Galería de imágenes</option>
									<option style="width:100%;" value="7">Álbumes de galerías</option>	
								</optgroup>							
							<?php } 	
							if(module_active(3)){ ?>										
								<optgroup label="---------------------------------------------------" style="width:150px;">
									<option style="width:100%;" value="6">Vídeo</option>
									<option style="width:100%;" value="5">Sección de vídeos</option>	
								</optgroup>		
							<?php } 									
						} ?>
						
						<optgroup label="---------------------------------------------------" style="width:150px;">				
							<option style="width:100%" value="-1">Enlace personalizado</option>
						</optgroup>		
						
						<optgroup label="---------------------------------------------------" style="width:150px;">					
							<option style="width:100%" value="0" selected>Elemento de menú</option>
						</optgroup>	
					</select>						
				
				</div>		
				
	<!-- ARTICULOS -->
				<div class='cp_table' id='select_article' style='display:none;width:571px;'>
					<div class='cp_box dotted cp_height25' style="width:528px;">
					<div class='cp_table200'><div class='cp_formfield' style='width: 60px !important;'>Sección:&nbsp;</div>
					<select name='sectionArticle' id='sectionArticle' onchange='openSelectArticle();return false;' style='width:130px;'>
					<?php 
						$q = "select ID, TITLE from ".preBD."articles_sections where (TYPE = 'article' or TYPE = 'fundacion')";
						$q .= " order by TITLE asc";
						$result = checkingQuery($connectBD, $q);
						$i = 0;
						while($row_sec = mysqli_fetch_assoc($result)) { ?>
							<option value='<?php echo $row_sec["ID"]; ?>'><?php echo $row_sec["TITLE"]; ?></option>
							<?php $sec_art[$i] = $row_sec;
							$i++;
						} ?>
						</select></div>
					<div class='cp_formfield' style='width: 60px !important;'>Artículo:&nbsp;</div>
					<div class='cp_table' id='content_list_articles'>
					<?php 
					for($i = 0; $i < count($sec_art); $i++) { ?>
						<div id='articles<?php echo $sec_art[$i]["ID"]; ?>' class='cp_table' style='display:none;'>
						<select name='Article<?php echo $sec_art[$i]["ID"]; ?>' style='max-width:267px;'>
						<?php 
						$q = "select ID, TITLE from ".preBD."articles where (TYPE = 'article' or TYPE = 'fundacion')";
						$q .= " and IDSECTION = " . $sec_art[$i]["ID"];
						$q .= " and STATUS != 0 ";
						$q .= " and TRASH = 0";
						$q .= " and (DATE_START <= NOW() or (DATE_START <= NOW() and DATE_END <> DATE_START and DATE_END >= NOW()))";
						$q .= " order by DATE_START desc";
						$result = checkingQuery($connectBD, $q);
						while($row_art = mysqli_fetch_assoc($result)) { ?>
							<option value='<?php echo $row_art["ID"]; ?>'><?php echo cutting($row_art["TITLE"],50); ?></option>
						<?php } ?>
					</select></div>
					<?php } ?>
					</div></div>
					</div>
				</div>

	<!-- SECCION -->
				<div id='box_section' class='cp_table' style='display:none; float:right; width:571px;'>
					<div class='cp_box dotted cp_height25' style="width:528px;">
						<div class='cp_formfield' style='width: 60px !important;'>Sección:&nbsp;</div>
						<select name='Section' id='Section'>
							<?php $q = "select ID, TITLE from ".preBD."articles_sections where (TYPE = 'article' or TYPE = 'fundacion')";
							$q .= " order by TITLE asc";
							$result = checkingQuery($connectBD, $q);
							while($row_sec = mysqli_fetch_assoc($result)) { ?>
								<option value='<?php echo $row_sec["ID"]; ?>'><?php echo $row_sec["TITLE"]; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
			<?php if(module_active(5)){ ?>	
	<!-- DOCUMENTOS DE DESCARGA -->
				<div class='cp_box' id='select_doc' style='display:none;width:100%;'>
					<div class='cp_box dotted cp_height25' style="width:528px;height: 140px;margin-left:140px;">
						<div class='cp_box'>
							<div class='cp_formfield' style='width: 90px !important;'>Sección:&nbsp;</div>
							<select name='select-doc-level-1' id='select-doc-level-1' style='width:250px;'>
							<?php 
								$q = "select ID, TITLE from ".preBD."download_sections";
								$q .= " order by TITLE asc";
								$result = checkingQuery($connectBD, $q);
								$i = 0;
								while($row_sec = mysqli_fetch_object($result)) { 
							?>
									<option value='<?php echo $row_sec->ID; ?>'><?php echo $row_sec->TITLE; ?></option>
							<?php 	$sec_des[$i] = $row_sec;
									$i++;
								} ?>
							</select>
						</div>
						
						<div class='cp_box'>
							<div class='cp_formfield' style='width: 90px !important;'>Descargas:&nbsp;</div>
							<?php 
							$down = array();
							for($i = 0; $i < count($sec_des); $i++) { ?>
								<div id='descargasDoc<?php echo $sec_des[$i]->ID; ?>' class="box-doc-level-2" style='display:none;'>
									<select name='select-doc-level-2-<?php echo $sec_des[$i]->ID; ?>' class='select-doc-level-2' id='select-doc-level-2-<?php echo $sec_des[$i]->ID; ?>' style='width:250px;'>
										<?php 
											$q = "select ID, TITLE from ".preBD."downloads where";
											$q .= " IDSECTION = " . $sec_des[$i]->ID;
											$q .= " and STATUS > 0";
											$q .= " and (DATE_START <= NOW() or (DATE_START <= NOW() and DATE_END <> DATE_START and DATE_END >= NOW()))";
											$q .= " order by DATE_START desc";
											$result = checkingQuery($connectBD, $q);
											$j = 0;
											while($row_art = mysqli_fetch_object($result)) {
												$down[] = $row_art;
											?>
												<option style="width:100%;" value='<?php echo $row_art->ID; ?>'<?php if($j==0){echo " selected='selected'";} ?>><?php echo cutting($row_art->TITLE,50); ?></option>
										<?php $j++;} ?>
									</select>
								</div>
							<?php } ?>
							
						</div>
						<div class='cp_box'>
							<div class='cp_formfield' style='width: 90px !important;'>Documentos:&nbsp;</div>
							<?php 
							for($i = 0; $i < count($down); $i++) { ?>
								<div id='box-doc-level-3-<?php echo $down[$i]->ID; ?>' class="box-doc-level-3" style='display:none;'>
									<select name='select-doc-level-3-<?php echo $down[$i]->ID; ?>' id='select-doc-level-3-<?php echo $down[$i]->ID; ?>' style='width:200px;'>
										<?php 
											$q = "select ID, TITLE from ".preBD."download_docs where";
											$q .= " IDDOWNLOAD = " . $down[$i]->ID;
											$q .= " order by POSITION asc";
											$result = checkingQuery($connectBD, $q);
											while($docs = mysqli_fetch_object($result)) {
											?>
												<option style="width:100%;" value='<?php echo $docs->ID; ?>'><?php echo cutting($docs->TITLE,50); ?></option>
										<?php } ?>
									</select>
								</div>
							<?php } ?>
							</div>
						</div>
					</div>
					<script type="text/javascript">
						
					</script>
				</div>
	<!-- DESCARGA -->
				<div class='cp_table' id='select_descarga' style='display:none; float:right; width:571px;'>
					<div class='cp_box dotted cp_height25' style="width:528px;">
					<div class='cp_table200'><div class='cp_formfield' style='width: 60px !important;'>Sección:&nbsp;</div>
					<select name='sectionDescarga' id='sectionDescarga' onchange='openSelectDescarga();return false;' style='width:130px;'>
					<?php 
						$q = "select ID, TITLE from ".preBD."download_sections";
						$q .= " order by TITLE asc";
						$result = checkingQuery($connectBD, $q);
						$i = 0;
						while($row_sec = mysqli_fetch_assoc($result)) { ?>
							<option value='<?php echo $row_sec["ID"]; ?>'><?php echo $row_sec["TITLE"]; ?></option>
							<?php $sec_des[$i] = $row_sec;
							$i++;
						} ?>
						</select></div>
					<div class='cp_formfield' style='width: 60px !important;'>Descarga:&nbsp;</div>
					<div class='cp_table' id='content_list_descargas'>
					<?php 
					for($i = 0; $i < count($sec_des); $i++) { ?>
						<div id='descargas<?php echo $sec_des[$i]["ID"]; ?>' class='cp_table' style='display:none;'>
						<select name='Descarga<?php echo $sec_des[$i]["ID"]; ?>' style='width:267px;'>
						<?php 
						$q = "select ID, TITLE from ".preBD."downloads where";
						$q .= " IDSECTION = " . $sec_des[$i]["ID"];
						$q .= " and STATUS = 1";
						$q .= " and (DATE_START <= NOW() or (DATE_START <= NOW() and DATE_END <> DATE_START and DATE_END >= NOW()))";
						$q .= " order by DATE_START desc";
						$result = checkingQuery($connectBD, $q);
						while($row_art = mysqli_fetch_assoc($result)) { ?>
							<option style="width:100%;" value='<?php echo $row_art["ID"]; ?>'><?php echo cutting($row_art["TITLE"],50); ?></option>
						<?php } ?>
					</select></div>
					<?php } ?>
					</div></div>
					</div>
				</div>				
				
				
	<!-- SECCION DESCARGA-->
				<div id='box_descarga' class='cp_table' style='display:none; float:right; width:571px;'>
					<div class='cp_box dotted cp_height25' style="width:528px;">
						<div class='cp_formfield' style='width: 60px !important;'>Sección:&nbsp;</div>
						<select name='DescargaSection' id='DescargaSection'>
							<?php $q = "select ID, TITLE from ".preBD."download_sections";
							$q .= " order by TITLE asc";
							$result = checkingQuery($connectBD, $q);
							while($row_sec = mysqli_fetch_assoc($result)) { ?>
								<option value='<?php echo $row_sec["ID"]; ?>'><?php echo $row_sec["TITLE"]; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>	
			<?php } ?>

	<!-- GALERÍA -->
				<div class='cp_table' id='select_gallery' style='display:none; float:right; width:571px;'>
					<div class='cp_box dotted cp_height25' style="width:528px;">
					<div class='cp_table200'><div class='cp_formfield' style='width: 60px !important;'>Sección:&nbsp;</div>
					<select name='sectionGallery' id='sectionGallery' onchange='openSelectGallery();return false;' style='width:130px;'>
					<?php 
						$q = "select ID, TITLE from ".preBD."images_gallery_sections";
						$q .= " order by TITLE asc";
						$result = checkingQuery($connectBD, $q);
						$i = 0;
						while($row_sec = mysqli_fetch_assoc($result)) { ?>
							<option value='<?php echo $row_sec["ID"]; ?>'><?php echo $row_sec["TITLE"]; ?></option>
							<?php $sec_gal[$i] = $row_sec;
							$i++;
						} ?>
						</select></div>
					<div class='cp_formfield' style='width: 60px !important;'>Galería:&nbsp;</div>
					<div class='cp_table' id='content_list_galleries'>
					<?php 
					for($i = 0; $i < count($sec_gal); $i++) { ?>
						<div id='galleries<?php echo $sec_gal[$i]["ID"]; ?>' class='cp_table' style='display:none;'>
						<select name='Gallery<?php echo $sec_gal[$i]["ID"]; ?>' style='width:267px;'>
						<?php 
						$q = "select ID, TITLE from ".preBD."images_gallery where";
						$q .= " IDGALLERYSECTION = " . $sec_gal[$i]["ID"];
						$q .= " order by ID desc";
						$result = checkingQuery($connectBD, $q);
						while($row_gal = mysqli_fetch_assoc($result)) { ?>
							<option style="width:100%;" value='<?php echo $row_gal["ID"]; ?>'><?php echo cutting($row_gal["TITLE"],50); ?></option>
						<?php } ?>
					</select></div>
					<?php } ?>
					</div></div>
					</div>
				</div>				
				
				
	<!-- ÁLBUM DE GALERÍAS -->
				<div id='box_gallery' class='cp_table' style='display:none; float:right; width:571px;'>
					<div class='cp_box dotted cp_height25' style="width:528px;">
						<div class='cp_formfield' style='width: 60px !important;'>Sección:&nbsp;</div>	
						<select name='GallerySection' id='GallerySection'>
							<?php $q = "select ID, TITLE from ".preBD."images_gallery_sections";
							$q .= " order by TITLE asc";
							$result = checkingQuery($connectBD, $q);
							while($row_sec = mysqli_fetch_assoc($result)) { ?>
								<option value='<?php echo $row_sec["ID"]; ?>'><?php echo $row_sec["TITLE"]; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>	
			<?php if(module_active(3)){ ?>	
	<!-- VÍDEO -->
				<div class='cp_table' id='select_video' style='display:none; float:right; width:571px;'>
					<div class='cp_box dotted cp_height25' style="width:528px;">
					<div class='cp_table200'><div class='cp_formfield' style='width: 60px !important;'>Sección:&nbsp;</div>
					<select name='sectionVideo' id='sectionVideo' onchange='openSelectVideo();return false;' style='width:130px;'>
					<?php 
						$q = "select ID, TITLE from ".preBD."videos_gallery";
						$q .= " order by TITLE asc";
						$result = checkingQuery($connectBD, $q);
						$i = 0;
						while($row_sec = mysqli_fetch_assoc($result)) { ?>
							<option value='<?php echo $row_sec["ID"]; ?>'><?php echo $row_sec["TITLE"]; ?></option>
							<?php $sec_vid[$i] = $row_sec;
							$i++;
						} ?>
						</select></div>
					<div class='cp_formfield' style='width: 60px !important;'>Vídeo:&nbsp;</div>
					<div class='cp_table' id='content_list_videos'>
					<?php 
					for($i = 0; $i < count($sec_vid); $i++) { ?>
						<div id='videos<?php echo $sec_vid[$i]["ID"]; ?>' class='cp_table' style='display:none;'>
						<select name='Video<?php echo $sec_vid[$i]["ID"]; ?>' style='width:267px;'>
						<?php 
						$q = "select ID, TITLE from ".preBD."videos where";
						$q .= " IDGALLERY = " . $sec_vid[$i]["ID"];
						$q .= " and STATUS = 1";
						$q .= " order by ID desc";
						$result = checkingQuery($connectBD, $q);
						while($row_art = mysqli_fetch_assoc($result)) { ?>
							<option style="width:100%;" value='<?php echo $row_art["ID"]; ?>'><?php echo cutting($row_art["TITLE"],50); ?></option>
						<?php } ?>
					</select></div>
					<?php } ?>
					</div></div>
					</div>
				</div>				
				
				
	<!-- SECCION VÍDEOS-->
				<div id='box_video' class='cp_table' style='display:none; float:right; width:571px;'>
					<div class='cp_box dotted cp_height25' style="width:528px;">
						<div class='cp_formfield' style='width: 60px !important;'>Sección:&nbsp;</div>	
						<select name='VideoSection' id='VideoSection'>
							<?php $q = "select ID, TITLE from ".preBD."videos_gallery";
							$q .= " order by TITLE asc";
							$result = checkingQuery($connectBD, $q);
							while($row_sec = mysqli_fetch_assoc($result)) { ?>
								<option value='<?php echo $row_sec["ID"]; ?>'><?php echo $row_sec["TITLE"]; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>					
			<?php } ?>	
	<!-- LINK EXTERNO -->
				<div class='cp_table' id='select_link' style='display:none; float:right; width:571px;'>
					<div class='cp_box dotted cp_height25' style="width:528px;">
						<input type='text' name='Link' value='https://' style='width:400px;'>
						<div class='cp_table'><select name='Target'>
							<option value='_self'>Misma ventana</option>
							<option value='_blank'>Nueva ventana</option>
						</select></div><br/>
					</div>
				</div>

				<div class='cp_table700'>
					<input style="float:right;" type='button' name='save' value='Crear' onclick='enlace_seleccionado(); showloading(1); validate(this); return false;' />
					<div class='cp_table'><img class='image middle' src='images/loading.gif' style='visibility: hidden; padding: 8px 0px 0px 20px;' id='loading'></div>
				</div>
				
			</form>
	<script type='text/javascript'>
		includeField('title_link','string');
		includeField('Parent','number');

		function enlace_seleccionado(){
			var valor_select = document.getElementById("enlace_item").value;
		
			if((valor_select == 2) || (valor_select == 3)){
				includeField('red_social','file');
			}else{	
				resetFields();
				document.getElementById('info-title_link').innerHTML = "";
				document.getElementById('info-Parent').innerHTML = "";
				document.getElementById('info-red_social').innerHTML = "";
				includeField('title_link','string');
				includeField('Parent','number');
			}
		}
		
	</script>
			<?php
			}

	// EDIT ITEM
			else if ($action == 'EditItem') {
				if (isset($_GET['msg'])) {
					$msg = $_GET['msg'];
					if(!mb_check_encoding($msg, 'UTF-8')){
						$msg = utf8_encode($msg); 
					} ?>
					<div class='cp_info' style="clear:both;"><img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' /><?php echo $msg; ?></div>
					<br/>
				<?php }

				$item = $_GET['item'];
				$q = "select * from ".preBD."menu_item where ID = " . $item;
				$result = checkingQuery($connectBD, $q);
				$row = mysqli_fetch_assoc($result); 
				
				?>
				<a name='EditItem'></a>
				
				<div class='cp_alert noerror' id='info-title_link'></div>
				<div class='cp_alert noerror' id='info-red_social'></div>
				<?php 
				if(!isset($_GET["msg"])) { ?>
					<br/>
					<div class='cp_alert' style="clear:both;"><img class='cp_msgicon' src='images/alert.png' alt='¡INFORMACIÓN!' />¡ATENCIÓN! Va a editar el elemento de menu &quot;<?php echo $row["TITLE"]; ?>&quot;.</div></b>
				<?php } ?>
				<form method='post' action='modules/menu/edit_item_menu.php' enctype='multipart/form-data'  id='mainform' name='mainform'>
				<input type='hidden' name='id' value='<?php echo $item; ?>' />
				<div style='display:block;margin-bottom:10px;clear:both;width:100%;float:left;' id="caja_aux">
					<div class='cp_table650'>
						<input type='hidden' name='Menu' id='Menu' title='Menu' value='<?php echo $filtermenu; ?>' />
						<div class='cp_formfield'>Título:&nbsp;</div>
						<input type='text' name='Title' id='title_link' title='Título' style='width:400px;' value='<?php echo stripslashes($row["TITLE"]); ?>'/>
					</div>
				
				<?php 
				if($row["LEVEL"] != 0) { ?>
					<div class='cp_formfield' style="clear:both;">Raíz del elemento&nbsp;</div>
					<div class='cp_table550'>
						<select name='Parent' id='Parent' title='Raíz del elemento'>
							<?php 
							$q_s = "SELECT * FROM ".preBD."menu_item where PARENT = 0 AND IDMENU=".$filtermenu." ORDER BY POSITION ASC";
							$result_s = checkingQuery($connectBD, $q_s);
							while($row_item = mysqli_fetch_assoc($result_s)) { ?>
								<option value='<?php echo $row_item["ID"]; ?>'
								<?php if($row_item["ID"] == $row["PARENT"]){ ?>
									selected='selected'
								<?php } ?>
								style='color:#01356f;font-size:12px;'><?php echo $row_item["TITLE"]; ?></option>
								<?php 
								$q_sub = "select * from ".preBD."menu_item where PARENT = " . $row_item["ID"]. " and LEVEL = 1 AND IDMENU=".$filtermenu;
								$result_sub = checkingQuery($connectBD, $q_sub);
								while($row_subitem = mysqli_fetch_assoc($result_sub)) { ?>
									<option value='<?php echo $row_subitem["ID"]; ?>'
									<?php if($row_subitem["ID"] == $row["PARENT"]){ ?>
										selected='selected'
									<?php }	?>
									style='margin-left:10px;'>&nbsp;&nbsp;- <?php echo $row_subitem["TITLE"]; ?></option>
								
								<?php }
							} ?>
							<option value="0" style='color:red;font-size:12px;'>Nuevo elemento raíz</option>
						</select>
					</div><br/>
				<?php } ?>				
				
					<div class='cp_table'>
						<div class='cp_formfield'>Mostrar&nbsp;</div>
						<div class='cp_table550'>
							<select name='enlace_item' id='enlace_item' title='Mostrar enlace' <?php if($row["THUMBNAIL"] == ""){ ?> onchange="mostrar_ocultar();" <?php } ?>>
								<option value='1' <?php if($row['DISPLAY'] == 1){ echo "selected";} ?>>&nbsp;&nbsp;T&iacute;tulo</option>				
								<option value='2' <?php if($row['DISPLAY'] == 2){ echo "selected";} ?>>&nbsp;&nbsp;Imagen</option>
								<option value='3' <?php if($row['DISPLAY'] == 3){ echo "selected";} ?>>&nbsp;&nbsp;Título + Imagen</option>
							</select>
						</div>
					</div>	
				
				<?php if($row["THUMBNAIL"] != ""){ ?>		
					<div class='cp_table650' style="margin-bottom:10px;">
						<div class='cp_formfield'>
						<label for='Image'>Imagen:</label></div>				
						<div><?php if($row["THUMBNAIL"] != ""){ ?> <img src='../files/menus/image/<?php echo $row["THUMBNAIL"]; ?>'  height='auto' style="max-height:45px;"/> <?php } ?></div>				
					</div>
					
					<div class='cp_table650'>
						<div class='cp_formfield'>
						<label for='Image'>Eliminar imagen:</label></div>	
						<input id='delete_image_item_menu' type='checkbox' name='delete_image_item_menu' onClick='deleteImageItemMenu();' />
					</div>					
				
					<div id="box_edit_image" class='cp_table650' style="margin-bottom:10px;float:left;width:250px;height:24px;">
						<div class='cp_formfield'>
						<label for='Image'>Modificar imagen:</label></div>	
						<input id='Select_image_item_menu' type='checkbox' name='Select_image_item_menu' onClick='openNewImageItemMenu();' />
					</div>
					
					<div id="box_aux" style="visibility:visible"></div>
				<?php }else{ ?>
					<div id="box_aux" style="visibility:hidden"></div>
				<?php } ?>				
					
					<div style="display:none;float:left;width:450px;"  id="new_image">
						<div class='cp_formfield'>Nueva imagen:</div>
						<input  type='file' name='red_social' id='red_social' title='imagen' size='20' value='0' />
					</div>													
				
					<div class='cp_formfield' style="float:left;clear:both;">Destino: </div>
					<select id="select_item_menu" onchange="opt_select_type_item()" name='type_link' style="float:left;">
						<?php if (icon_header(1)) {
								if(module_active(1)){ ?>					
									<optgroup>
										<option style="width:100%" value="2" <?php if($row["TYPE"] == 2) { ?> selected <?php } ?>>Artículo</option>
										<option style="width:100%" value="1"<?php if($row["TYPE"] == 1) { ?> selected <?php } ?>>Sección de contenido</option>
									</optgroup>
								<?php }
								if(module_active(5)){ ?>
									<optgroup label="---------------------------------------------------" style="width:150px;">	
										<option style="width:100%" value="9" <?php if($row["TYPE"] == 9) { ?> selected <?php } ?>>Documentos</option>
										<option style="width:100%" value="4" <?php if($row["TYPE"] == 4) { ?> selected <?php } ?>>Descarga</option>
										<option style="width:100%" value="3" <?php if($row["TYPE"] == 3) { ?> selected <?php } ?>>Sección de descarga</option>	
									</optgroup>
								<?php }
								if(module_active(2)){ ?>									
									<optgroup label="---------------------------------------------------" style="width:150px;">	
										<option style="width:100%" value="8" <?php if($row["TYPE"] == 8) { ?> selected <?php } ?>>Galería de imágenes</option>
										<option style="width:100%" value="7" <?php if($row["TYPE"] == 7) { ?> selected <?php } ?>>Álbumes de galerías</option>	
									</optgroup>	
								<?php }
								if(module_active(3)){ ?>									
									<optgroup label="---------------------------------------------------" style="width:150px;">	
										<option style="width:100%" value="6" <?php if($row["TYPE"] == 6) { ?> selected <?php } ?>>Vídeo</option>
										<option style="width:100%" value="5" <?php if($row["TYPE"] == 5) { ?> selected <?php } ?>>Sección de vídeos</option>	
									</optgroup>
								<?php }									
						} ?>
						
						<optgroup label="---------------------------------------------------" style="width:150px;">				
							<option style="width:100%" value="-1" <?php if($row["TYPE"] == -1) { ?> selected <?php } ?>>Enlace personalizado</option>
						</optgroup>							
						<optgroup label="---------------------------------------------------" style="width:150px;">						
							<option style="width:100%" value="0" <?php if($row["TYPE"] == 0) { ?> selected <?php } ?>>Elemento de menú</option>
						</optgroup>	
					</select>				
				</div>	
				
				<!-- ARTICULOS -->
				<div class='cp_table' id='select_article' style='
					<?php if($row["TYPE"] == 2) { ?>
						display:block;
					<?php } else { ?>
						display:none;
					<?php } ?> width:571px;'> 
					<div class='cp_box dotted cp_height25' style="width:528px;">
					<div class='cp_table200'><div class='cp_formfield' style='width: 60px !important;'>Sección:&nbsp;</div>
					<select name='sectionArticle' id='sectionArticle' onchange='openSelectArticle();return false;' style='width:130px;'>
						<?php if($row["TYPE"] == 2) {
							$q_info = "select IDSECTION from ".preBD."articles where ID = " . $row["IDVIEW"];
							$result_info = checkingQuery($connectBD, $q_info);
							$row_info = mysqli_fetch_assoc($result_info);
							$Section = $row_info["IDSECTION"];
						}
						
						$q = "select ID, TITLE from ".preBD."articles_sections where (TYPE = 'article' or TYPE = 'fundacion')";
						$q .= " order by TITLE asc";
						$result = checkingQuery($connectBD, $q);
						$i = 0;
						while($row_sec = mysqli_fetch_assoc($result)) { ?>
							<option value='<?php echo $row_sec["ID"]; ?>'
							<?php if ($Section == $row_sec["ID"]) { ?>
								selected='selected'
							<?php } ?>
							><?php echo $row_sec["TITLE"]; ?></option>
							<?php $sec_art[$i] = $row_sec;
							$i++;
						} ?>
						</select></div>
					<div class='cp_formfield' style='width: 60px !important;'>Artículo:&nbsp;</div>
					<div class='cp_table' id='content_list_articles'>
					
					<?php for($i = 0; $i < count($sec_art); $i++) { ?>
						<div id='articles<?php echo $sec_art[$i]["ID"]; ?>' class='cp_table'
						<?php if($Section == $sec_art[$i]["ID"]) { ?>
							style='display:block;'>
						<?php } else { ?>
							style='display:none;'>
						<?php } ?>
						<select name='Article<?php echo $sec_art[$i]["ID"]; ?>' style='max-width:260px;'>
						<?php $q = "select ID, TITLE from ".preBD."articles where (TYPE = 'article' or TYPE = 'fundacion')";
						$q .= " and IDSECTION = " . $sec_art[$i]["ID"];
						$q .= " and STATUS = 1";
						$q .= " and TRASH = 0";
						$q .= " and (DATE_START <= NOW() or (DATE_START <= NOW() and DATE_END <> DATE_START and DATE_END >= NOW()))";
						$q .= " order by DATE_START desc";
						$result = checkingQuery($connectBD, $q);
						while($row_art = mysqli_fetch_assoc($result)) { ?>
							<option value='<?php echo $row_art["ID"]; ?>'
								<?php if($row["IDVIEW"] == $row_art["ID"]) { ?>
									selected='selected'
								<?php } ?>
							><?php echo $row_art["TITLE"]; ?></option>
						<?php } ?>
					</select></div>
					<?php } ?>
					</div></div>

					</div>
				</div>

	<!-- SECCIONES -->
				<div id='box_section' class='cp_table' style='
					<?php if($row["TYPE"] == 1) { ?>
						display:block;
					<?php } else { ?>
						display:none;
					<?php } ?>				
				float:right; width:571px;'> 
					<div class='cp_box dotted cp_height25' style="width:528px;">
						<div class='cp_formfield' style='width: 60px !important;'>Sección:&nbsp;</div>	
						<select name='Section' id='Section'>
						<?php 
							$q = "select ID, TITLE from ".preBD."articles_sections where (TYPE = 'article' or TYPE = 'fundacion')";
							$q .= " order by TITLE asc";
							$result = checkingQuery($connectBD, $q);
							while($row_sec = mysqli_fetch_assoc($result)) { ?>
								<option value='<?php echo $row_sec["ID"]; ?>'
								<?php if($row["IDVIEW"] == $row_sec["ID"]) { ?>
									selected='selected'
								<?php } ?>
								><?php echo $row_sec["TITLE"]; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
			<?php if(module_active(5)){ ?>	
	<!-- DOCUMENTOS DE DESCARGA -->
				<div class='cp_box' id='select_doc'  style='width:100%;clear:both;<?php if($row["TYPE"] == 9) { ?>
						display:block;
					<?php } else { ?>
						display:none;
					<?php } ?>				
				'> 
				<?php 
					if($row["TYPE"] == 9) {
						$q = "select ".preBD."download_docs.ID as idDoc, 
									".preBD."download_docs.IDDOWNLOAD as idDown, 
									".preBD."downloads.IDSECTION as idSec
								from ".preBD."download_docs 
								inner join ".preBD."downloads on ".preBD."downloads.ID = ".preBD."download_docs.IDDOWNLOAD
							where ".preBD."download_docs.ID = " . $row["IDVIEW"];
						$r = checkingQuery($connectBD, $q);
						$dataDocs = mysqli_fetch_object($r);
					}
				?>
					<div class='cp_box dotted cp_height25' style="width:528px;height: 140px;margin-left:140px;">
						<div class='cp_box'>
							<div class='cp_formfield' style='width: 90px !important;'>Sección:&nbsp;</div>
							<select name='select-doc-level-1' id='select-doc-level-1' style='width:250px;'>
							<?php 
								$q = "select ID, TITLE from ".preBD."download_sections";
								$q .= " order by TITLE asc";
								$result = checkingQuery($connectBD, $q);
								$i = 0;
								while($row_sec = mysqli_fetch_object($result)) { 
							?>
									<option value='<?php echo $row_sec->ID; ?>'<?php if($row_sec->ID == $dataDocs->idSec && $row["TYPE"] == 9){echo " selected='seleceted'";} ?>>
										<?php echo $row_sec->TITLE; ?>
									</option>
							<?php 	$sec_des[$i] = $row_sec;
									$i++;
								} ?>
							</select>
						</div>
						
						<div class='cp_box'>
							<div class='cp_formfield' style='width: 90px !important;'>Descargas:&nbsp;</div>
							<?php 
							$down = array();
							for($i = 0; $i < count($sec_des); $i++) { ?>
								<div id='descargasDoc<?php echo $sec_des[$i]->ID; ?>' class="box-doc-level-2" style='display:none;'>
									<select name='select-doc-level-2-<?php echo $sec_des[$i]->ID; ?>' class='select-doc-level-2' id='select-doc-level-2-<?php echo $sec_des[$i]->ID; ?>' style='width:250px;'>
										<?php 
											$q = "select ID, TITLE from ".preBD."downloads where";
											$q .= " IDSECTION = " . $sec_des[$i]->ID;
											$q .= " and STATUS > 0";
											$q .= " and (DATE_START <= NOW() or (DATE_START <= NOW() and DATE_END <> DATE_START and DATE_END >= NOW()))";
											$q .= " order by DATE_START desc";
											$result = checkingQuery($connectBD, $q);
											$j = 0;
											while($row_art = mysqli_fetch_object($result)) {
												$down[] = $row_art;
											?>
												<option style="width:100%;" value='<?php echo $row_art->ID; ?>'<?php if($row_art->ID == $dataDocs->idDown && $row["TYPE"] == 9){echo " selected='selected'";} ?>><?php echo cutting($row_art->TITLE,50); ?></option>
										<?php $j++;
											} ?>
									</select>
								</div>
							<?php } ?>
							
						</div>
						<div class='cp_box'>
							<div class='cp_formfield' style='width: 90px !important;'>Documentos:&nbsp;</div>
							<?php 
							for($i = 0; $i < count($down); $i++) { ?>
								<div id='box-doc-level-3-<?php echo $down[$i]->ID; ?>' class="box-doc-level-3" style='display:none;'>
									<select name='select-doc-level-3-<?php echo $down[$i]->ID; ?>' id='select-doc-level-3-<?php echo $down[$i]->ID; ?>' style='width:200px;'>
										<?php 
											$q = "select ID, TITLE from ".preBD."download_docs where";
											$q .= " IDDOWNLOAD = " . $down[$i]->ID;
											$q .= " order by POSITION asc";
											$result = checkingQuery($connectBD, $q);
											while($docs = mysqli_fetch_object($result)) {
											?>
												<option style="width:100%;" value='<?php echo $docs->ID; ?>'<?php if($docs->ID == $dataDocs->idDoc && $row["TYPE"] == 9){echo " selected='selected'";} ?>>
													<?php echo cutting($docs->TITLE,50); ?>
												</option>
										<?php } ?>
									</select>
								</div>
							<?php } ?>
							</div>
						</div>
					</div>
				</div>
				
	<!-- DESCARGA -->
				<div class='cp_table' id='select_descarga' style='
					<?php if($row["TYPE"] == 4) { ?>
						display:block;
					<?php } else { ?>
						display:none;
					<?php } ?>				
				float:right; width:571px;'> 
					<div class='cp_box dotted cp_height25' style="width:528px;">
					<div class='cp_table200'><div class='cp_formfield' style='width: 60px !important;'>Sección:&nbsp;</div>
					<select name='sectionDescarga' id='sectionDescarga' onchange='openSelectDescarga();return false;' style='width:130px;'>
						<?php if($row["TYPE"] == 4) {
							$q_info = "select IDSECTION from ".preBD."downloads where ID = " . $row["IDVIEW"];
							$result_info = checkingQuery($connectBD, $q_info);
							$row_info = mysqli_fetch_assoc($result_info);
							$Descarga = $row_info["IDSECTION"];
						}					
					
						$q = "select ID, TITLE from ".preBD."download_sections";
						$q .= " order by TITLE asc";
						$result = checkingQuery($connectBD, $q);
						$i = 0;
						while($row_sec = mysqli_fetch_assoc($result)) { ?>
							<option value='<?php echo $row_sec["ID"]; ?>'><?php echo $row_sec["TITLE"]; ?></option>
							<?php $sec_des[$i] = $row_sec;
							$i++;
						} ?>
						</select></div>
					<div class='cp_formfield' style='width: 60px !important;'>Descarga:&nbsp;</div>
					<div class='cp_table' id='content_list_descargas'>
					<?php 
					for($i = 0; $i < count($sec_des); $i++) { ?>
						<div id='descargas<?php echo $sec_des[$i]["ID"]; ?>' class='cp_table' 
						<?php if($Descarga == $sec_des[$i]["ID"]) { ?>
							style='display:block;'>
						<?php } else { ?>
							style='display:none;'>
						<?php } ?>
						<select name='Descarga<?php echo $sec_des[$i]["ID"]; ?>' style='width:267px;'>
						<?php 
						$q = "select ID, TITLE from ".preBD."downloads where";
						$q .= " IDSECTION = " . $sec_des[$i]["ID"];
						$q .= " and STATUS = 1";
						$q .= " and (DATE_START <= NOW() or (DATE_START <= NOW() and DATE_END <> DATE_START and DATE_END >= NOW()))";
						$q .= " order by DATE_START desc";
						$result = checkingQuery($connectBD, $q);
						while($row_art = mysqli_fetch_assoc($result)) { ?>
							<option style="width:100%;"  value='<?php echo $row_art["ID"]; ?>'
								<?php if($row["IDVIEW"] == $row_art["ID"]) { ?>
									selected='selected'
								<?php } ?>
							><?php echo $row_art["TITLE"]; ?></option>
						<?php } ?>
					</select></div>
					<?php } ?>
					</div></div>
					</div>
				</div>						
				
	<!-- SECCION DESCARGA-->
				<div id='box_descarga' class='cp_table' style='
					<?php if($row["TYPE"] == 3) { ?>
						display:block;
					<?php } else { ?>
						display:none;
					<?php } ?>				
				float:right; width:571px;'> 
					<div class='cp_box dotted cp_height25' style="width:528px;">
						<div class='cp_formfield' style='width: 60px !important;'>Sección:&nbsp;</div>	
						<select name='DescargaSection' id='DescargaSection'>
							<?php $q = "select ID, TITLE from ".preBD."download_sections";
							$q .= " order by TITLE asc";
							$result = checkingQuery($connectBD, $q);
							while($row_sec = mysqli_fetch_assoc($result)) { ?>
								<option value='<?php echo $row_sec["ID"]; ?>'
								<?php if($row["IDVIEW"] == $row_sec["ID"]) { ?>
									selected='selected'
								<?php } ?>
								><?php echo $row_sec["TITLE"]; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>			
			<?php } 
			if(module_active(3)){ ?>	
	<!-- VÍDEO -->
				<div class='cp_table' id='select_video'  style='
					<?php if($row["TYPE"] == 6) { ?>
						display:block;
					<?php } else { ?>
						display:none;
					<?php } ?>				
				float:right; width:571px;'> 
					<div class='cp_box dotted cp_height25' style="width:528px;">
					<div class='cp_table200'><div class='cp_formfield' style='width: 60px !important;'>Sección:&nbsp;</div>
					<select name='sectionVideo' id='sectionVideo' onchange='openSelectVideo();return false;' style='width:130px;'>
						<?php if($row["TYPE"] == 6) {
							$q_info = "select IDGALLERY from ".preBD."videos where ID = " . $row["IDVIEW"];
							$result_info = checkingQuery($connectBD, $q_info);
							$row_info = mysqli_fetch_assoc($result_info);
							$Video = $row_info["IDGALLERY"];
						}		
						
						$q = "select ID, TITLE from ".preBD."videos_gallery";
						$q .= " order by TITLE asc";
						$result = checkingQuery($connectBD, $q);
						$i = 0;
						while($row_sec = mysqli_fetch_assoc($result)) { ?>
							<option value='<?php echo $row_sec["ID"]; ?>'><?php echo $row_sec["TITLE"]; ?></option>
							<?php $sec_vid[$i] = $row_sec;
							$i++;
						} ?>
						</select></div>
					<div class='cp_formfield' style='width: 60px !important;'>Vídeo:&nbsp;</div>
					<div class='cp_table' id='content_list_videos'>
					<?php 
					for($i = 0; $i < count($sec_vid); $i++) { ?>
						<div id='videos<?php echo $sec_vid[$i]["ID"]; ?>' class='cp_table' 
						<?php if($Video == $sec_vid[$i]["ID"]) { ?>
							style='display:block;'>
						<?php } else { ?>
							style='display:none;'>
						<?php } ?>						
						<select name='Video<?php echo $sec_vid[$i]["ID"]; ?>' style='width:267px;'>
						<?php 
						$q = "select ID, TITLE from ".preBD."videos where";
						$q .= " IDGALLERY = " . $sec_vid[$i]["ID"];
						$q .= " and STATUS = 1";
						$q .= " order by ID desc";
						$result = checkingQuery($connectBD, $q);
						while($row_art = mysqli_fetch_assoc($result)) { ?>
							<option style="width:100%;"  value='<?php echo $row_art["ID"]; ?>'
								<?php if($row["IDVIEW"] == $row_art["ID"]) { ?>
									selected='selected'
								<?php } ?>
							><?php echo $row_art["TITLE"]; ?></option>
						<?php } ?>
					</select></div>
					<?php } ?>
					</div></div>
					</div>
				</div>				
				
				
	<!-- SECCION VÍDEOS-->
				<div id='box_video' class='cp_table' style='
					<?php if($row["TYPE"] == 5) { ?>
						display:block;
					<?php } else { ?>
						display:none;
					<?php } ?>				
				float:right; width:571px;'> 
					<div class='cp_box dotted cp_height25' style="width:528px;">
						<div class='cp_formfield' style='width: 60px !important;'>Sección:&nbsp;</div>	
						<select name='VideoSection' id='VideoSection'>
							<?php $q = "select ID, TITLE from ".preBD."videos_gallery";
							$q .= " order by TITLE asc";
							$result = checkingQuery($connectBD, $q);
							while($row_sec = mysqli_fetch_assoc($result)) { ?>
								<option value='<?php echo $row_sec["ID"]; ?>'
								<?php if($row["IDVIEW"] == $row_sec["ID"]) { ?>
									selected='selected'
								<?php } ?>
								><?php echo $row_sec["TITLE"]; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>	
			<?php } ?>	
				
	<!-- GALERÍA -->
				<div class='cp_table' id='select_gallery'  style='
					<?php if($row["TYPE"] == 8) { ?>
						display:block;
					<?php } else { ?>
						display:none;
					<?php } ?>				
				float:right; width:571px;'> 
					<div class='cp_box dotted cp_height25' style="width:528px;">
					<div class='cp_table200'><div class='cp_formfield' style='width: 60px !important;'>Sección:&nbsp;</div>
					<select name='sectionGallery' id='sectionGallery' onchange='openSelectGallery();return false;' style='width:130px;'>
						<?php if($row["TYPE"] == 8) {
							$q_info = "select IDGALLERYSECTION from ".preBD."images_gallery where ID = " . $row["IDVIEW"];
							$result_info = checkingQuery($connectBD, $q_info);
							$row_info = mysqli_fetch_assoc($result_info);
							$Gallery = $row_info["IDGALLERYSECTION"];
						}		
						
						$q = "select ID, TITLE from ".preBD."images_gallery_sections";
						$q .= " order by TITLE asc";
						$result = checkingQuery($connectBD, $q);
						$i = 0;
						while($row_sec = mysqli_fetch_assoc($result)) { ?>
							<option value='<?php echo $row_sec["ID"]; ?>'><?php echo $row_sec["TITLE"]; ?></option>
							<?php $sec_vid[$i] = $row_sec;
							$i++;
						} ?>
						</select></div>
					<div class='cp_formfield' style='width: 60px !important;'>Galería:&nbsp;</div>
					<div class='cp_table' id='content_list_galleries'>
					<?php 
					for($i = 0; $i < count($sec_vid); $i++) { ?>
						<div id='galleries<?php echo $sec_vid[$i]["ID"]; ?>' class='cp_table' 
						<?php if($Gallery == $sec_vid[$i]["ID"]) { ?>
							style='display:block;'>
						<?php } else { ?>
							style='display:none;'>
						<?php } ?>						
						<select name='Gallery<?php echo $sec_vid[$i]["ID"]; ?>' style='width:267px;'>
						<?php 
						$q = "select ID, TITLE from ".preBD."images_gallery where";
						$q .= " IDGALLERYSECTION = " . $sec_vid[$i]["ID"];
						$q .= " order by ID desc";
						$result = checkingQuery($connectBD, $q);
						while($row_art = mysqli_fetch_assoc($result)) { ?>
							<option style="width:100%;"  value='<?php echo $row_art["ID"]; ?>'
								<?php if($row["IDVIEW"] == $row_art["ID"]) { ?>
									selected='selected'
								<?php } ?>
							><?php echo $row_art["TITLE"]; ?></option>
						<?php } ?>
					</select></div>
					<?php } ?>
					</div></div>
					</div>
				</div>				
				
				
	<!-- SECCION ÁLBUMES DE GALERÍAS -->
				<div id='box_gallery' class='cp_table' style='
					<?php if($row["TYPE"] == 7) { ?>
						display:block;
					<?php } else { ?>
						display:none;
					<?php } ?>				
				float:right; width:571px;'> 
					<div class='cp_box dotted cp_height25' style="width:528px;">
						<div class='cp_formfield' style='width: 60px !important;'>Sección:&nbsp;</div>	
						<select name='GallerySection' id='GallerySection'>
							<?php $q = "select ID, TITLE from ".preBD."images_gallery_sections";
							$q .= " order by TITLE asc";
							$result = checkingQuery($connectBD, $q);
							while($row_sec = mysqli_fetch_assoc($result)) { ?>
								<option value='<?php echo $row_sec["ID"]; ?>'
								<?php if($row["IDVIEW"] == $row_sec["ID"]) { ?>
									selected='selected'
								<?php } ?>
								><?php echo $row_sec["TITLE"]; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>					
				
			
	<!-- LINK EXTERNO -->
				<div class='cp_table' id='select_link' style='
					<?php if($row["TYPE"] == -1) { ?>
						display:block;
					<?php } else { ?>
						display:none;
					<?php } ?>
					float:right; width:571px;'> 
					<div class='cp_box dotted cp_height25' style="width:528px;">		
					<?php $link = stripslashes($row["IDVIEW"]); ?>
					
						<input type='text' name='Link' value='<?php echo $link; ?>' style='width:400px;'>
						<div class='cp_table'><select name='Target'>
							<option value='_self'
							<?php if($row["TARGET"] == "_self") { ?>
								selected='selected'
							<?php } ?>
							>Misma ventana</option>
							<option value='_blank'
							<?php if($row["TARGET"] == "_blank") { ?>
								selected='selected'
							<?php } ?>
							>Nueva ventana</option>
						</select></div><br/>
					</div>
				</div>
			</div>
			<div class='cp_table700'>
				<input style="float:right;" type='button' name='save' value='Guardar' onclick='edit_enlace_seleccionado(); showloading(1); validate(this); return false;' />
				<div class='cp_table'><img class='image middle' src='images/loading.gif' style='visibility: hidden; padding: 8px 0px 0px 20px;' id='loading'></div>
			</div>
		</form>
	<script type='text/javascript'>
		includeField('title_link','string');
		
		function edit_enlace_seleccionado(){
			var valor_select = document.getElementById("enlace_item").value;
			var aux2 = document.getElementById('new_image').style.display;

			if((valor_select == 2) || (valor_select == 3)){
				if(aux2 == "block"){
					includeField('red_social','file');
				}else{
					resetFields();
					includeField('title_link','string');				
				}
			}else{	
				resetFields();
				var aux = document.getElementById('box_aux');
				if(aux.style.visibility == "visible"){
					var img2 = document.getElementById('delete_image_item_menu');
					img2.checked = true;
					var img = document.getElementById('Select_image_item_menu');
					img.checked = false;
				}
				document.getElementById('new_image').style.display='none';	
				includeField('title_link','string');
			}
		}				
		
	</script>
		<?php }
		} 
}else{
	echo "<p>No tiene permiso para acceder a esta sección.</p>";
}?>		
		