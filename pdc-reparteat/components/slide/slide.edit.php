<?php if (allowed($mnu)) { ?>
		<div class='cp_mnu_title title_header_mod'>Editar imagen</div>
		<div class="container">
		<div class="row">
	<?php
		if (isset($_GET['msg'])) {
			$msg = $_GET['msg'];
			echo "<div class='cp_info'><img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' />".$msg."</div>\r\n";
		}
		if (isset($_GET['record'])) {
			$id = $_GET['record'];
		}
		else {
			$location = "Location: ../../index.php?mnu=design&com=slide&tpl=create";
			header($location);
		}
		
		$q_record = "SELECT * FROM ".preBD."slider WHERE ID='".$id."'";
		$result_record = checkingQuery($connectBD, $q_record);
		
		$row_record = mysqli_fetch_array($result_record);
		
			$dateStart = new DateTime($row_record["DATE_START"]);
			$dateEnd = new DateTime($row_record["DATE_END"]);
			
			$Status = $row_record['STATUS'];
			$Album = $row_record['IDALBUM'];
			$Title = $row_record['TITLE'];
			$Subtitle = $row_record['SUBTITLE'];
			$Text = $row_record['TEXT'];
			$Image = $row_record['IMAGE'];
			$Link = $row_record['LINK'];
			$Target = $row_record['TARGET']; ?>
			<div class='cp_alert noerror' id='info-Title'></div>
			<div class='cp_alert noerror' id='info'><br/></div>

			<script type="text/javascript">
				window.onload = function(){showloading(0);}
			</script>
			<form method='post' action='modules/slide/edit_banner.php' enctype='multipart/form-data' id='mainform' name='mainform'>
				<div class='cp_box shaded cp_height65'>
					<div class='cp_table200' style='padding:5px;'>
						<div class='cp_table bold'>
							<b>Número:</b> <?php echo $id; ?>
						</div>
						<input type='hidden' id='id' name='id' value='<?php echo $id; ?>'/>
					</div>
					<div class='cp_table200 floatRight'>
						<div class='cp_table50 bold' style='padding:5px;'>
							<label for='Status'>Estado: </label>
						</div>
						<div class='cp_table'>
							<select name='Status' id='Status' width='40'>
								<option value='1'<?php if ($Status == 1) {echo " selected='selected'";} ?>>Publicado</option>
								<option value='0'<?php if ($Status == 0) {echo " selected='selected'";} ?>>Borrador</option>
							</select>
						</div>
					</div>
					<div class="separator10">&nbsp;</div>
					<div class='cp_table' style='width:45%'> 
						<div class='cp_formfield bold'>
							<label for='Date_start_dd'>Fecha / hora inicio:</label>
						</div>
						<input maxlength="100" size="12" value="<?php echo $dateStart->format("d-m-Y"); ?>" name="date_day" id="date_day" readonly="readonly" />
						<div class='cp_formfield_s'>/</div>
						<input type='text' name='Date_start_hh' id='Date_start_hh' size='1' value='<?php echo $dateStart->format("H"); ?>'/>
						<div class='cp_formfield_xs'>:</div>
						<input type='text' name='Date_start_ii' id='Date_start_ii' size='1' value='<?php echo $dateStart->format("i"); ?>'/>
					</div>
					<div class='cp_table' style='width:45%'> 
						<?php if($dateStart->getTimestamp() != $dateEnd->getTimestamp()): ?>
							<div class='cp_formfield bold'>
								<label for='Date_end_dd'>Fecha / hora fin:</label>
								<input type="checkbox" name="controlDateEnd" id="controlDateEnd" style="margin-top:0px;margin-left:5px;" checked="checked" />
							</div>
							<div id="boxDateEnd" style="display:block;">
								<input maxlength="100" size="12" value="<?php echo $dateEnd->format("d-m-Y"); ?>" name="date_day_finish" id="date_day_finish" readonly="readonly" />
								<div class='cp_formfield_s'>/</div>
								<input type='text' name='Date_end_hh' id='Date_end_hh' size='1' value='<?php echo $dateEnd->format("H"); ?>' />
								<div class='cp_formfield_xs'>:</div>
								<input type='text' name='Date_end_ii' id='Date_end_ii' size='1' value='<?php echo $dateEnd->format("i"); ?>' />
							</div>
						<?php else: ?>
							<div class='cp_formfield bold'>
								<label for='Date_end_dd'>Fecha / hora fin:</label>
								<input type="checkbox" name="controlDateEnd" id="controlDateEnd" style="margin-top:0px;margin-left:5px;" />
							</div>
							<div id="boxDateEnd" style="display:none;">
								<input maxlength="100" size="12" value="<?php echo $dateEnd->format("d-m-Y"); ?>" name="date_day_finish" id="date_day_finish" readonly="readonly" disabled="disabled" />
								<div class='cp_formfield_s'>/</div>
								<input type='text' name='Date_end_hh' id='Date_end_hh' size='1' value='<?php echo $dateEnd->format("H"); ?>' disabled="disabled" />
								<div class='cp_formfield_xs'>:</div>
								<input type='text' name='Date_end_ii' id='Date_end_ii' size='1' value='<?php echo $dateEnd->format("i"); ?>' disabled="disabled" />
							</div>
						<?php endif; ?>
					</div>
			
					
				</div>	
				<div class='cp_box cp_height20'>
					<div class='cp_table650'>
						<div class='cp_formfield bold' style="width:121px;">
							<label for='Album'>Banner:</label>
						</div>
						<div class='cp_formfield cp_height35'>
						<?php 
							$qA = "select * from ".preBD."slider_gallery where ID = '" . $Album."'";		
							$resultA = checkingQuery($connectBD, $qA);
							$info_album = mysqli_fetch_object($resultA);	
							$aux = $info_album->ID;
							$ancho = $info_album->WIDTH;
							$alto = $info_album->HEIGHT;
						?>
						
							<select name='Album' id='Album' style='width:300px;margin-top:-5px;' onchange='viewSizeImg();return false;'>
							<?php
							$q = "SELECT ID, TITLE, WIDTH, HEIGHT FROM ".preBD."slider_gallery order by TITLE asc" ;
							$result = checkingQuery($connectBD, $q);
							$info_album = array();
							$i = 0;
							while($row = mysqli_fetch_object($result)) {
								$info_album[$i]["id"] = $row->ID;
								$info_album[$i]["w"] = $row->WIDTH;
								$info_album[$i]["h"] = $row->HEIGHT;
								$i++; ?>
								<option value='<?php echo $row->ID; ?>' <?php if($row->ID == $aux){ ?> selected <?php }?>><?php echo stripslashes($row->TITLE); ?></option>
							<?php } ?>
							</select>
						</div>
					</div>
				</div>		

			
				<div class='cp_box dotted cp_height380'>
					<div class='cp_table cp_height35'>
						<div class='cp_table120 bold'>
							<label for='Title'>T&iacute;tulo:*</label>
						</div>
						<input type='text' name='Title' id='Title' title='Title' size='79' value='<?php echo $Title; ?>' />
					</div>
					<div class='cp_table cp_height30'>
						<div class='cp_table120 bold'>
							<label for='Subtitle'>Subt&iacute;tulo:*</label>
						</div>
						<input type='text' name='Subtitle' id='Subtitle' title='Subtitle' size='81' value='<?php echo $Subtitle; ?>' />
					</div>
					<?php require_once("js/jscripts/tiny_mce/tiny_mce_small.php"); ?>	
				
					<div class='cp_table cp_height170'>
						<div class='cp_table bold top' style='width: 120px'>
							<label for='Text'>Descripción:</label>
						</div>
						<div class='cp_table right'>
							<textarea name='Text' id='Text' class='spl_editable' style='width:520px;height:150px;'><?php echo $Text; ?></textarea>
						</div>
					</div>
					<div class='cp_table cp_height45'>
						<div class='cp_table120 bold' style='padding-top:5px;'>
							<label for='Link'>Url del enlace:</label>
						</div>
						<input type='text' name='Link' id='Link' title='Link' size='72'<?php if($Target == "_none") {echo " value=''";} else {echo " value='".$Link."'";} ?> />
						<div class='cp_table'>
							<select name='Target' id='Target' width='40'>
								<option value='_none'<?php if ($Target == "_none") {echo " selected='selected'";} ?>>Sin enlace</option>
								<option value='_self'<?php if ($Target == "_self") {echo " selected='selected'";} ?>>Misma ventana</option>
								<option value='_blank'<?php if ($Target == "_blank") {echo " selected='selected'";} ?>>Nueva ventana</option>
							</select>
						</div>
					</div>
					<br/>
					<div class="separator5"></div>
					<div class='cp_table120 bold'>
						<label for='Image2'>Imagen:</label>
					</div>
					<div class='cp_table220 left' style='max-height:100px;overflow:hidden;'>
						<a href='../files/slide/image/<?php echo $Image; ?>' class='lytebox' data-title='<?php echo $Title; ?>'>
							<img class='image' src='../files/slide/image/<?php echo $Image; ?>' alt='Zoom' title='Zoom' width='180' />
						</a>
						<input type='hidden' name='Image1' value='<?php echo $Image; ?>'>
					</div>
					<div class='cp_table300'>
						<div class='cp_table200 cp_height45'>
							<label for='Image2'>Cambiar imagen</label>
						</div>
						<input type='file' name='Image2' id='Image2' size='20' />
						<div class='cp_table' style='margin-top:10px;color:#a50000;'>
							JPG o GIF o PNG de <?php echo $ancho; ?> x <?php echo $alto; ?> px
						</div>
					</div>
		<?php // CIERRE DEL DOTTED ?>
				</div>	
				<div class='cp_table'>
					<div class='cp_table400 right top'>
						<img class='image middle' src='images/loading.gif' style='visibility: hidden; padding: 10px 20px 0px 0px;' id='loading'>
					</div>
					<div class='cp_table230 align_ie'>&nbsp;</div>
					<input type='submit' value='Guardar' onclick='showloading(1); validate(this);' />
				</div>
			
			</form>
			<script type='text/javascript'>
				includeField('Title','string');
			</script>
		</div><!-- cierre row-->
	</div><!-- cierre container-->
<?php 
}else{
	echo "<p>No tiene permiso para acceder a esta sección.</p>";
}
?>	