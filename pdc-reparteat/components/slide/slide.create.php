<?php if (allowed($mnu)) { ?>
		<div class='cp_mnu_title title_header_mod'>Nueva imagen</div>
		<div class="container">
		<div class="row">
	<?php $Author = $_SESSION[PDCLOG]['Login']; ?>
			<div class='cp_alert noerror' id='info-Title'></div>
			<div class='cp_alert noerror' id='info-Image'></div>
			<form method='post' action='modules/slide/create_banner.php' enctype='multipart/form-data' id='mainform' name='mainform'>
				<div class='cp_box shaded cp_height65'>
					<div class='cp_table200' style="float:right;">
						<div class='cp_table120 bold'>
							<label for='Status'>Estado: </label>
						</div>
						<div class='cp_table'>
							<select name='Status' id='Status' width='40'>
								<option value='1'>Publicado</option>
								<option value='0' selected='selected'>Borrador</option>
							</select>
						</div>
					</div>
					<div class='cp_table' style='width:45%'> 
						<div class='cp_formfield bold'>
							<label for='Date_start_dd'>Inicio publicación:</label>
						</div>
						<input maxlength="100" size="12" value="<?php echo date("d-m-Y"); ?>" name="date_day" id="date_day" readonly="readonly" />
						<div class='cp_formfield_s'>/</div>
						<input type='text' name='Date_start_hh' id='Date_start_hh' size='1' value='<?php echo date("H"); ?>'/>
						<div class='cp_formfield_xs'>:</div>
						<input type='text' name='Date_start_ii' id='Date_start_ii' size='1' value='<?php echo date("i"); ?>'/>
					</div>
					<div class='cp_table' style='width:100%'> 
						<div class='cp_formfield bold'>
							<label for='Date_end_dd'>Final publicación:</label>
							<input type="checkbox" name="controlDateEnd" id="controlDateEnd" style="margin-top:0px;margin-left:5px;" />
						</div>
						<div id="boxDateEnd" style="display:none;">
							<input maxlength="100" size="12" value="<?php echo date("d-m-Y"); ?>" name="date_day_finish" id="date_day_finish" readonly="readonly" disabled="disabled" />
							<div class='cp_formfield_s'>/</div>
							<input type='text' name='Date_end_hh' id='Date_end_hh' size='1' value='00' disabled="disabled" />
							<div class='cp_formfield_xs'>:</div>
							<input type='text' name='Date_end_ii' id='Date_end_ii' size='1' value='00' disabled="disabled" />
						</div>
					</div>
				</div>
				<br/>
			
				<div class='cp_box cp_height20'>
					<div class='cp_table650'>
						<div class='cp_formfield bold'>
							<label for='Album'>Banner:</label>
						</div>
						<div class='cp_formfield cp_height35'>
							<select name='Album' id='Album' style='width:300px;margin-top:-5px;' onchange='viewSizeImg();return false;'>
							<?php 
							$q = "SELECT ID, TITLE, WIDTH, HEIGHT FROM ".preBD."slider_gallery order by ID desc" ;
							$result = checkingQuery($connectBD, $q);
							$info_album = array();
							$i = 0;
							while($row = mysqli_fetch_object($result)) {
								$info_album[$i]["id"] = $row->ID;
								$info_album[$i]["w"] = $row->WIDTH;
								$info_album[$i]["h"] = $row->HEIGHT;
								$i++; ?>
								<option value='<?php echo $row->ID; ?>'><?php echo stripslashes($row->TITLE); ?></option>
							<?php } ?>
							</select>
						</div>
					</div>
				</div>
			
				<div class='cp_box dotted cp_height350'>
					<div class='cp_table cp_height30'>
						<div class='cp_table120 bold'>
							<label for='Title'>T&iacute;tulo:*</label>
						</div>
						<input type='text' name='Title' id='Title' title='Title' size='81' />
					</div>
					<div class='cp_table cp_height30'>
						<div class='cp_table120 bold'>
							<label for='Subtitle'>Subt&iacute;tulo:*</label>
						</div>
						<input type='text' name='Subtitle' id='Subtitle' title='Subtitle' size='81' />
					</div>
					<?php require_once("js/jscripts/tiny_mce/tiny_mce_small.php"); ?>	
					<div class='cp_table cp_height170'>
						<div class='cp_table120 bold top'>
							<label for='Text'>Descripción:</label>
						</div>
						<div class='cp_table540'>
							<textarea name='Text' id='Text' class='spl_editable' style='width:520px;height:150px;'></textarea>
						</div>
					</div>
					<div class='cp_table15'>&nbsp;</div>
					<div class='cp_table'>
						<div class='cp_table120 bold'>
							<label for='Link'>Url del enlace:</label>
						</div>

						<input value="http://" type='text' name='Link' id='Link' title='Link' size='72'	/>	
						<div class='cp_table'>
							<select name='Target' id='Target' width='40'>
								<option value='_none' selected='selected'>Sin enlace</option>
								<option value='_self'>Misma ventana</option>
								<option value='_blank'>Nueva ventana</option>
							</select>
						</div>
						<br/>
						<div style='height: 15px;display:block;'></div>
			
						<div class='cp_table' style='width:670px;'>
							<div class='cp_table120 bold'>
								<label for='Image'>Imagen:</label>
							</div>
							<div class='cp_table'>
								<input type='file' name='Image' id='Image' title='Image' size='20' />
							</div>
						</div>
						<br/>
						<div class='cp_table120'>&nbsp;</div>
						<div id='infoSize' class='cp_table' style='margin-top:10px;color:#a50000;'>
							JPG o GIF o PNG de <?php echo $info_album[0]["w"]; ?> x <?php echo $info_album[0]["h"]; ?> px
						</div>
						<script type="text/javascript">
							function viewSizeImg(){
								var selector = document.getElementById("Album");
								
								var info = new Array(); 
								<?php for($j=0;$j<count($info_album);$j++): ?>
									info[<?php echo $info_album[$j]["id"]; ?>] = "JPG o GIF o PNG de <?php echo $info_album[$j]["w"]; ?> x <?php echo $info_album[$j]["h"]; ?> px";
								<?php endfor; ?>	
								document.getElementById("infoSize").innerHTML = info[selector.value];
							}
						</script>
					</div>
				</div>
				<br/>
				
				<div class='cp_table'>
					<div class='cp_table400 right top'>
						<img class='image middle' src='images/loading.gif' style='visibility: hidden; padding: 10px 20px 0px 0px;' id='loading'>
					</div>
					<div class='cp_table230 align_ie'>&nbsp;</div>
					<input type='button' value='Guardar' onclick='showloading(1); validate(this);' />
				</div>
		
			</form>
			<script type='text/javascript'>
				includeField('Title','string');
				includeField('Image','string');
				//includeField('Link','url');
			</script>
		</div><!-- cierre row-->
	</div><!-- cierre container-->
<?php 
}else{
	echo "<p>No tiene permiso para acceder a esta sección.</p>";
}?>	