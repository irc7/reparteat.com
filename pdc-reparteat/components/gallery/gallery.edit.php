<?php if (allowed($mnu)) { ?>
	<div class='cp_mnu_title title_header_mod'>Editar imagen</div>
	<?php
		if (isset($_GET['msg'])) {
			$msg = utf8_encode($_GET['msg']); ?>
			<div class='cp_info'>
				<img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' />
				<?php echo $msg; ?>
			</div>
		<?php }
		if (isset($_GET['record'])) {
			$id = $_GET['record'];
		}
		else {
			$location = "Location: ../index.php?mnu=content&com=gallery&tpl=create";
			header($location);
		}
		
		$q_record = "SELECT * FROM ".preBD."images WHERE ID='" . $id . "'";
		
		$result_record = checkingQuery($connectBD, $q_record);
		$row_record = mysqli_fetch_array($result_record);
		$Author = trim($row_record['AUTHOR']);
		$Gallery= $row_record['IDGALLERY'];
		$Title = stripslashes($row_record['TITLE']);
		$Text = stripslashes($row_record['TEXT']);
		$Url = $row_record['URL'];
		$Status = $row_record['STATUS']; ?>
		
			<div class='cp_alert noerror' id='info-Title'></div>
			<br/>
					
			<form id='edit_reports' method='post' action='modules/gallery/edit_image.php?record=<?php echo $id; ?>' enctype='multipart/form-data' id='mainform' name='mainform'>
			<input type="hidden" name="mnu" value="<?php echo $mnu; ?>" />
			<div class='cp_box shaded cp_height55'>
				<div class='cp_table400'>
					<div class='cp_formfield bold'>Autor:</div>
					<div class='cp_table250' style='padding-top:5px;'>
						<?php echo $Author; ?>
					</div>
					<input type='hidden' name='Author' value='<?php echo $_SESSION[PDCLOG]['Login']; ?>'/>
				</div>
				
				<div class='cp_table280'>
					<div class='cp_formfield_m floatRight top right'>
						<?php echo $id; ?>
					</div>
					<div class='cp_formfield_ls bold floatRight top'>
						<label class='right' for='Number'>Número:</label>
					</div>
					<input type='hidden' id='Number' name='Number' value='<?php echo $id; ?>'/>
				</div>
				
				<div class='cp_table160' style='float:right'>
					<div class='cp_table60 bold'>
						<label for='Status' style='padding-top:5px;'>Estado:</label>
					</div>
					<div class='cp_formfield_l' style='margin-top:0px;'>
						<select name='Status' id='Status' width='40'>
							<option value='1'<?php if ($Status == 1){echo " selected='selected'"; } ?>>Publicado</option>
							<option value='0'<?php if ($Status == 0) {echo " selected='selected'";} ?>>Borrador</option>
						</select>
					</div>
				</div>
			</div>
			
			<div class='cp_box cp_height140'>
				<div class='cp_table650 cp_height40'>
					<div class='cp_formfield bold' style='padding-top:3px;'>
						<label for='Gallery'>Galería:</label>
					</div>
					<div class='cp_table'>
						<select name='Gallery' id='Gallery'>
						<?php 
						$q = "SELECT * FROM ".preBD."images_gallery";
						$result = checkingQuery($connectBD, $q);
						while($row = mysqli_fetch_array($result)):
							$Gallery_number = $row['ID']; ?>
							<option value='<?php echo $Gallery_number; ?>'<?php if ($Gallery == $Gallery_number) {echo " selected='selected'";} ?>><?php echo $row['TITLE']; ?></option>
						<?php endwhile; ?>
						</select>
					</div>
				</div>
			
				<div class='cp_table'>
					<div class='cp_formfield bold'>
						<label for='Title'>Título*:</label>
					</div>
					<input type='text' name='Title' id='Title' title='Título' style='width: 525px;' value='<?php echo $Title; ?>'/>
				</div>
			
				<div class='cp_table'>
					<div class='cp_formfield bold'>
						<label for='Text'>Descripción:</label>
					</div>
					<textarea name='Text' id='Text' title='Text' style='width: 525px;height:74px;' /><?php echo $Text; ?></textarea>
				</div>
			
			</div>
			<div id='document_record' class='cp_box dotted cp_height150' style="height:190px;">
				<div class='cp_mnu_title'>
					<p class='cp_title_name' style='margin-bottom:10px !important'>Imagen </p>
				</div>
				<?php 
				if ($Url != "") {
					$name_image = explode("-", $Url, 2); ?>
					
					<div class='cp_table' style='margin-left:15px;'>
						<a href='../files/gallery/image/<?php echo $Url; ?>' class='lytebox' data-title="<strong><?php echo $Title; ?></strong><br/><?php echo $Text; ?>">
							<img src='../files/gallery/thumb/<?php echo $Url; ?>' style='border:none;' height="130"/>
						</a>
					</div>
					<div class='cp_table400' style='margin-left:10px;'>
						<?php echo $name_image[1]; ?>
					</div>
					
					<div class='cp_table400 cp_height120'>
						<div class='cp_table' style='margin-top:10px;margin-left:10px;width:500px;'>
							<div class='cp_table120'>Modificar miniatura:</div>
							<input id='select_min' type='checkbox' name='Select_Min' onClick='openNexImgMin();' />
						</div>
						<div class='cp_table' style='margin-top:10px;margin-left:10px;width:500px;'>
							<div class='cp_table120'>Modificar imagen:</div>
							<input id='select_image' type='checkbox' name='Select_Image' onClick='openNexImg();' />
						</div>
						
						<input type='file' name='Url' id='Url' style='width:400px;display:none;margin-left: 10px;margin-top: 5px;' />
					</div>
				<?php 
				} else { ?>
					<div class='cp_table300' style='margin-left:50px;'>
						<div class='cp_formfield_l bold'>
							<label for='Url_image'>Nueva imagen:</label>
						</div>
						<input type='file' name='Url_image' id='Url_image' size='20' /></div>
					</div>
				<?php } ?>	
			</div>
			<div class='cp_table700' style='clear:both;'>
				<div class='cp_table350 right'>
					<img class='image middle' src='images/loading.gif' style='visibility: hidden; padding: 10px 20px 0px 0px;' id='loading'>
				</div>
				<div class='cp_table250'>&nbsp;</div>
				<div class='cp_table75'>
					<input type='submit' name='save' value='Guardar' onclick='showloading(1); validate(this); return false;' />
				</div>
			</div>
			</form>
			<script type='text/javascript'>
				includeField('Title','string');
			</script>
		
<?php 	
}else{
	echo "<p>No tiene permiso para acceder a esta sección.</p>";
}?>	