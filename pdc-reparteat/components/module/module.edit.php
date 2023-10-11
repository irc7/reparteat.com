<?php
	if (allowed($mnu) && ($_SESSION[PDCLOG]['Login'] == "webmaster@ismaelrc.es") && ($_SESSION[PDCLOG]['Type'] == 4)){ ?>
		<div class='cp_mnu_title cp_mnu_title title_header_mod'>Editar módulo</div>
		<?php
			if (isset($_GET['msg'])) {
				$msg = utf8_encode($_GET['msg']);
				echo "<div class='cp_info'><img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' /><p>".$msg."</p></div>\r\n";
			}

			if (isset($_GET['module'])) {
				$id = $_GET['module'];	
			} else {
				$location = "Location: ../index.php?mnu=configuration&com=module&tpl=option";
				header($location);
			}		

			$q_module = "SELECT * FROM ".preBD."configuration_modules WHERE ID='".$id."'";
			
			$result_module = checkingQuery($connectBD, $q_module);
			$row_module = mysqli_fetch_array($result_module);
				
			$permission = $row_module['PERMISSION'];
			$title_module = $row_module['MODULE'];
			$id_menu = $row_module['IDMENU'];		
			$Image = $row_module['IMAGE']; ?>
			
			<div class='cp_alert noerror' id='info-Title'></div>
				<div class='cp_alert noerror' id='info'><br/></div>

				<form method='post' action='modules/module/edit_module.php' enctype='multipart/form-data' id='mainform' name='mainform'>
					<div class='cp_box shaded cp_height25'>
						<div class='cp_table floatLeft' style='padding:5px;'>
							<div class='cp_table bold'>
								<b>Número:</b> <?php echo $id; ?>
							</div>
							<input type='hidden' id='id' name='id' value='<?php echo $id; ?>'/>
						</div>
					</div>			
				
					<div class='cp_box dotted cp_height150'>
						<div class='cp_table cp_height35' style="float:left;">
							<div class='cp_table120 bold'>
								<label for='Title'>T&iacute;tulo:*</label>
							</div>
							<input type='text' name='Title' id='Title' title='Title' size='79' value='<?php echo $title_module; ?>' />
						</div>		
						<div class='cp_table cp_height45' style="float:left;clear:both;">
							<div class='cp_table120 bold' style='padding-top:5px;'>
								<label for='level_permission'>Nivel de permiso:</label>
							</div>
							<div class='cp_table'>
								<select name='Permission' id='Permission' width='40'>
									<option value='0' <?php if ($permission == 0) {echo " selected='selected'";} ?>>0</option>
									<option value='1' <?php if ($permission == 1) {echo " selected='selected'";} ?>>1</option>
									<option value='2' <?php if ($permission == 2) {echo " selected='selected'";} ?>>2</option>
									<option value='3' <?php if ($permission == 3) {echo " selected='selected'";} ?>>3</option>
								</select>
							</div>
						</div>
						<span style="margin-left:10px; position:relative; top:5px; float:left; color:#a50000; font-size:10px;">0 = inactivo / 1 = nivel más restringido / 2 = nivel medio / 3 = nivel menos restringido</span>				
						<br/>
						<div class='cp_table120 bold' style="float:left;clear:both;">
							<label for='Image2'>Imagen:</label>
						</div>
						<div class='cp_table100 left' style='max-height:100px;overflow:hidden;'>
							<?php if($Image != ""){ ?>
								<img class='image' src='images/modules/<?php echo $Image; ?>' width='24' />
								<input type='hidden' name='Image1' value='<?php echo $Image; ?>'>
							<?php } ?>
						</div>
						<div class='cp_table300'>
							<div class='cp_table200 cp_height45'>
								<label for='Image2'>Cambiar imagen</label>
							</div>
							<input type='file' name='Image2' id='Image2' size='20' />
							<div class='cp_table' style='margin-top:10px;color:#a50000;'>
								JPG o GIF o PNG de 24 x 24 px
							</div>
						</div>
						
			<?php // CIERRE DEL DOTTED ?>
					</div>	
					<div class='cp_table'>
						<div class='cp_table400 right top'>
							<img class='image middle' src='images/loading.gif' style='visibility: hidden; padding: 10px 20px 0px 0px;' id='loading'>
						</div>
						<div class='cp_table230 align_ie'>&nbsp;</div>
						<input type='submit' value='Guardar' onclick='validate(this);return false;' />
					</div>
				
				</form>
				<script type='text/javascript'>
					includeField('Title','string');
				</script>
	<?php 
	}else{
		echo "<p>No tiene permiso para acceder a esta sección.</p>";
	}?>