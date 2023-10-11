<?php if (allowed($mnu)) { ?>
		<div class='cp_mnu_title title_header_mod'>Nueva imagen</div>
	
			<div class='cp_alert noerror' id='info-Title'></div>
			<div class='cp_alert noerror' id='info-Url'></div>
			<div class='cp_alert noerror' id='info-Multiple2'></div>
			<div class='cp_alert noerror' id='info-NumTotal'></div>
			<div class='cp_alert noerror' id='info-MaxFile'></div>
			<br/>
			<form id='createDownload' method='post' action='modules/gallery/create_image.php' enctype='multipart/form-data' id='mainform' name='mainform'>
				<input type="hidden" name="mnu" value="<?php echo $mnu; ?>" />
				<div class='cp_box shaded cp_height25'>
					<div class='cp_table400'>
						<div class='cp_formfield bold'>Autor:</div>
						<div class='cp_table250' style='padding-top:5px;'>
							<?php echo $_SESSION[PDCLOG]['Login']; ?>
						</div>
						<input type='hidden' name='Author' value='<?php echo $_SESSION[PDCLOG]['Login']; ?>'/>
					</div>
					
					<div class='cp_table160' style='float:right'>
						<div class='cp_table60 bold'>
							<label for='Status' style='padding-top:5px;'>Estado:</label>
						</div>
						<div class='cp_formfield_l' style='margin-top:0px;'>
							<select name='Status' id='Status' width='40'>
								<option value='1'>Publicado</option>
								<option value='0' selected='selected'>Borrador</option>
							</select>
						</div>
					</div>		
				</div>
				
				<div class='cp_box cp_height20'>
					<div class='cp_table650'> 
						<div class='cp_formfield bold'>
							<label for='Gallery'>Galería:</label>
						</div>
						<div class='cp_formfield cp_height35'>
							<select name='Gallery' id='Gallery' style='width:300px;margin-top:-5px;'>
								<?php
								$q = "SELECT ID, TITLE FROM ".preBD."images_gallery order by ID desc" ;
								$result = checkingQuery($connectBD, $q);
								while($row = mysqli_fetch_array($result)) { ?>
									<option value='<?php echo $row['ID']; ?>'><?php echo $row['TITLE']; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</div>
				
				<!-- Selector una imagen o varias -->
				<div id="boxMenutype" class='boxGestionType'>
					<ul class='listGestionType'>
						<li>
							<div id="menuSinglePhoto" class="gestionType gestionTypeOff" onclick="changeTypeUploadPhoto('single'); return false;">
								Una foto
							</div>
						</li>
						<li style="float:left;">
							<div id="menuMultiplePhoto" class="gestionType gestionTypeOff" onclick="changeTypeUploadPhoto('multiple'); return false;" >
								Subida múltiple
							</div>
						</li>
					</ul>
				</div>
				
				<script type="text/javascript">				
					window.onload = function(){
						changeTypeUploadPhoto('single');
					};					
				</script>		
				<input type="hidden" name="typeUpload" id="typeUpload" value="single" />				
		
				<div id="one_photo">				
					<div class='cp_box dotted cp_height180'>
						<div class='cp_table'>
							<div class='cp_formfield bold'>
								<label for='Title'>Título*:</label>
							</div>
							<input type='text' name='Title' id='Title' title='Título' size='83' />
						</div>
						<div class='cp_table'>
							<div class='cp_formfield bold'>
								<label for='Text'>Descripción:</label>
							</div>
							<textarea name='Text' id='Text' title='Text' rows='4' cols='83' /></textarea>
						</div>
						<div class='cp_table650' style='margin-top:10px;'>
							<div class='cp_formfield bold'>
								<label for='Url'>Imagen: </label>
							</div>
							<div class='cp_table400'>
								<input type='file' name='Url' id='Url' title='Imagen' style='width:400px;' />
							</div>
						</div>
						
						<?php
							if(return_bytes(ini_get('upload_max_filesize')) > return_bytes(ini_get('post_max_size'))){
								$var = ini_get('post_max_size');
								$var2 = return_bytes(ini_get('post_max_size'));
							}else{
								$var = ini_get('upload_max_filesize');
								$var2 = return_bytes(ini_get('upload_max_filesize'));
							}
						?>
						
						<p style="clear:both; color: #c00;font-size: 12px;">El tamaño de la imagen a subir no puede superar los <?php echo $var; ?></p>						
					</div>
				</div>
				<div id="multiple_photo">
					<div class='cp_box dotted cp_height90'>
						<div class='cp_table650' style='margin-top:10px;'>
							<div class='cp_formfield bold'>
								<label for='Url'>Archivos a Subir: </label>
							</div>
							<div class='cp_table400'>
								<input type="file" name="Multiple2[]" id='Multiple2' title='Imágenes'  multiple="multiple" />
							</div>
						</div>
						
						<p style="clear:both; color: #c00;font-size: 12px;">Máximo número de archivos a subir: <?php echo ini_get('max_file_uploads'); ?></p>
						<p style="clear:both; color: #c00;font-size: 12px;">Tama&ntilde;o total máximo de la subida: <?php echo ini_get('post_max_size'); ?></p>
						<!--<p style="clear:both; color: #c00;font-size: 12px;">Tama&ntilde;o máximo de cada imagen: <?php echo $var; ?></p>-->
					</div>
				</div>
				
				<div class='cp_table700'>
					<div class='cp_table350 right'>
						<img class='image middle' src='images/loading.gif' style='visibility: hidden; padding: 10px 20px 0px 0px;' id='loading'>
					</div>
					<div class='cp_table250'>&nbsp;</div>
					<div class='cp_table75'>
						<input type='button' name='save' value='Guardar' onclick='enlace_seleccionado();' />
					</div>
				</div>				
			</form>
			<script type='text/javascript'>
				includeField('Title','string');
				includeField('Url','file');
				
				function enlace_seleccionado(){
					var type = document.getElementById("typeUpload").value;			

					/*tamaño permitido por el servidor para una sola subida o para el cuerpo del post*/
					var max = <?php echo return_bytes($var); ?>;						
				
					if(type === "single") {
						resetFields();
						document.getElementById('info-Multiple2').innerHTML = "";
						document.getElementById('info-NumTotal').innerHTML = "";
						includeField('Title','string');
						includeField('Url','file');				

						/*tamaño de la foto subida*/
						var input = document.getElementById('Url');
						var file = input.files[0];
						
						if(input.files.length > 0){
							/*tamaño archivo mayor que el permitido por el servidor*/
							if(file.size > max){
								var info = document.getElementById("info-MaxFile");
								info.className = "error";
								document.getElementById('info-MaxFile').innerHTML = "<img class='cp_msgicon' src='images/alert.png' alt='¡ATENCIÓN!' /> La imagen supera el tamaño permitido por el servidor. (<?php echo $var; ?>)";	
							}else{
								document.getElementById('info-MaxFile').innerHTML = "";
								showloading(1); 
								validate(this);
								return false;
							}	
						}else{
							document.getElementById('info-MaxFile').innerHTML = "";
							showloading(1); 
							validate(this);
							return false;							
						}
					} else if(type === "multiple") {			
						resetFields();
						document.getElementById('info-Title').innerHTML = "";
						document.getElementById('info-Url').innerHTML = "";	
						document.getElementById('info-MaxFile').innerHTML = "";						
						
						var totales = <?php echo ini_get('max_file_uploads'); ?>;					
						var tam_total_fichero = 0;						
						var inp = document.getElementById('Multiple2');
						
						/*tamaño que ocupan todos los arhcivos*/
						for(var i=0; i<inp.files.length;i++){
							var file = inp.files[i];
							tam_total_fichero = tam_total_fichero + file.size;
						}		
						
						//alert("Tamaño total archivos: " + tam_total_fichero);

						if(inp.files.length > totales){
							var info = document.getElementById("info-NumTotal");
							info.className = "error";
							document.getElementById('info-NumTotal').innerHTML = "<img class='cp_msgicon' src='images/alert.png' alt='¡ATENCIÓN!' /> Ha sobrepasado el número máximo de archivos a adjuntar (<?php echo ini_get('max_file_uploads'); ?>).";	
						}else{
							if(tam_total_fichero > max){
								var info = document.getElementById("info-MaxFile");
								info.className = "error";
								document.getElementById('info-MaxFile').innerHTML = "<img class='cp_msgicon' src='images/alert.png' alt='¡ATENCIÓN!' /> Ha sobrepasado el tamaño máximo que permite el servidor (<?php echo $var; ?>).";									
							}else{
								includeField('Multiple2','file');
								showloading(1); 
								validate(this);
								return false;
							}
						}
					}
				}
				
			</script>
<?php		
}else{
	echo "<p>No tiene permiso para acceder a esta sección.</p>";
}?>	