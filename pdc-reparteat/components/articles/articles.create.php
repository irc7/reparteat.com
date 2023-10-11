<?php if (allowed($mnu)) : 
		if(isset($_GET["type"])) {
			$typeArticle = trim($_GET["type"]); 
		}else {
			$typeArticle = "article";
		}	
		if(isset($_GET["filtersection"])) {
			$filtersection = intval($_GET["filtersection"]); 
		}else {
			$filtersection = 0;
		}
?>
		<div class='cp_mnu_title title_header_mod'>Nuevo artículo</div>
		<?php 
			if (isset($_GET['msg'])) {
				$msg = utf8_encode($_GET['msg']);
				echo "<div class='cp_info'><img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' /><p>".$msg."</p></div>\r\n";
			}
		?>
			<div class='cp_alert noerror' id='info-Title'></div>
			<div class='cp_alert noerror' id='info-Title_seo'></div>
			<br/>
	<div class="container">
		<div class="row">
			<form method='post' action='modules/articles/create_record.php' enctype='multipart/form-data' id='mainform' name='mainform'>
				<input type='hidden' name='mnu' value='<?php echo $mnu; ?>' />
				<input type='hidden' name='type' value='<?php echo $typeArticle; ?>' />
				<div class='cp_box shaded cp_height65'>
					<div class='cp_table' style="width:100%;">
						<div class='cp_table'>
							<div class='cp_formfield bold'>Autor:</div>
							<div class='cp_formfield'><?php echo $_SESSION[PDCLOG]['Login']; ?></div>
							<input type='hidden' name='Author' value='<?php echo $_SESSION[PDCLOG]['Login']; ?>'/>
						</div>
						
						<div class='cp_formfield_l floatRight'>
							<select name='Status' id='Status' width='40'>
								<option value='2' selected='selected'>Invisible</option>							
								<option value='1'>Publicado</option>
								<option value='0' selected='selected'>Borrador</option>
							</select>
						</div>
						<div class='cp_table60 bold floatRight'>
							<label for='Status' style='padding-top:5px;'>Estado:</label>
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
					<div class='cp_table' style='width:45%'> 
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
			
			<div class='cp_box cp_height350' id='box_article'>
				<div class='cp_table cp_height45'<?php if($typeArticle == "fundacion"){echo " style='display:none;'";} ?>>
					<div class='cp_formfield bold'>
						<label for='Section'>Sección:</label>
					</div>
					<div class='cp_formfield'>

						<select name='Section' id='Section' onchange="showDimension()">
						<?php
							$q = "SELECT * FROM ".preBD."articles_sections where type = '".$typeArticle."' order by TITLE asc";
							$result = checkingQuery($connectBD, $q);
							while($row = mysqli_fetch_array($result)):
						?>
							<option value='<?php echo $row['ID']; ?>'<?php if($filtersection == $row['ID']){echo " selected";} ?>>
								<?php echo $row['TITLE']; ?>
							</option>
						<?php endwhile; ?>

						</select>
					</div>
				</div>
				<br/>
			
				<div class='cp_table' style="display:none;">
					<div class='cp_formfield bold'>
						<label for='Title'>Firma:</label>
					</div>
					<input type='text' name='Firm' id='Firm' title='Firma' size='83' placeholder="Redacción"/>
				</div>
				<br/>
				<div class='cp_table' style="display:none;">
					<div class='cp_formfield bold' style="width:225px;">
						<label for='UrlArt' style="font-size:11px;"><?php echo DOMAIN; ?></label>
					</div>
					<input type='text' name='UrlArt' id='UrlArt' title='UrlArt' size='70' placeholder="Url del artículo"/>
					<br/>
					<div style="font-size:11px;font-style:italic;color:#c00;padding-top:5px;">
						Sustituya los espacios por guiones y utilice unicamente números y letras.
					</div>
					<br/>
					<br/>
				</div>
				<br/>
				<div class='cp_table'>
					<div class='cp_formfield bold'>
						<label for='Title'>Título*:</label>
					</div>
					<input type='text' name='Title' id='Title' title='Título' size='83' />
				</div>
				<br/>
				
				<div class='cp_table'>
					<div class='cp_formfield bold'>
						<label for='Title_seo'>Título portada *:</label>
					</div>
					<input type='text' name='Title_seo' id='Title_seo' title="Título de portada" size='83' />
				</div>
				<br/>
				
				<div class='cp_table'>
					<div class='cp_formfield bold'>
						<label for='Subtitle'>Subtítulo:</label>
					</div>
					<input type='text' name='Subtitle' id='Subtitle' size='83' />
				</div>
				<br/>
				
				<div class='cp_table'>
					<div class='cp_formfield bold top'>
						<label for='Sumary'>Resumen:</label>
					</div>
					<textarea name='Sumary' id='Sumary' cols='82' rows='4'></textarea>
				</div>
				<br/>
						
				<div class='cp_table'>
					<div class='cp_formfield bold top'>
						<label for='Intro'>Entradilla:</label>
					</div>
					<textarea name='Intro' id='Intro' cols='82' rows='4'></textarea>
				</div>
			</div>
			
			<div id='box_block' class='cp_box dotted' style='margin-top:30px;'>
				<div class='cp_mnu_subtitle title_block'>Bloque 1</div>
			
				<div class='cp_height30'>
					<div class='cp_formfield bold'>
						<label for='Block1_title'>Título:</label>
					</div>
					<input type='text' name='Block1_title' id='Block1_title' size='84' />
				</div>
	
			
				<?php require_once("js/jscripts/tiny_mce/tiny_mce.php"); ?>
				<div class='cp_height270' style='display:block;width:100%'>
					<div class='cp_table cp_height270' style='width:100%'>
						<textarea name='Block1_text' id='Block1_text' class='spl_editable' style='width:690px;height:260px;'></textarea>
					</div>
				</div>
			
				<div style='display:block;'>
<?php //GESTION DE IMAGEN - VIDEO ?>
					<div class='boxGestionType' style="margin-top:20px;">
						<ul id='opt_paragraph1' class='listGestionType'>
							<li><div class="gestionType gestionTypeOff" id='list_img1' onclick='selectImageParagraph(1);selectImageParagraph(1);'>Imagen</div></li>
							<li><div class="gestionType gestionTypeOff" id='list_video1' onclick='selectVideoParagraph(1);'>Vídeo</div></li>
							<li><div class="gestionType gestionTypeOff" id='list_gallery1' onclick='selectGalleryParagraph(1);'>Galería</div></li>
						</ul>
						<script type="text/javascript">
							window.onload = function(){
								selectImageParagraph(1);
							};
						</script>
						<input type='hidden' name='o_p_1' id='o_p_1' value='0' />
					</div>
				
					<div id='box_img1' style='display:block;background-color:#ededed;padding-top:10px;border:1px dotted #999;'>
						<table class='mnu_image'><tbody>
							<tr>
								<td>Seleccione imagen: </td>
								<td>
									<input type='hidden' name='mnu_img1' id='mnu_img1_image' value='1' />
								</td>
								<td>
									<input type='file' name='Image1' id='Image1'  style='display:block' />
									
									<?php 
										$q = "select ID, IMAGE_C from ".preBD."articles_sections where type = 'article' order by TITLE asc";
										$result = checkingQuery($connectBD, $q);
										$i = 1;
										while($row = mysqli_fetch_array($result)){								
										?>
											<span id="box_infomartion_<?php echo $row['ID'];?>" style="margin-top:4px;float:left; margin-left:20px; ;color: #c00; font-size:10px; <?php if($i != 1){ ?> display:none; <?php }else{ ?> display:block; <?php } ?>">
												Ancho óptimo:  <?php echo $row['IMAGE_C']."px"; ?>
											</span>
									<?php 
										$i++;
									} ?>
										
								</td>
							</tr> 
						<!--<tr>
								<td>Regla de maquetación</td>
								<td colspan='2'>
									<input type='radio' name='mnu_img1' id='mnu_img1_regla' value='0' onclick='document.getElementById(\"Image1\").style.display=\"none\";document.getElementById(\"Image1\").disabled=true' />
								</td>
							</tr>
							<tr>
								<td>Enlazar imagen:</td>
								<td>
									<input type='checkbox' name='opt_link1' name='opt_link1' onclick='if(document.getElementById("box_link1").style.display=="none"){document.getElementById("box_link1").style.display="block";}else{document.getElementById("box_link1").style.display="none";}' style='float:right;'/>
								</td>
								<td>
									<div id='box_link1' style='display:none'>
										<input type='text' name='Link_img1' id='Link_img1' value='http://' style='float:left;width:350px;' />
										<select name='Target_link1' id='Target_link1'>
											<option value='_self'>Misma ventana</option>
											<option value='_blank' selected='selected'>Nueva ventana</option>
										</select>
									</div>
								</td>
							</tr>-->
							</tbody>
						</table>
					</div>
					
					<div id='box_video1' style='display:none;padding-top:10px;background-color:#ededed;border:1px dotted #999;margin-top:-1px;'>
						<table class='mnu_image'>
						<tbody>
							<tr>
								<td>Vídeo personal</td>
								<td>
									<input type='radio' name='mnu_video1' id='mnu_video1_per' value='1' 
									onclick='document.getElementById("wrapperVideo1").style.display="block";
										document.getElementById("Video1").disabled=false;
										document.getElementById("wrapperVideo1_img").style.display="block";
										document.getElementById("video_dimensions").style.display="block";
										document.getElementById("Video1_img").disabled=false;
										document.getElementById("wrapperYoutube1").style.display="none";
										document.getElementById("Youtube1").disabled=true;
										document.getElementById("wrapperYoutube1_img").style.display="none";
										document.getElementById("Youtube1_img").disabled=true;' />
								</td>
								<td>
									<div id='wrapperVideo1_img' style='display:none'>
										<span style="float:left;">Seleccione imagen:&nbsp;</span>
										<input type='file' name='Video1_img' id='Video1_img' disabled style='float:left;' />
										<div id="video_dimensions" style="float:left; margin-left:20px; display:none;">
											<?php 
												$q3 = "select ID, IMAGE_C from ".preBD."articles_sections where type = 'article' order by TITLE asc";
												$result3 = checkingQuery($connectBD, $q3);
												$j=1;
												while($row3 = mysqli_fetch_array($result3)){
												?>
													<span id="video_infomartion_<?php echo $row3['ID'];?>" style="margin-top:4px;color: #c00; font-size:10px;  <?php if($j != 1){ ?> display:none; <?php }else{ ?> display:block; <?php } ?>">
														Ancho óptimo:  <?php echo $row3['IMAGE_C']."px"; ?>
													</span>
											<?php 
												$j++;
											} ?>								
										</div>
									</div>
									<div id='wrapperVideo1' style='display:none'>
										<span style="float:left;clear:both;">Seleccione vídeo:&nbsp;</span>
										<input type='file' name='Video1' id='Video1' disabled style='float:left;' />
									</div>
								</td>
							</tr>
							<tr>
								<td>Vídeo YouTube</td>
						<?php //PARA ACTIVAR LA IMAGEN PERSONALIZADA DEL YOUTUBE LINE 210 y 211	?>
								<td>
									<input type='radio' name='mnu_video1' value='0'
									onclick='document.getElementById("wrapperVideo1").style.display="none";
												document.getElementById("Video1").disabled=true;
												document.getElementById("wrapperVideo1_img").style.display="none";
												document.getElementById("video_dimensions").style.display="none";
												document.getElementById("Video1_img").disabled=true;
												document.getElementById("wrapperYoutube1_img").style.display="none";
												document.getElementById("Youtube1_img").disabled=true;
												document.getElementById("wrapperYoutube1").style.display="block";
												document.getElementById("Youtube1").disabled=false'; />
								</td>
								<td>
									<div id='wrapperYoutube1' style='display:none'>
										Inserte còdigo:&nbsp;
										<input type='text' name='Youtube1' id='Youtube1' disabled style='float:none;width:200px;' />
										<p style='margin:0px;margin-left:90px;padding:0px;padding-top:5px;font-size:10px;'>
											http://www.youtube.com/watch?v=<span style='color:red;font-size:11px;'>1NQ7JQ3C4dM</span>&feature=g-feat
										</p>
									</div>
									<div id='wrapperYoutube1_img' style='display:none'>
										Seleccione imagen:&nbsp;<input type='file' name='Youtube1_img' id='Youtube1_img' disabled style='float:none;' />
									</div>
								</td>
							</tr>				
						</tbody>
						</table>
					</div>
					
					<div id='box_gallery1' style='display:none;padding-top:10px;padding-left:10px;background-color:#ededed;border:1px dotted #999;margin-top:-1px;'>
						<div style='clear:both;height:30px;'>
						<div class='cp_formfield bold'>
							<label for='Block1_album'>Insertar galería:</label></div>
							<?php
							$qG = "select ID, TITLE from ".preBD."images_gallery order by ID desc";
							$resultG = checkingQuery($connectBD, $qG);
							?>
							<div class='cp_table'>
								<select name='Block1_album' id='Block1_album'>
									<option value='0' selected='selected'>Sin galería</option>
									<?php
									while($album = mysqli_fetch_assoc($resultG)) {
										echo "<option value='".$album["ID"]."'>".$album["TITLE"]."</option>";
									}
									?>
								</select>
							</div>
						</div>	
					</div>
	<?php //ALINEACION	 ?>
					<div class='box_aling' style='height:32px;margin-top:10px;'>
						<div class='cp_formfield bold'>Posición:</div>
						<div class='cp_aling_pi center'>
							<img class='image' src='images/left.png' />
						</div>
						<div class='cp_aling_pi center'>
							<img class='image' src='images/center.png' />
						</div>
						<div class='cp_aling_pi center'>
							<img class='image' src='images/right.png' />
						</div>
					</div>
					
					<div class='box_aling' style='height:20px;'>
						<div class='cp_formfield bold align_ie'><label for='Block1_align'>&nbsp;</label></div>
						<div class='cp_aling_p center'>
							<input type='radio' name='Block1_align' id='Block1_image_align' value='left' onclick='changeTextInfoTransparentSmall(1);'>
						</div>
						<div class='cp_aling_p center'>
							<input type='radio' name='Block1_align' id='Block1_image_align' value='center' checked onclick='changeTextInfoTransparentBig(1);'>
						</div>
						<div class='cp_aling_p center'>
							<input type='radio' name='Block1_align' id='Block1_image_align' value='right' onclick='changeTextInfoTransparentSmall(1);'>
						</div>
					</div>
				</div>
	<?php //PIE DE FOTO ?>
				<div class='box_foot' style='margin-top:10px;height:60px;'>
					<div class='cp_formfield bold'>
						<label for='Block1_image_foot'>Pié de imagen:</label>
					</div>
					<textarea name='Block1_foot' id='Block1_foot' style='width:435px;height:50px'></textarea>
				</div>

	<?php //GESTION DE DESCARGA ?>
				<div id='add_file1' style='display:block;clear:both;'>
					<div class='cp_formfield bold'>
						<label>Agregar adjunto</label>
					</div>
					<img class='image' src='images/add.png' alt='Agregar adjunto' title='Agregar adjunto' style='cursor:pointer;margin-top: 5px;' onclick='openBoxFile(1);'>
				</div>
				
				<div id='box_file1' style='display:none;clear:both;height:50px;'>
					<div class='cp_formfield bold'>
						<label for='Block1_title_file'>T&iacute;tulo documento:</label>
					</div>
					<input type='text' name='Block1_title_file' id='Block1_title_file' size='40' />
					<input type='file' name='Block1_file' id='Block1_file' size='40' disabled />
				</div>	
			</div>
			<div class='cp_box dotted' style='height:390px;display:none;'>		
				<div class='cp_mnu_subtitle title_block'>Noticias relacionadas</div>
				<div style="width: 100%;height: 260px;margin-top:20px;">
					
					<?php	
						
						$q1 = "SELECT * from ".preBD."articles_sections where TYPE = 'article' and ID > 2 order by TITLE asc";
						$result1 = checkingQuery($connectBD, $q1);?>
						<!--echo "<option value ='0' >Ninguna</option>";-->
						<select name='section_rn' id='section' style='width:300px;' onchange="SelectIdSection();">						
						<?php while($row1 = mysqli_fetch_object($result1)) {										
							echo "<option value='".$row1->ID."'>";
							if (strlen($row1->TITLE) > 80){
								echo substr($row1->TITLE,0,80) . "...";
							} else {
								echo $row1->TITLE;
							}
							echo "</option>\r\n";					
						}
						?>	
						</select>	
						
						<?php 
						/* Selección las diferentes secciones para construir las cajas*/
						$i = 0;
						$result2 = checkingQuery($connectBD, $q1);
						?>
						<br/><br/>						
						<?php while($row2 = mysqli_fetch_object($result2)) {?>	
						<div id="seccion_<?php echo $row2->ID ?>" style="float:left; <?php if($i == 0){?> display:block; <?php }else{?>display:none;<?php } ?>">
							<p><strong>Selección de artículos</strong></p>
							<?php 
							
							$u = "select * from ".preBD."articles where IDSECTION = ". $row2->ID ." and (STATUS = 1 or STATUS = 2) and TRASH = 0 order by DATE_START desc";
							
							$result4 = checkingQuery($connectBD, $u);
							?>
							<select class="select-origen" id="origen<?php echo $row2->ID; ?>" name="origen<?php echo $row2->ID; ?>[]" multiple="multiple" style="width:300px; height:200px; float:left;">
							<?php while($row4 = mysqli_fetch_object($result4)){ ?>
								<option id="option-<?php echo $row2->ID ?>_<?php echo $row4->ID ?>" value="<?php echo $row2->ID ?>_<?php echo $row4->ID ?>"> <?php echo $row4->ID . "-" .$row4->TITLE; ?></option>
							<?php } ?>
							</select>							
						</div>
						<?php $i++; ?>
					<?php } ?>
					<br/>
					<div class="botones">
						<input type="button" class="pasar" value="Agregar »" ><br /><br />
						<input type="button" class="quitar" value="« Quitar" >
					</div>
					<div class="caja_destino">
						<select name="destino[]" id="destino" multiple="multiple" style="width:300px; height:200px; float:left;"></select>
					</div>							
				</div>
				<div style="text-align:justify;font-weight:bold;font-style:italic;width:100%;display:block;clear:both;margin-top:10px;">
					<p><em style="color:#c00;">Debe seleccionar un número par de noticias</em></p>
					<br/>
					Para seleccionar más de un elemento a la vez de la lista, mantenga pulsada la tecla Ctrl (en Windows) o Cmd (en Macintosh).
				</div>
			</div>
			
			<div class='cp_table'>
				<div class='cp_table200'>&nbsp;</div>
				<div class='cp_table200 right'>
					<img class='image middle' src='images/loading.gif' style='visibility: hidden; padding: 10px 20px 0px 0px;' id='loading'>
				</div>
				<div class='cp_table100 save_ie1'>&nbsp;</div>
				<input style='float:left;' type='button' name='insertParagraphs' value='Insertar Bloque' onclick='changeAction();showloading(1);validate(this); return false;' />
			</div>
			
			<div class='cp_table20 save_ie2'>&nbsp;</div>
			<input style='float:right;' type='button' name='save' value='Guardar' onclick='sendFormArticle();showloading(1); validate(this); return false;' />
		</form>
		</div>
	</div>
		<script type="text/javascript">
			var $z = jQuery.noConflict();
			$z(document).ready(function(){
				
			/*Gestion de noticias*/	
				$z("#Noticia").change(function(){ //elimina del bloque de noticias la seleccionada en actualidad
					var vA = $z(this).val();	
					
					$z(".select-origen option").attr("disabled", false);
					$z(".select-origen option").css("color", "#666");
					
					$z("#destino option#option-4_"+vA).remove().appendTo(".select-origen");
					
					$z(".select-origen option#option-4_"+vA).attr("disabled", true);
					$z(".select-origen option#option-4_"+vA).css("color", "#ededed");
				});
			/*Pasamos el artículo seleccionado a la caja de destino*/
				$z('.pasar').click(function () {
					var idSection = document.getElementById('section').value;
					return !$z('#origen'+idSection+' option:selected').remove().appendTo('#destino'); 
				});
				
				$z('.quitar').click(function () {
					$z("#destino option:selected").each(function(){
						var v = $z(this).val();	
						var position = v.indexOf('_');
						$z(this).remove().appendTo('#origen'+v.substring(0,position));
					});
				});
			});
			
			function sendFormArticle() {
				$z("#destino").each(function(){
					$z("#destino option").attr("selected",true); 
				});
				includeField('Title','string');		
				includeField('Title_seo','string');		
			}
		</script>
<?php else: ?>
	<p>No tiene permiso para acceder a esta sección.</p>
<?php endif; ?>	