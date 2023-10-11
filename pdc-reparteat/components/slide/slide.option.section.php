<?php if (allowed($mnu)) { ?>
	<div class='cp_mnu_title'>Listado de banners</div>
	<div class="container">
	<div class="row">
	
	<?php 
		if (isset($_GET['msg'])) {
			$msg = utf8_encode($_GET['msg']); ?>
			<div class='cp_info'>
				<img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' />
				<p><?php echo $msg; ?></p>
			</div>
		<?php 
		}
		include ("components/slide/slide.list.section.php");
?>
		<div class="separator30"></div>
<?php
		if (isset($_GET['action'])) {
			$action = $_GET['action'];
			$album = $_GET['album'];
				
			$q = "SELECT * FROM ".preBD."slider_gallery WHERE ID = '" . $album . "'";
			//pre($q);
			$result = checkingQuery($connectBD, $q);
			$row = mysqli_fetch_object($result);
			
			$title = stripslashes($row->TITLE);
			$description = stripslashes($row->DESCRIPTION);
			$width = $row->WIDTH;
			$height = $row->HEIGHT;
			$time_pause = $row->PAUSE_SECONDS;
			$speed = $row->SPEED;


	// CHANGE SIZE
			if ($action == 'Changesize') { ?>
				<div class='cp_alert'>
					<img class='cp_msgicon' src='images/alert.png' alt='¡INFORMACIÓN!' />
					<p>¡ATENCIÓN! Va a cambiar el tamaño de las imágenes para el banner <?php echo $title; ?></p>
				</div>
				<div class='cp_alert noerror' id='info-width'></div>
				<div class='cp_alert noerror' id='info-height'></div>
				<br/>
				<form method='post' action='modules/slide/changesize_album.php' name='mainform' id='mainform'>
				
					<div class='cp_table650'>
						<div class='cp_formfield'>
							<label for='thumb_width'>Ancho en píxeles:</label>
						</div>
						<input type='text' name='width' id='width' title='Ancho' size='3' value='<?php echo $width; ?>'/>
					</div>
					
					<div class='cp_table650'>
						<div class='cp_formfield'>
							<label for='thumb_height'>Alto en píxeles:</label>
						</div>
						<input type='text' name='height' id='height' title='Alto' size='3' value='<?php echo $height; ?>'/>
					</div>
					
					<input type='hidden' name='album' value='<?php echo $album; ?>' />
					<div class='cp_formfield'>&nbsp;</div>
					<input type='button' value='Cambiar' onclick='validate(this); return false;' />
				</form>
				<script type='text/javascript'>
					includeField('width','number');
					includeField('height','number');
				</script>
			<?php }

	// CREATE ALBUM
			else if ($action == 'Createbanner') { ?>
				
				<div class='cp_info'>
					<img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' />
					<p>¡ATENCIÓN! Va a crear un nuevo banner</p>
				</div>
				<div class='cp_alert noerror' id='info-title'></div>
				<br/>
				<form method='post' action='modules/slide/create_album.php' name='mainform' id='mainform'>
					<div class='cp_table650'>
						<div class='cp_formfield'>
							<label for='section'>Nuevo banner:</label>
						</div>
						<input type='text' name='title' id='title' title='Título' size='64' />
					</div>
				
					<div class='cp_table650' style='margin-bottom:15px;'>
						<div class='cp_description'>
							<label for='description'>Descripci&oacute;n:</label>
						</div>
						<textarea style='margin-left:-2px;' name='description' id='description' title='Descripción' rows='2' cols='66'></textarea>
					</div>
					<div class='cp_formfield'>&nbsp;</div>
					<input type='button' value='Crear' onclick='validate(this); return false;' />
				</form>
				<script type='text/javascript'>
					includeField('title','string');
				</script>
			<?php }
	// EDIT ALBUM
			else if ($action == 'Editbanner') { ?>
				<div class='cp_alert'>
					<img class='cp_msgicon' src='images/alert.png' alt='¡INFORMACIÓN!' />
					<p>¡ATENCIÓN! Va a editar el banner <?php echo $title; ?></p>
				</div>
				<div class='cp_alert noerror' id='info-title'></div>
				<br/>
				<form method='post' action='modules/slide/edit_album.php' name='mainform' id='mainform'>
					<input type='hidden' name='album' value='<?php echo $album; ?>' />
					<div class='cp_table650'>
						<div class='cp_formfield'>
							<label for='section'>Título:</label>
						</div>
						<input type='text' name='title' id='title' title='Título' size='64' value='<?php echo $title; ?>'/>
					</div>
					<div class='cp_table650' style='margin-bottom:15px;'>
						<div class='cp_description'>
							<label for='description'>Descripci&oacute;n:</label>
						</div>
						<textarea style='margin-left:-2px;' name='description' id='description' title='Descripción' rows='2' cols='66'><?php echo $description; ?></textarea>
					</div>
					<div class='cp_formfield'>&nbsp;</div>
					<input type='button' value='Editar' onclick='validate(this); return false;' />
				</form>
				<script type='text/javascript'>
					includeField('title','string');
				</script>
			<?php }	else if ($action == 'Editpause') { ?>
				<div class='cp_alert'>
					<img class='cp_msgicon' src='images/alert.png' alt='¡INFORMACIÓN!' />
					<p>¡ATENCIÓN! Va a editar el tiempo de pausa entre imágenes del banner <?php echo $title; ?></p>
				</div>
				<div class='cp_alert noerror' id='info-pausa'></div>
				<br/>
				<form method='post' action='modules/slide/edit_time_pause.php' name='mainform' id='mainform'>
					<input type='hidden' name='album' value='<?php echo $album; ?>' />
					<div class='cp_table650'>
						<div class='cp_formfield'>
							<label for='section'>Tiempo pausa:</label>
						</div>
						<input type='text' name='pausa' id='pausa' title='Pausa' size='4' value='<?php echo $time_pause; ?>'/>
					</div>
					<div class='cp_formfield'>&nbsp;</div>
					<input type='button' value='Editar' onclick='validate(this); return false;' />
				</form>
				<script type='text/javascript'>
					includeField('pausa','string');
				</script>
			<?php }	else if ($action == 'Editspeed') { ?>
				<div class='cp_alert'>
					<img class='cp_msgicon' src='images/alert.png' alt='¡INFORMACIÓN!' />
					<p>¡ATENCIÓN! Va a editar la velocidad de transición entre imágenes del banner <?php echo $title; ?></p>
				</div>
				<div class='cp_alert noerror' id='info-speed'></div>
				<br/>
				<form method='post' action='modules/slide/edit_speed.php' name='mainform' id='mainform'>
					<input type='hidden' name='album' value='<?php echo $album; ?>' />
					<div class='cp_table650'>
						<div class='cp_formfield'>
							<label for='section'>Velocidad transición:</label>
						</div>
						<input type='text' name='speed' id='speed' title='Velocidad' size='4' value='<?php echo $speed; ?>'/>
						&nbsp;<span>(Formato X.X)</span>
					</div>
					<div class='cp_formfield'>&nbsp;</div>
					<input type='button' value='Editar' onclick='validate(this); return false;' />
				</form>
				<script type='text/javascript'>
					includeField('speed','string');
				</script>
			<?php }			
			
		}
?>
	</div>
	</div>
<?php		
}else{
	echo "<p>No tiene permiso para acceder a esta sección.</p>";
}?>	