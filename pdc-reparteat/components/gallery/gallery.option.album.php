<?php if (allowed($mnu)) { ?>
		<div class='cp_mnu_title'>Álbumes de galer&iacute;as</div>
	<?php
		include ("components/gallery/gallery.list.album.php");
		if (isset($_GET['action'])) {
			$action = $_GET['action'];
			$section = $_GET['section'];
				
			$q = "SELECT * FROM ".preBD."images_gallery_sections WHERE ID = '" . $section . "'";

			$result = checkingQuery($connectBD, $q);
			$row = mysqli_fetch_array($result);
			
			
			$title = $row['TITLE'];
			$title_seo = $row['TITLE_SEO'];
			$description = $row['DESCRIPTION'];
			

	// CREATE ALBUM
			if ($action == 'Createsection') { ?>
				<div class='cp_info'>
					<img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' />
					¡ATENCIÓN! Va a crear un nuevo álbum
				</div>
				<div class='cp_alert noerror' id='info-Section'></div>
				<br/>
				<form method='post' action='modules/gallery/create_album.php' name="mainform" id="mainform">
					<input type="hidden" name="mnu" value="<?php echo $mnu; ?>" />
					<div class='cp_table650'>
						<div class='cp_formfield'>
							<label for='Section'>Título:</label>
						</div>
						<input type='text' name='Section' id='Section' title='Título' size='66' />
					</div>
				
					<div class="cp_table650">
						<div class="cp_formfield">
							<label for="Title_seo">Título SEO:</label>
						</div>
						<input type="text" name="Title_seo" id="Title_seo" size="66" />
					</div>			
				
					<div class='cp_formfield'>&nbsp;</div>
					<input type='button' value='Crear álbum' onclick='validate(this); return false;' />
				</form>
				<script type='text/javascript'>
					includeField('Section','string');
				</script>
			<?php }
	// EDIT ALBUM
			else if ($action == 'Editsection') { ?>
				<div class='cp_alert'>
					<img class='cp_msgicon' src='images/alert.png' alt='¡INFORMACIÓN!' />
					¡ATENCIÓN! Va a editar el álbum <?php echo $title; ?>
				</div>
				<div class='cp_alert noerror' id='info-Title'></div>
				<br/>
				<form method='post' action='modules/gallery/edit_album.php' name="mainform">
					<input type="hidden" name="mnu" value="<?php echo $mnu; ?>" />
					<input type='hidden' name='section' value='<?php echo $section; ?>' />
					<div class='cp_table650'>
						<div class='cp_formfield'>
							<label for='newalbum'>Título:</label>
						</div>
						<input type='text' name='Title' id='Title' title='Título' value='<?php echo $title; ?>' size='66' />
					</div>
				
					<div class="cp_table650">
						<div class="cp_formfield">
							<label for="Title_seo">Título SEO:</label>
						</div>
						<input type="text" name="Title_seo" id="Title_seo" size="66" value="<?php echo $title_seo; ?>" />
					</div>				
					
					<div class='cp_table650'>
						<div class='cp_formfield'>&nbsp;</div>
						<input type='button' value='Guardar álbum' onclick='validate(this); return false;' />
					</div>
				</form>
				<script type='text/javascript'>
					includeField('Title', 'string');
				</script>
			<?php }
		}
		else {
			$msg = NULL;
		}
		if (isset($_GET['msg'])) {
			$msg = utf8_encode($_GET['msg']); ?>
			<div class='cp_info'>
				<img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' />
				<?php echo $msg; ?>
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