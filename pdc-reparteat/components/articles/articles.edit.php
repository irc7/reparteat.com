<?php if (allowed($mnu)):
	/*cogemos del servidor el máximo número de archivos de subida*/		
	$max_subida = ini_get('max_file_uploads');
 ?>
	<?php require_once("js/jscripts/tiny_mce/tiny_mce.php"); ?>
	<div class='cp_mnu_title cp_mnu_title title_header_mod'>Editar artículo</div>
	<?php
		if (isset($_GET['msg'])) {
			$msg = utf8_encode($_GET['msg']);
			echo "<div class='cp_info'><img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' /><p>".$msg."</p></div>\r\n";
		}
		unset($_SESSION["relatedNews"]);
			
		if (isset($_GET['record'])) {
			$id = $_GET['record'];
			
			$q = "select ID from ".preBD."articles_temp where IDARTICLE = " . $id; 
			$result = checkingQuery($connectBD, $q);
			$art_temp = mysqli_fetch_assoc($result);
			if(!isset($_GET['preview'])) {
				deleteArticleTemp($art_temp["ID"]);
				$id = createArticleTemp($id);
			} else if($_GET['preview'] == "new"){
				$id = createArticleTemp($id);
			} else {
				$id = $art_temp["ID"];
			}	
		} else if (isset($_GET['recordTemp'])){
			$id = $_GET['recordTemp'];
		} else {
			$location = "Location: ../index.php?mnu=".$mnu."&com=articles&tpl=option";
			header($location);
		}
		
		
		
		if (isset($_GET['preview']) && $_GET['preview'] == "on"):
		?>	
		<script type="text/javascript">
			window.open("../index.php?view=preview&id=<?php echo $id; ?>", "preview");
		</script>
		<?php endif;
		$q_article = "SELECT * FROM ".preBD."articles_temp WHERE ID='" . $id . "'";
		$result_article = checkingQuery($connectBD, $q_article);
		$row_article = mysqli_fetch_array($result_article);
		$Author_old = trim($row_article['AUTHOR']);
		$Author_new = $_SESSION[PDCLOG]["Login"];
		$id_old = $row_article['IDARTICLE'];
		
		$related = articlesRelated($id_old);
		
			$dateStart = new DateTime($row_article["DATE_START"]);
			$dateEnd = new DateTime($row_article["DATE_END"]);
			
			$Status = $row_article['STATUS'];
			$Section = $row_article['IDSECTION'];
			$Firm = stripslashes($row_article['FIRM']);
			$Title = stripslashes($row_article['TITLE']);
			$Title_seo = stripslashes($row_article['TITLE_SEO']);
			$Subtitle = stripslashes($row_article['SUBTITLE']);
			$Sumary = stripslashes($row_article['SUMARY']);
			$Intro = stripslashes($row_article['INTRO']);
			$Thumbnail= $row_article['THUMBNAIL'];
			
			
			$img_youtube = substr($Thumbnail, 0, 2);
			$url_youtube = substr($Thumbnail, 2, strlen($Thumbnail));
			
			if ($Thumbnail == ""){
				$Thumbnail_url = "images/emptythumbnail.gif";
			}else if($img_youtube  == "v="){
				$Thumbnail_url = "http://img.youtube.com/vi/".$url_youtube."/0.jpg";
			}else{
				$Thumbnail_url = "../files/articles_temp/thumb/".$Thumbnail;
			} 
			
			$Thumbnail_url_enlace = "../files/articles_temp/image/".$Thumbnail;
			echo "<div class='cp_alert noerror' id='info-Title'></div>\r\n";
			echo "<div class='cp_alert noerror' id='info-Title_seo'></div>\r\n";
			echo "<br/>\r\n";
			
			$q_blocks = "SELECT * FROM ".preBD."paragraphs_temp WHERE IDARTICLE = " . $id;
			$result_blocks = checkingQuery($connectBD, $q_blocks);
			$paragraphs = mysqli_num_rows($result_blocks);
			
			$q_section = "SELECT * FROM ".preBD."articles_sections WHERE ID = ".$Section;
			
			
			$result_section = checkingQuery($connectBD, $q_section);
			$section_thumbnail = mysqli_fetch_array($result_section);
			
			$typeArticle = $section_thumbnail['TYPE'];
			
			$q = "SELECT * FROM ".preBD."url_web WHERE TYPE = 'article' and ID_VIEW = " . $id_old;
			$r = checkingQuery($connectBD, $q);
			$urlArt = mysqli_fetch_object($r);
			
			
	?>
	<div class="container">
		<div class="row">
			<form method='post' action='modules/articles/edit_record.php?record=<?php echo $id; ?>' enctype='multipart/form-data' id='mainform' name='mainform'>
				<input type='hidden' name='mnu' value='<?php echo $mnu; ?>' />
				<input type='hidden' name='type' id="typeArticle" value='<?php echo $typeArticle; ?>' />
				<div class='cp_box shaded cp_height95'>
					<div class='cp_table400'>
						<div class='cp_formfield bold' style='padding-top:3px;'>Autor:</div>
						<div class='cp_formfield_lx top'>
							<input type='hidden' name='Author' id='Author' value='<?php echo $Author_new; ?>' />
							<?php echo $Author_old; ?>
						</div>
					</div>
			
					<div class='cp_table280'>
						<div class='cp_formfield_m floatRight top right'><?php echo $id_old; ?></div>
						<div class='cp_formfield_ls bold floatRight top'>
							<label class='right' for='Status'>Número:</label>
						</div>
						<input type='hidden' id='Number' name='Number' value='<?php echo $id; ?>'/>
					</div>
					<br/>
					
						<div class='cp_table160 floatRight'>
							<div class='cp_formfield_ls bold right' style='padding-top:3px;'>
								<label class='right' for='Status'>Estado:</label>
							</div>
							<div class='cp_formfield_l top'>
								<select name='Status' id='Status' width='40' style='float:right;'>
									<option value='2'<?php if ($Status == 2) {echo " selected='selected'";} ?>>Invisible</option>
									<option value='1'<?php if ($Status == 1) {echo " selected='selected'";} ?>>Publicado</option>
									<option value='0'<?php if ($Status == 0) {echo " selected='selected'";} ?>>Borrador</option>
								</select>
							</div>
						</div>
					
					
					<div class='cp_table' style='width:50%'> 
						<div class='cp_formfield bold'>
							<label for='Date_start_dd'>Fecha / hora inicio:</label>
						</div>
						<input maxlength="100" size="12" value="<?php echo $dateStart->format("d-m-Y"); ?>" name="date_day" id="date_day" readonly="readonly" />
						<div class='cp_formfield_s'>/</div>
						<input type='text' name='Date_start_hh' id='Date_start_hh' size='1' value='<?php echo $dateStart->format("H"); ?>'/>
						<div class='cp_formfield_xs'>:</div>
						<input type='text' name='Date_start_ii' id='Date_start_ii' size='1' value='<?php echo $dateStart->format("i"); ?>'/>
					</div>
					<div class='cp_table' style='width:100%'> 
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
				<br/>
			
				<div class='cp_box' id='box_article' style='height:470px;'>
					<div class='cp_table'>
						<div class='cp_formfield bold' style="width:220px;">
							<label for='UrlArt' style="font-size:11px;"><?php echo DOMAIN; ?></label>
						</div>
						<input type='text' name='UrlArt' id='UrlArt' title='UrlArt' size='63' value="<?php echo $urlArt->SLUG; ?>" />
						<br/>
						<div style="font-size:11px;font-style:italic;color:#c00;padding-top:5px;">
							Sustituya los espacios por guiones y utilice unicamente números y letras.
						</div>
						<br/>
					</div>
					<div class="separator"></div>
					<div class='cp_table cp_height45'>
						<div class='cp_formfield bold' style='padding-top:10px;'>
							<label for='Section'>Sección:</label>
						</div>
						<div class='cp_formfield'>
							<?php
								$q_aux = "SELECT count(*) FROM ".preBD."paragraphs_temp WHERE IDARTICLE = '" . $id . "' ORDER BY POSITION asc";
								
								$result_aux = checkingQuery($connectBD, $q_aux);
								$aux = mysqli_fetch_array($result_aux);
							?>	
								
							<select name='Section' id='Section' onchange="showDimensionEditArticle(<?php echo $aux[0]; ?>)">
							<?php
								$q_sections = "SELECT * FROM ".preBD."articles_sections where TYPE = '".$typeArticle."' order by TITLE asc";
								$result_sections = checkingQuery($connectBD, $q_sections);
								while($row_sections = mysqli_fetch_array($result_sections)) {
									$Section_number = $row_sections['ID'];
									echo "<option value='".$Section_number."'";
									if ($Section == $Section_number) {
										echo " selected='selected'";
										$width_thumb = $row_sections['IMAGE_LR'];
									}
									echo ">".$row_sections['TITLE']."</option>";
								}
							?>
							</select>
						</div>
					</div>
					<div class="separator"></div>
					<div class='cp_table650' style="display:none;">
						<div class='cp_formfield bold'><label for='Title'>Firma:</label></div>
						<input type='text' name='Firm' id='Firm' title='Firma' size='75' value='<?php echo $Firm; ?>'/>
					</div>
					
					<br/>
					<div class='cp_table650'>
						<div class='cp_formfield bold'><label for='Title'>Título*:</label></div>
						<input type='text' name='Title' id='Title' title='Título' size='75' value='<?php echo $Title; ?>'/>
					</div>
					<br/>
				
					<div class='cp_table'>
						<div class='cp_formfield bold'><label for='Title_seo'>Título portada:</label></div>
						<input type='text' name='Title_seo' id='Title_seo' title="Título de portada" size='75' value='<?php echo $Title_seo; ?>' />
					</div>
					<br/>
				
					<div class='cp_table'>
						<div class='cp_formfield bold'><label for='Subtitle'>Subtítulo:</label></div>
						<input type='text' name='Subtitle' id='Subtitle' size='75' value='<?php echo $Subtitle; ?>'/>
					</div>
					<br/>
				
					<div class='cp_table cp_height90' style="margin-bottom:5px;">
						<div class='cp_formfield bold top'><label for='Sumary'>Resumen:</label></div>
						<textarea name='Sumary' id='Sumary' cols='75' rows='4'><?php echo $Sumary; ?></textarea>
					</div>
					<br/>
					<div class='cp_table cp_height90' style="margin-bottom:5px;">
						<div class='cp_formfield bold top'><label for='Intro'>Entradilla:</label></div>
						<textarea name='Intro' id='Intro' cols='75' rows='4'><?php echo $Intro; ?></textarea>
					</div>
			
					<div class='cp_table650 cp_height90' id='box_article'>
						<div class='cp_formfield bold top'>
							<label for='Intro'>Miniatura del artículo:</label>
						</div>
						<div class='cp_table150' style='height:90px;overflow:hidden;'>
							<img class='image' src='<?php echo $Thumbnail_url; ?>'  alt='Zoom 100%' title='Zoom 100%' style='max-width:120px; max-height:90px;' />
						</div>
						<div class='cp_table240 top'>
							<div class='cp_table' style='margin-bottom:10px;'>
								<div class='cp_table150'>Seleccionar miniatura</div>
								<input style='margin-bottom:10px;' id='select_thumb_article' type='radio' name='thumbimage' value='-1' checked='checked' onClick='close_thumb_new();' />
							</div>
							<br/>
							<?php if ($Thumbnail != ""): ?>
							<div class='cp_table' style='margin-bottom:10px;'>
								<div class='cp_table150'>Eliminar imagen</div>
								<input id='delete_thumb_article' type='radio' name='thumbimage' value='1000' onClick='close_thumb_new();' />
							</div>
							<?php endif; ?>
							<div class='cp_table' style='margin-bottom:10px;'>
								<div class='cp_table150'>Nueva miniatura</div>
								<input id='select_new_thumb' class='cp_input240' type='radio' name='thumbimage' value='0' onClick='open_thumb_new();' />
							</div>
							<div class='cp_table' id='Thumb_image' style='display:none;margin-left:0px;margin-top:80px;position:absolute;'>
								<input type='file' name='Thumb_image' id='Thumb_image_input' size='20' style='margin-bottom:10px;' /><br />
								<?php 
									$q = "select ID, THUMB_HEIGHT, THUMB_WIDTH from ".preBD."articles_sections where type = 'article' order by TITLE asc";
									$result = checkingQuery($connectBD, $q);
									while($row = mysqli_fetch_array($result)){								
									?>
										<span class="option_aux" id="box_infomartion_<?php echo $row['ID'];?>" style="float:left; margin-left:20px; ;color: #c00; font-size:10px; <?php if ($Section == $row['ID']) { ?> display:block; <?php }else{ ?> display:none <?php } ?> ">
											Dimensión óptima:  <?php echo $row['THUMB_WIDTH']."x".$row['THUMB_HEIGHT']."px";  ?>
										</span>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
				<?php 
					$q_paragraphs = "SELECT * FROM ".preBD."paragraphs_temp WHERE IDARTICLE = '" . $id . "' ORDER BY POSITION asc";
					
					$result_paragraphs = checkingQuery($connectBD, $q_paragraphs);
					$cont = 1;
						
					while ($row_blocks = mysqli_fetch_array($result_paragraphs)):
			//		pre($row_blocks);die();
						$Title_block = stripslashes($row_blocks['TITLE']);
						$Text_block = stripslashes($row_blocks['TEXT']);
						$Type = $row_blocks['TYPE'];
						$Image_block = stripslashes($row_blocks['IMAGE']);
						$Video_block = stripslashes($row_blocks['VIDEO']);
						$Foot_block = stripslashes(($row_blocks['FOOT']));
						$Link_block = stripslashes($row_blocks['LINK']);
						$Target_block = $row_blocks['TARGET'];
						$Album = $row_blocks['IDALBUM'];
						
						
						if ($row_blocks['ALIGN'] != "") {
							$Align_block = $row_blocks['ALIGN'];
						} else {
							$Align_block = "right";
						}
				?>
				<div class='cp_box dotted' style='margin-top:35px;'>
					<div class='cp_mnu_subtitle title_block cp_height25'>
						<div class='cp_table250' style='color:#fff;'>Bloque <?php echo $cont; ?></div>
						<div class='cp_table100 floatRight'>
							<ul class='cp_control_paragraphs'>
							<?php if ($cont != $paragraphs): ?>
								<li class='position'>
									<input id='down_buttom' type='button' name='downParagraphs<?php echo $cont; ?>' value=' ' alt='Bajar' title='Mover hacia abajo' onclick='optionActionBlock("down", "<?php echo $id; ?>", "<?php echo $cont; ?>");validate(this);return false;' />
								</li>
							<?php endif; ?>
							<?php if ($cont != 1): ?>
								<li class='position'>
									<input id='up_buttom' type='button' name='upParagraphs<?php echo $cont; ?>' value=' ' alt='Subir' title='Mover hacia arriba' onclick='optionActionBlock("up", "<?php echo $id; ?>", "<?php echo $cont; ?>");validate(this);return false;' />
								</li>
							<?php endif; ?>
								<li style='float:right;'>
									<a href='' onClick='open_closeSecurity(<?php echo $cont; ?>);return false;' alt='Eliminar' title='Eliminar'>
										<img src='images/delete.png' style='border:none !important;' />
									</a>
								</li>
							</ul>
							<div id='list_security<?php echo $cont; ?>' class='view_security_off'>
								<p class='text_security'>
									<img src='images/alert.png' />¿Eliminar Bloque <?php echo $cont; ?>?
								</p>
								<ul class='control_mnu' style='list-style:none;margin-left:15px;'>
									<li class='buttom_control'>
										<input id='confirm_delete' type='button' name='deleteParagraphs<?php echo $cont; ?>' value='Continuar' onclick='optionActionBlock("delete", "<?php echo $id; ?>", "<?php echo $cont; ?>");validate(this);return false;' />
									</li>
									<li class='buttom_control' style='margin-left:8px;'>
										<a id='close_buttom' href='#' onClick='open_closeSecurity(<?php echo $cont; ?>);return false;'>
											<span>Cancelar</span>
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				
					<div class='cp_height30'>
						<div class='cp_formfield bold'>
							<label for='Block<?php echo $cont; ?>_title'>Título:</label>
						</div>
						<input type='text' name='Block<?php echo $cont; ?>_title' id='Block<?php echo $cont; ?>_title' size='84' value='<?php echo $Title_block; ?>' />
					</div>
				
				<div class='cp_height270' style='display:block;width:100%'>
					<div class='cp_table cp_height270' style='width:100%'>
						<textarea name='Block<?php echo $cont; ?>_text' id='Block<?php echo $cont; ?>_text' class='spl_editable' style='width:690px;height:260px;'>
							<?php echo $Text_block; ?>
						</textarea>
					</div>
				</div>

	<?php //GESTION DE IMAGEN - VIDEO

			if($Album == 0 && ($Image_block == "" || $Image_block == NULL) && ($Video_block == "" || $Video_block == NULL)): ?>	
			
			<div class='boxGestionType' style="margin-top:20px;">
				<ul id='opt_paragraph<?php echo $cont; ?>' class='listGestionType'>
					<li><div class="gestionType gestionTypeOff" id='list_img<?php echo $cont; ?>' onclick='selectImageParagraph(<?php echo $cont; ?>);'>Imagen</div></li>
					<li><div class="gestionType gestionTypeOff" id='list_video<?php echo $cont; ?>' onclick='selectVideoParagraph(<?php echo $cont; ?>);'>Vídeo</div></li>
					<li><div class="gestionType gestionTypeOff" id='list_gallery<?php echo $cont; ?>' onclick='selectGalleryParagraph(<?php echo $cont; ?>);'>Galería</div></li>
				</ul>
				<input type='hidden' name='o_p_<?php echo $cont; ?>' id='o_p_<?php echo $cont; ?>' value='0' />
				<script type="text/javascript">
					window.onload = function(){
						selectImageParagraph(<?php echo $cont; ?>);
					};
				</script>
			</div>
			<?php $openScript[$cont] = "Image"; ?>
			
			<div id='box_img<?php echo $cont; ?>' style='display:block;background-color:#ededed;padding-top:10px;border:1px dotted #999;'>
				<table class='mnu_image' style='width:685px;'>
					<tbody>
						<tr>
							<td>Seleccione imagen:</td>
							<td>
								<input type='hidden' name='mnu_img<?php echo $cont; ?>' id='mnu_img<?php echo $cont; ?>_image' value='1' onclick='document.getElementById("new_dimensions_<?php echo $cont; ?>").style.display="block";' />
							</td>
							<td>
								<input type='file' name='Image<?php echo $cont; ?>' id='Image<?php echo $cont; ?>' disabled style='display:none' />
								<div id="new_dimensions_<?php echo $cont; ?>" style="float:left; margin-left:20px;">
									<?php 
										$q3 = "select ID, IMAGE_C from ".preBD."articles_sections where type = 'article' order by TITLE asc";
										$result3 = checkingQuery($connectBD, $q3);
										while($row3 = mysqli_fetch_array($result3)){
										?>
											<span id="new_infomartion_<?php echo $row3['ID'];?>" style="margin-top:4px;color: #c00; font-size:10px; <?php if ($Section == $row3['ID']) { ?> display:block; <?php }else{ ?> display:none <?php } ?> ">
												Ancho óptimo:  <?php echo $row3['IMAGE_C']."px"; ?>
											</span>
									<?php } ?>								
								</div>
							</td>
						</tr> 
					<!--	<tr>
							<td>Regla de maquetación</td>
							<td colspan='2'>
								<input type='radio' name='mnu_img<?php echo $cont; ?>' id='mnu_img<?php echo $cont; ?>_regla' value='2' onclick='document.getElementById("Image<?php echo $cont; ?>").style.display="none";document.getElementById("Image<?php echo $cont; ?>").disabled=true;document.getElementById("Image<?php echo $cont; ?>").disabled=false;document.getElementById("new_dimensions_<?php echo $cont; ?>").style.display="none";' />
							</td>
						</tr>
						<tr>
							<td>Enlazar imagen:</td>
							<td>
								<input type='checkbox' name='opt_link<?php echo $cont; ?>' name='opt_link<?php echo $cont; ?>' onclick='if(document.getElementById("box_link<?php echo $cont; ?>").style.display=="none"){document.getElementById("box_link<?php echo $cont; ?>").style.display="block"}else{document.getElementById("box_link<?php echo $cont; ?>").style.display="none";}' style='float:right;' />
							</td>
							<td>
								<div id='box_link<?php echo $cont; ?>' style='display:none'>
									<input type='text' name='Link_img<?php echo $cont; ?>' id='Link_img<?php echo $cont; ?>' value='http://' style='float:left;width:350px;' />
									<select name='Target_link<?php echo $cont; ?>' id='Target_link<?php echo $cont; ?>'>
										<option value='_self'>Misma ventana</option>
										<option value='_blank' selected='selected'>Nueva ventana</option>
									</select>
								</div>
							</td>							
						</tr>
					-->
					</tbody>
				</table>
			</div>
			
			<div id='box_video<?php echo $cont; ?>' style='display:none;padding-top:10px;background-color:#ededed;border:1px dotted #999;margin-top:-1px;'>
				<table class='mnu_image' style='width:685px;'>
				<tbody>
					<tr>
						<td>Vídeo personal</td>
						<td>
							<input type='radio' name='mnu_video<?php echo $cont; ?>' id='mnu_video<?php echo $cont; ?>_per' value='1'
							onclick='document.getElementById("wrapperVideo<?php echo $cont; ?>").style.display="block";
							document.getElementById("Video<?php echo $cont; ?>").disabled=false;
							document.getElementById("o_p_<?php echo $cont; ?>").value = 1;
							document.getElementById("wrapperVideo<?php echo $cont; ?>_img").style.display="block";
							document.getElementById("video_dimensions_<?php echo $cont; ?>").style.display="block";
							document.getElementById("Video<?php echo $cont; ?>_img").disabled=false;
							document.getElementById("wrapperYoutube<?php echo $cont; ?>").style.display="none";
							document.getElementById("Youtube<?php echo $cont; ?>").disabled=true;
							document.getElementById("wrapperYoutube<?php echo $cont; ?>_img").style.display="none";
							document.getElementById("image_start<?php echo $cont; ?>").disabled=false;
							document.getElementById("image_start<?php echo $cont; ?>").value=1;
							document.getElementById("Youtube<?php echo $cont; ?>_img").disabled=true;' />
							<input type='hidden' name='mnu_img<?php echo $cont; ?>' id='image_start<?php echo $cont; ?>' value='1' disabled />
						</td>
						<td>
							<div id='wrapperVideo<?php echo $cont; ?>_img' style='display:none'>
								<span style="float:left;">Seleccione imagen:&nbsp;</span>
								<input type='file' name='Image<?php echo $cont; ?>' id='Video<?php echo $cont; ?>_img' disabled style='float:left;' />
								<div id="video_dimensions_<?php echo $cont; ?>" style="float:left; margin-left:20px;">
									<?php 
										$q3 = "select ID, IMAGE_C from ".preBD."articles_sections where type = 'article' order by TITLE asc";
										$result3 = checkingQuery($connectBD, $q3);
										while($row3 = mysqli_fetch_array($result3)){
										?>
											<span id="video_infomartion_<?php echo $row3['ID'];?>" style="margin-top:4px;color: #c00; font-size:10px; <?php if ($Section == $row3['ID']) { ?> display:block; <?php }else{ ?> display:none <?php } ?> ">
												Ancho óptimo:  <?php echo $row3['IMAGE_C']."px"; ?>
											</span>
									<?php } ?>								
								</div>
							</div>
							<div id='wrapperVideo<?php echo $cont; ?>' style='display:none'>
								<span style="float:left;clear:both;">Seleccione vídeo:&nbsp;</span>
								<input type='file' name='Video<?php echo $cont; ?>' id='Video<?php echo $cont; ?>' disabled style='float:none;' />
							</div>
						</td>
					</tr> 
					<tr>
						<td>Vídeo YouTube</td>
						<td>
							<input type='radio' name='mnu_video<?php echo $cont; ?>' value='2' 
							onclick='document.getElementById("wrapperVideo<?php echo $cont; ?>").style.display="none";
							document.getElementById("Video<?php echo $cont; ?>").disabled=true;
							document.getElementById("o_p_<?php echo $cont; ?>").value = 2;
							document.getElementById("wrapperVideo<?php echo $cont; ?>_img").style.display="none";
							document.getElementById("video_dimensions_<?php echo $cont; ?>").style.display="none";
							document.getElementById("Video<?php echo $cont; ?>_img").disabled=true;
							document.getElementById("wrapperYoutube<?php echo $cont; ?>_img").style.display="none";
							document.getElementById("Youtube<?php echo $cont; ?>_img").disabled=true;
							document.getElementById("image_start<?php echo $cont; ?>").disabled=false;
							document.getElementById("image_start<?php echo $cont; ?>").value=2;
							document.getElementById("wrapperYoutube<?php echo $cont; ?>").style.display="block";
							document.getElementById("Youtube<?php echo $cont; ?>").disabled=false'; />
						</td>
						<td>
							<div id='wrapperYoutube<?php echo $cont; ?>' style='display:none'>
								Inserte código:&nbsp;
								<input type='text' name='Youtube<?php echo $cont; ?>' id='Youtube<?php echo $cont; ?>' disabled style='float:none;width:200px;' />
								<p style='margin:0px;margin-left:10px;padding:0px;padding-top:5px;font-size:10px;'>
									http://www.youtube.com/watch?v=<span style='color:red;font-size:11px;'>1NQ7JQ3C4dM</span>&feature=g-feat
								</p>
							</div>
							<div id='wrapperYoutube<?php echo $cont; ?>_img' style='display:none'>
								Seleccione imagen:&nbsp;
								<input type='file' name='Youtube<?php echo $cont; ?>_img' id='Youtube<?php echo $cont; ?>_img' disabled style='float:none;' />
							</div>
						</td>
					</tr>				
				</tbody>
				</table>
			</div>
			<div id='box_gallery<?php echo $cont; ?>' style='display:none;padding-top:10px;padding-left:10px;background-color:#ededed;border:1px dotted #999;margin-top:-1px;'>
				<div class='box_gallery' style='clear:both;height:30px;'>		
					<div class='cp_formfield bold' style="width:100px !important;">
						<label for='Block<?php echo $cont; ?>_album'>Insertar galería:</label>
					</div>
					<?php 
					$qG = "select ID, TITLE from ".preBD."images_gallery order by ID desc";
					$resultG = checkingQuery($connectBD, $qG);
					?>
					<div class='cp_table'>
						<select name='Block<?php echo $cont; ?>_album'  style="width:150px !important;">>
							<option value='0'<?php if($Album == 0) {echo " selected='selected'";}; ?>>Sin galería</option>
						<?php while($album = mysqli_fetch_assoc($resultG)){
								echo "<option value='".$album["ID"]."'";
								if($Album == $album["ID"]){
									echo " selected='selected'";
								}
								echo ">".$album["TITLE"]."</option>";
							}
						?>
						</select>
					</div>
				</div>
			</div>
			
			
			<?php elseif($Type == "gallery"): 
				$openScript[$cont] = "Gallery";
				if($Album == 0): ?>
					
					<div class='boxGestionType' style="margin-top:20px;">
						<ul id='opt_paragraph<?php echo $cont; ?>' class='listGestionType'>
							<li><div class="gestionType gestionTypeOff" id='list_img<?php echo $cont; ?>' >Imagen</div></li>
							<li><div class="gestionType gestionTypeOff" id='list_video<?php echo $cont; ?>'>Vídeo</div></li>
							<li><div class="gestionType gestionTypeOn" id='list_gallery<?php echo $cont; ?>'>Galería</div></li>
						</ul>
						<input type='hidden' name='o_p_<?php echo $cont; ?>' id='o_p_<?php echo $cont; ?>' value='0' />
						
					</div>
			
					<div id='box_img<?php echo $cont; ?>' style='display:block;background-color:#ededed;padding-top:10px;border:1px dotted #999;'>
						<table class='mnu_image' style='width:685px;'>
						<tbody>
							<tr>
								<td>Seleccione imagen</td>
								<td>
									<input type='hidden' name='mnu_img<?php echo $cont; ?>' id='mnu_img<?php echo $cont; ?>_image' value='1' />
								</td>
								<td>
									<input type='file' name='Image<?php echo $cont; ?>' id='Image<?php echo $cont; ?>' disabled style='display:none' />
								</td>
							</tr> 
						<!--	<tr>
								<td>Regla de maquetación</td>
								<td colspan='2'>
									<input type='radio' name='mnu_img<?php echo $cont; ?>' id='mnu_img<?php echo $cont; ?>_regla' value='2' onclick='document.getElementById("Image<?php echo $cont; ?>").style.display="none";document.getElementById("Image<?php echo $cont; ?>").disabled=true' />
								</td>
							</tr>
							<tr>
								<td>Enlazar imagen:</td>
								<td>
									<input type='checkbox' name='opt_link<?php echo $cont; ?>' name='opt_link<?php echo $cont; ?>' onclick='if(document.getElementById("box_link<?php echo $cont; ?>").style.display=="none"){document.getElementById("box_link<?php echo $cont; ?>").style.display="block"}else{document.getElementById("box_link<?php echo $cont; ?>").style.display="none"}' style='float:right;'/>
								</td>
								<td>
									<div id='box_link<?php echo $cont; ?>' style='display:none'>
										<input type='text' name='Link_img<?php echo $cont; ?>' id='Link_img<?php echo $cont; ?>' value='http://' style='float:left;width:350px;' />
										<select name='Target_link<?php echo $cont; ?>' id='Target_link<?php echo $cont; ?>'>
											<option value='_self'>Misma ventana</option>
											<option value='_blank' selected='selected'>Nueva ventana</option>
										</select>
									</div>
								</td>							
							</tr>
						-->
						</tbody>
						</table>
					</div>
			
					<div id='box_video<?php echo $cont; ?>' style='display:none;padding-top:10px;background-color:#ededed;border:1px dotted #999;margin-top:-1px;'>
						<table class='mnu_image' style='width:685px;'>
						<tbody>
							<tr>
								<td>Vídeo personal</td>
								<td>
									<input type='radio' name='mnu_video<?php echo $cont; ?>' id='mnu_video<?php echo $cont; ?>_per' value='1' 
									onclick='document.getElementById("wrapperVideo<?php echo $cont; ?>").style.display="block";
									document.getElementById("Video<?php echo $cont; ?>").disabled=false;
									document.getElementById("o_p_<?php echo $cont; ?>").value = 1;
									document.getElementById("wrapperVideo<?php echo $cont; ?>_img").style.display="block";
									document.getElementById("Video<?php echo $cont; ?>_img").disabled=false;
									document.getElementById("wrapperYoutube<?php echo $cont; ?>").style.display="none";
									document.getElementById("Youtube<?php echo $cont; ?>").disabled=true;
									document.getElementById("wrapperYoutube<?php echo $cont; ?>_img").style.display="none";
									document.getElementById("image_start<?php echo $cont; ?>").disabled=false;
									document.getElementById("image_start<?php echo $cont; ?>").value=1;
									document.getElementById("Youtube<?php echo $cont; ?>_img").disabled=true;' />
									<input type='hidden' name='mnu_img<?php echo $cont; ?>' id='image_start<?php echo $cont; ?>' value='1' disabled />
								</td>
								<td>
									<div id='wrapperVideo<?php echo $cont; ?>_img' style='display:none'>
										Seleccione imagen:&nbsp;
										<input type='file' name='Image<?php echo $cont; ?>' id='Video<?php echo $cont; ?>_img' disabled style='float:none;' />
									</div>
									<div id='wrapperVideo<?php echo $cont; ?>' style='display:none'>
										Seleccione vídeo:&nbsp;
										<input type='file' name='Video<?php echo $cont; ?>' id='Video<?php echo $cont; ?>' disabled style='float:none;' />
									</div>
								</td>
							</tr> 
							<tr>
								<td>Vídeo YouTube</td>
								<td>
									<input type='radio' name='mnu_video<?php echo $cont; ?>' value='2' 
									onclick='document.getElementById("wrapperVideo<?php echo $cont; ?>").style.display="none";
										document.getElementById("Video<?php echo $cont; ?>").disabled=true;
										document.getElementById("o_p_<?php echo $cont; ?>").value = 2;
										document.getElementById("wrapperVideo<?php echo $cont; ?>_img").style.display="none";
										document.getElementById("Video<?php echo $cont; ?>_img").disabled=true;
										document.getElementById("wrapperYoutube<?php echo $cont; ?>_img").style.display="none";
										document.getElementById("Youtube<?php echo $cont; ?>_img").disabled=true;
										document.getElementById("image_start<?php echo $cont; ?>").disabled=false;
										document.getElementById("image_start<?php echo $cont; ?>").value=2;
										document.getElementById("wrapperYoutube<?php echo $cont; ?>").style.display="block";
										document.getElementById("Youtube<?php echo $cont; ?>").disabled=false'; />
								</td>
								<td>
									<div id='wrapperYoutube<?php echo $cont; ?>' style='display:none'>
										Inserte còdigo:&nbsp;
										<input type='text' name='Youtube<?php echo $cont; ?>' id='Youtube<?php echo $cont; ?>' disabled style='float:none;width:200px;' />
										<p style='margin:0px;margin-left:10px;padding:0px;padding-top:5px;font-size:10px;'>
											http://www.youtube.com/watch?v=<span style='color:red;font-size:11px;'>1NQ7JQ3C4dM</span>&feature=g-feat
										</p>
									</div>
									<div id='wrapperYoutube<?php echo $cont; ?>_img' style='display:none'>
										Seleccione imagen:&nbsp;
										<input type='file' name='Youtube<?php echo $cont; ?>_img' id='Youtube<?php echo $cont; ?>_img' disabled style='float:none;' />
									</div>
								</td>
							</tr>				
						</tbody>
						</table>
					</div>
			
					<div id='box_gallery<?php echo $cont; ?>' style='display:none;padding-top:10px;padding-left:10px;background-color:#ededed;border:1px dotted #999;margin-top:-1px;'>
						<div class='box_gallery' style='clear:both;height:30px;'>		
						<div class='cp_formfield bold' style="width:100px !important;">
							<label for='Block<?php echo $cont; ?>_album'>Insertar galería:</label>
						</div>
						<?php
							$qG = "select ID, TITLE from ".preBD."images_gallery order by ID desc";
							$resultG = checkingQuery($connectBD, $qG);
						?>
						<div class='cp_table'>
							<select name='Block<?php echo $cont; ?>_album'  style="width:150px !important;">>
								<option value='0'<?php if($Album == 0) {echo " selected='selected'";} ?>>Sin galería</option>
								<?php while($album = mysqli_fetch_assoc($resultG)) {
									echo "<option value='".$album["ID"]."'";
									if($Album == $album["ID"]){
										echo " selected='selected'";
									}
									echo ">".$album["TITLE"]."</option>";
								}
								?>
							</select>
						</div>
					</div>
				</div>
					
				<?php else: ?>
					<div class='boxGestionType' style="margin-top:20px;">
						<ul id='opt_paragraph<?php echo $cont; ?>' class='listGestionType'>
							<li><div class="gestionTypeDesactive gestionTypeOff" id='list_img<?php echo $cont; ?>' title="Seleccione 'Sin galería' para activar esta opción">Imagen</div></li>
							<li><div class="gestionTypeDesactive gestionTypeOff" id='list_video<?php echo $cont; ?>' title="Seleccione 'Sin galería' para activar esta opción">Vídeo</div></li>
							<li><div class="gestionType gestionTypeOn" id='list_gallery<?php echo $cont; ?>'>Galería</div></li>
						</ul>
						<input type='hidden' name='o_p_<?php echo $cont; ?>' id='o_p_<?php echo $cont; ?>' value='5' />
					</div>
					
					<div id='box_gallery<?php echo $cont; ?>' style='display:block;padding-top:10px;padding-left:10px;background-color:#ededed;border:1px dotted #999; height:90px;'>
						<div class='box_gallery' style='clear:both;height:30px;'>		
							<div class='cp_formfield bold' style="width:100px !important;">
								<label for='Block<?php echo $cont; ?>_album'>Insertar galería:</label>
							</div>
							<?php
								$qG = "select ID, TITLE from ".preBD."images_gallery order by ID desc";
								$resultG = checkingQuery($connectBD, $qG);
							?>
							<div class='cp_table'>
								<select name='Block<?php echo $cont; ?>_album'  style="width:150px !important;">>
									<option value='0'<?php if($Album == 0){echo " selected='selected'";} ?>>Sin galería</option>
										<?php
										while($album = mysqli_fetch_assoc($resultG)) {
											echo "<option value='".$album["ID"]."'";
											if($Album == $album["ID"]){
												echo " selected='selected'";
											}
											echo ">".$album["TITLE"]."</option>";
										}
										?>
								</select>
							</div>
							<?php if($Album != 0): ?>
							<div class='box_radio_img' style='float:right;width:400px;'>
								<?php
									$q = "select * from ".preBD."images where STATUS = 1 and IDGALLERY = ".$Album." order by POSITION asc limit 0, 1";
									$result = checkingQuery($connectBD, $q);
									if($album = mysqli_fetch_assoc($result)):
								?>
									<a href='../files/gallery/image/<?php echo $album['URL']; ?>' class='lytebox' data-lyte-options='group:<?php echo $row_article['ID']; ?>'>
										<img src='../files/gallery/thumb/<?php echo $album['URL']; ?>' style='max-width:120px;max-height:85px; float:left; margin-right:10px;' />
									</a>
									<p class='radio_img_par' style='width:244px'>Seleccionar como miniatura del artículo:</p>
									<input name='thumbimage' id='select_new_thumb<?php echo $cont; ?>' type='radio' value='<?php echo $cont; ?>' onclick='close_thumb_new();' />
								<?php else: ?>
									<div style='width:98px;height:48px;padding:10px;float:left; margin-right:10px;font-weight:bold;text-align:center;border:1px solid #fff;'>
										No existen imágenes en esta galería
									</div>
								<?php endif; ?>								
							</div>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>
				
			<?php elseif($Type == "image"): 
				$openScript[$cont] = "Image"; ?>
				<div class='boxGestionType' style="margin-top:20px;">
					<ul id='opt_paragraph<?php echo $cont; ?>' class='listGestionType'>
						<li><div class="gestionType gestionTypeOn" id='list_img<?php echo $cont; ?>'>Imagen</div></li>
						<li><div class="gestionTypeDesactive gestionTypeOff" id='list_video<?php echo $cont; ?>' title="Elimine la imagen para activar esta opción">Vídeo</div></li>
						<li><div class="gestionTypeDesactive gestionTypeOff" id='list_gallery<?php echo $cont; ?>' title="Elimine la imagen para activar esta opción">Galería</div></li>
					</ul>
					<input type='hidden' name='o_p_<?php echo $cont; ?>' id='o_p_<?php echo $cont; ?>' value='0' />
				</div>
				<div id='box_img<?php echo $cont; ?>' style='display:block;background-color:#ededed;padding-top:10px;padding-bottom:10px;border:1px dotted #999;'>
					<table class='mnu_image' style='width:660px;'>
					<tbody>
						<tr>
							<td>
								<a href='../files/articles_temp/image/<?php echo $Image_block; ?>' class='lytebox' data-lyte-options='group:<?php echo $row_article['ID']; ?>' data-title='<?php if($Image_block == "ruler.gif") {echo "Regla de maquetación, ancho " . $width_thumb . " px";}else {echo "Imagen bloque ".$cont;}; ?>'>
									<img src='../files/articles_temp/thumb/<?php echo $Image_block; ?>' style='max-width:120px;' />
									<?php if($Image_block == "ruler.gif"): ?>
										<p style='color: #FF0000; margin-left: 10px; margin-top: -60px;'>Ancho <?php echo $width_thumb; ?> px</p>
									<?php endif; ?>
								</a>
							</td>
							<td style='padding-left:10px;'>
								<div class='box_radio_img' style='float:right;width:225px;'>
									<p class='radio_img_par'>Seleccionar como miniatura del artículo:</p>
									<input name='thumbimage' id='select_new_thumb<?php echo $cont; ?>' type='radio' value='<?php echo $cont; ?>' onclick='close_thumb_new();' />
								</div>
								<div class='box_radio_img' style='float:left;width:225px;'>
									<p class='radio_img_par'>Conservar imagen:</p>
									<input name='mnu_img<?php echo $cont; ?>' id='mnu_img_nothing<?php echo $cont; ?>' type='radio' value='-1' checked onclick='document.getElementById("Image<?php echo $cont; ?>").style.display="none";document.getElementById("Image<?php echo $cont; ?>").disabled=true; document.getElementById("box_dimensions<?php echo $cont; ?>").style.display="none"' />
								</div>
								<div class='box_radio_img' style='float:left;width:225px;'>
									<p class='radio_img_par'>Eliminar imagen:</p>
									<input name='mnu_img<?php echo $cont; ?>' id='mnu_img_delete<?php echo $cont; ?>' type='radio' value='0' onclick='document.getElementById("Image<?php echo $cont; ?>").style.display="none";document.getElementById("Image<?php echo $cont; ?>").disabled=true; document.getElementById("box_dimensions<?php echo $cont; ?>").style.display="none"' />
								</div>
								<div class='box_radio_img' style='float:left;width:225px;clear:both;'>
									<p class='radio_img_par'>Regla de maquetación:</p>
									<input name='mnu_img<?php echo $cont; ?>' i='mnu_img_regla<?php echo $cont; ?>' type='radio' value='2' onclick='document.getElementById("Image<?php echo $cont; ?>").style.display="none";document.getElementById("Image<?php echo $cont; ?>").disabled=true; document.getElementById("box_dimensions<?php echo $cont; ?>").style.display="none"' />
								</div>
								<div class='box_radio_img' style='clear:both;'>
									<p class='radio_img_par'>Nueva imagen:</p>
									<input name='mnu_img<?php echo $cont; ?>' id='mnu_img_new<?php echo $cont; ?>' type='radio' value='1' onclick='document.getElementById("Image<?php echo $cont; ?>").style.display="block";document.getElementById("Image<?php echo $cont; ?>").disabled=false; document.getElementById("box_dimensions<?php echo $cont; ?>").style.display="block"' />
								</div>
								<div class='box_radio_img' style='clear:both;'>
									<input type='file' name='Image<?php echo $cont; ?>' id='Image<?php echo $cont; ?>' disabled style='display:none' />
									<div id="box_dimensions<?php echo $cont; ?>" style='display:none' >
										<?php 
											$q2 = "select ID, IMAGE_C from ".preBD."articles_sections where type = 'article' order by TITLE asc";
											$result2 = checkingQuery($connectBD, $q2);
											while($row2 = mysqli_fetch_array($result2)){								
											?>
												<span id="box_infomartion_tam_<?php echo $row2['ID'];?>_<?php echo $cont; ?>" style="margin-top:4px;float:left; margin-left:20px; ;color: #c00; font-size:10px; <?php if ($Section == $row2['ID']) { ?> display:block; <?php }else{ ?> display:none <?php } ?> ">
													Ancho óptimo:  <?php echo $row2['IMAGE_C']."px"; ?>
												</span>
										<?php } ?>			
									</div>									
								</div>
							</td>
						</tr>
					<!--	<tr>
							<td>
								Enlazar imagen:
								<input type='checkbox' name='opt_link<?php echo $cont; ?>' name='opt_link<?php echo $cont; ?>' onclick='if(document.getElementById("box_link<?php echo $cont; ?>").style.display=="none"){document.getElementById("box_link<?php echo $cont; ?>").style.display="block"}else{document.getElementById("box_link<?php echo $cont; ?>").style.display="none"}'
								<?php if($Link_block != "" && $Link_block != "http://" && $Link_block != NULL) {echo "checked";} ?>	style='float:right;'/>
							</td>
							<td>
								<div id='box_link<?php echo $cont; ?>' <?php if($Link_block != "" && $Link_block != "http://" && $Link_block != NULL) {echo "style='display:block;'";}else{echo "style='display:none;'";} ?>>
									<input type='text' name='Link_img<?php echo $cont; ?>' id='Link_img<?php echo $cont; ?>' <?php if($Link_block != "" && $Link_block != "http://" && $Link_block != NULL) {echo "value='".$Link_block."'";}else{echo "value='http://'";} ?> style='float:left;width:350px;' />
									<select name='Target_link<?php echo $cont; ?>' id='Target_link<?php echo $cont; ?>'>
										<option value='_self' <?php if($Target_block == "_self") {echo " selected='selected'";} ?>>Misma ventana</option>
										<option value='_blank' <?php if($Target_block == "_blank") {echo " selected='selected'";} ?>>Nueva ventana</option>
									</select>
								</div>
							</td>
						</tr>
					-->
					</tbody>
					</table>
				</div>
				
			<?php elseif($Type == "video" || $Type == "youtube"): 
				$openScript[$cont] = "Video"; ?>
				<div class='boxGestionType' style="margin-top:20px;">
					<ul id='opt_paragraph<?php echo $cont; ?>' class='listGestionType'>
						<li><div class="gestionTypeDesactive gestionTypeOff" id='list_img<?php echo $cont; ?>' title="Elimine el video para activar esta opción">Imagen</div></li>
						<li><div class="gestionType gestionTypeOn" id='list_video<?php echo $cont; ?>'>Vídeo</div></li>
						<li><div class="gestionTypeDesactive gestionTypeOff" id='list_gallery<?php echo $cont; ?>'  title="Elimine el video para activar esta opción">Galería</div></li>
					</ul>
					<input type='hidden' name='o_p_<?php echo $cont; ?>' id='o_p_<?php echo $cont; ?>' <?php if($Type == "video") {echo "value='1'";}else{echo "value='2'";} ?> />
				</div>
				<div id='box_img<?php echo $cont; ?>' style='display:block;background-color:#ededed;padding-top:10px;padding-bottom:10px;border:1px dotted #999;'>
					<table class='mnu_video' style='width:685px;'>
					<tbody>
						<tr>
							<td colspan='3'>
							<?php if($Type == "video"): ?>
								<a class='bold' href='../files/articles_temp/video/<?php echo $Video_block; ?>' target='_blank' alt='Ver' title='Ver' style='font-size:13px;'>
									<img src='images/play_videoP.png' style='margin-right:10px;width:20px;' />
									<?php 
									$name_video = explode("-", $Video_block);
									$video = $name_video[1];
									echo $video;
									?>
							<?php else: ?>
								<a class='bold' href='http://www.youtube.com/watch?v=<?php echo $Video_block; ?>' target='_blank' alt='Ver en Youtube' title='Ver en Youtube' style='font-size:13px;'>
									<img src='images/play_videoP.png' style='margin-right:10px;width:20px;' />
									<?php echo youtube_data($Video_block, 'title'); ?>
								</a>
							<?php endif; ?>
							</td>
						</tr>
						<tr>
							<td width='40%'>
								<div class='box_radio_video'>
									<p class='radio_video_par'>Conservar vídeo:</p>
									<input name='mnu_video<?php echo $cont; ?>' id='mnu_video_nothing<?php echo $cont; ?>' type='radio' value='-1' checked onclick='closedVideoOptions(<?php echo $cont; ?>);active_mnu_img(<?php echo $cont; ?>);document.getElementById("edit_video_dimensions_<?php echo $cont; ?>").style.display="none";' />
								</div>
								<div class='box_radio_video'>
									<p class='radio_video_par'>Eliminar vídeo:</p>
									<input name='mnu_video<?php echo $cont; ?>' id='mnu_video_delete<?php echo $cont; ?>' type='radio' value='0' onclick='closedVideoOptions(<?php echo $cont; ?>);close_mnu_img(<?php echo $cont; ?>);document.getElementById("edit_video_dimensions_<?php echo $cont; ?>").style.display="none";' />
								</div>
								<div class='box_radio_video'>
									<p class='radio_video_par'>Nuevo vídeo:</p>
									<input name='mnu_video<?php echo $cont; ?>' id='mnu_video_new<?php echo $cont; ?>' type='radio' value='1' onclick='openVideoOptions(<?php echo $cont; ?>, "video");active_mnu_img(<?php echo $cont; ?>);document.getElementById("edit_video_dimensions_<?php echo $cont; ?>").style.display="none";' />
								</div>
								<div class='box_radio_video'>
									<p class='radio_video_par'>Vídeo YouTube:</p>
									<input name='mnu_video<?php echo $cont; ?>' id='mnu_video_youtube<?php echo $cont; ?>' type='radio' value='2' <?php if($Type == "youtube"){echo "checked ";} ?> onclick='openVideoOptions(<?php echo $cont; ?>, "youtube");active_mnu_img_Youtube(<?php echo $cont; ?>, "<?php echo $cont; ?>");document.getElementById("edit_video_dimensions_<?php echo $cont; ?>").style.display="none";' />
								</div>
							</td>
							<?php 
							$url_img = "../files/articles_temp/thumb/".$Image_block;
							if(file_exists($url_img) && ($Image_block != $Video_block) && ($Image_block != "" && $Image_block != NULL)): 
							?>
							<td width='24%'>
								<a href='../files/articles_temp/image/<?php echo $Image_block; ?>' class='lytebox' data-lyte-options='group:<?php echo $row_article['ID']; ?>' data-title='Imagen bloque <?php echo $cont; ?>'>
									<img src='../files/articles_temp/thumb/<?php echo $Image_block; ?>' style='max-width:120px;' />
								</a>
							<?php  elseif($Image_block == $Video_block): ?>
							<td width='24%'>
								<a href='http://img.youtube.com/vi/<?php echo $Video_block; ?>/0.jpg' class='lytebox' data-lyte-options='group:<?php echo $row_article['ID']; ?>' data-title='Imagen bloque <?php echo $cont; ?>'>
									<img src='http://img.youtube.com/vi/<?php echo $Video_block; ?>/0.jpg' style='width:120px;' />
								</a>
								<!--<img src='images/play_video.png' style='position:absolute;z-index:1000;margin-left:-75px;margin-top:30px;' />-->
							<?php else: ?>
							<td width='4%'>				
								&nbsp;
							<?php endif; ?>
							</td>
							<td style='padding-left:10px;' width='41%'>
								<div style='clear:both;margin-bottom:10px;height: 35px;<?php if((file_exists($url_img) && $Image_block != NULL && $Image_block != "") || $Image_block == $Video_block) {echo "display:block;";}else{echo "display:none;";} ?>'> 
									<p class='radio_img_par'>
										Seleccionar como miniatura<br/>del artículo:
										<input name='thumbimage' id='select_new_thumb<?php echo $cont; ?>' type='radio' value='<?php echo $cont; ?>' style='margin-left:40px;' onclick='close_thumb_new();' />
									</p>
								</div>
								<div style='clear:both;<?php if(($Image_block != NULL && $Image_block != "") && ($Type != "youtube")){echo "display:block;";}else{echo "display:none;";} ?>'>	
									<p class='radio_video_par'>Conservar imagen:</p>
									<input name='mnu_img<?php echo $cont; ?>' id='mnu_img_nothing<?php echo $cont; ?>' type='radio' value='-1' checked onclick='document.getElementById("Image<?php echo $cont; ?>").style.display="none";document.getElementById("Image<?php echo $cont; ?>").disabled=true; document.getElementById("edit_video_dimensions_<?php echo $cont; ?>").style.display="none";' />
								</div>	
								<div style='clear:both;<?php if(file_exists($url_img) && $Image_block != NULL && $Image_block != ""){echo "display:block;";}else{echo "display:none;";} ?>'>				
									<p class='radio_video_par'>Eliminar imagen:</p>
									<input name='mnu_img<?php echo $cont; ?>' id='mnu_img_delete<?php echo $cont; ?>' type='radio' value='0' onclick='document.getElementById("Image<?php echo $cont; ?>").style.display="none";document.getElementById("Image<?php echo $cont; ?>").disabled=true; document.getElementById("edit_video_dimensions_<?php echo $cont; ?>").style.display="none";' />
								</div>
					
								<div id='box_opt_img_youtube<?php echo $cont; ?>' style='<?php if($Type == "youtube"){echo "display:block;";}else{echo "display:none;";} ?>clear:both;'>
									<p class='radio_video_par'>Imagen Youtube:</p>
									<input name='mnu_img<?php echo $cont; ?>' id='opt_img_youtube<?php echo $cont; ?>' type='radio' value='2' <?php if (($Type == "youtube") && ($Image_block == $Video_block)) {echo "checked ";} ?> onclick='document.getElementById("Image<?php echo $cont; ?>").style.display="none";document.getElementById("Image<?php echo $cont; ?>").disabled=true; document.getElementById("edit_video_dimensions_<?php echo $cont; ?>").style.display="none";' />
								</div>
								<div style='clear:both;'>
									<p class='radio_video_par'>Nueva imagen:</p>
									<input name='mnu_img<?php echo $cont; ?>' id='mnu_img_new<?php echo $cont; ?>' type='radio' value='1' onclick='document.getElementById("Image<?php echo $cont; ?>").style.display="block";document.getElementById("Image<?php echo $cont; ?>").disabled=false;document.getElementById("edit_video_dimensions_<?php echo $cont; ?>").style.display="block";' />
								</div>
									
								<div style='clear:both;'>
									<input type='file' name='Image<?php echo $cont; ?>' id='Image<?php echo $cont; ?>' disabled style='display:none;width:230px;' />
									<div id="edit_video_dimensions_<?php echo $cont; ?>" style="display:none;float:left; margin-left:20px;">
										<?php 
											$q3 = "select ID, IMAGE_C from ".preBD."articles_sections where type = 'article' order by TITLE asc";
											$result3 = checkingQuery($connectBD, $q3);
											while($row3 = mysqli_fetch_array($result3)){
											?>
												<span id="edit_video_infomartion_<?php echo $row3['ID'];?>" style="margin-top:4px;color: #c00; font-size:10px; <?php if ($Section == $row3['ID']) { ?> display:block; <?php }else{ ?> display:none <?php } ?> ">
													Ancho óptimo:  <?php echo $row3['IMAGE_C']."px"; ?>
												</span>
										<?php } ?>								
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="4">
								<div id='opt_video<?php echo $cont; ?>' style='clear:both;display:none;'>
									<input type='file' name='Video<?php echo $cont; ?>' id='Video<?php echo $cont; ?>' disabled />
								</div>
								<div id='opt_youtube<?php echo $cont; ?>' style='clear:both;<?php if($Type == "youtube") {echo "display:block;";}else{echo "display:none;";} ?>'>
									<label for='Youtube' class='bold' style='padding-top:5px;'>Código:</label>
									<input type='text' name='Youtube<?php echo $cont; ?>' id='Youtube<?php echo $cont; ?>' <?php if($Type == 'youtube'){echo "value='".$Video_block."'";}else{echo "disabled";} ?> style='width:200px;margin-left:15px;margin-right: 15px;' />
									<p style='margin:0px;margin-left:10px;padding:0px;padding-top:5px;font-size:10px;'>
										http://www.youtube.com/watch?v=<span style='color:red;font-size:11px;'>1NQ7JQ3C4dM</span>&feature=g-feat
									</p>
								</div>
							</td>
						</tr>
					</tbody>
					</table>
				</div>
			<?php endif; ?>
<?php 	//ALINEACION ?>
	
			<div class='box_aling' style='height:32px;margin-top:10px;'>
				<div class='cp_formfield bold'>Posición:</div>
				<div class='cp_aling_pi center'><img class='image' src='images/left.png' /></div>
				<div class='cp_aling_pi center'><img class='image' src='images/center.png' /></div>
				<div class='cp_aling_pi center'><img class='image' src='images/right.png' /></div>
			</div>
			
			<div class='box_aling' style='height:20px;'>
				<div class='cp_formfield bold align_ie'>
					<label for='Block<?php echo $cont; ?>_align'>&nbsp;</label>
				</div>
				<div class='cp_aling_p center'>
					<input type='radio' name='Block<?php echo $cont; ?>_align' id='Block<?php echo $cont; ?>_image_align' value='left'<?php if($Align_block == "left"){echo " checked";} ?> />
				</div>
				<div class='cp_aling_p center'>
					<input type='radio' name='Block<?php echo $cont; ?>_align' id='Block<?php echo $cont; ?>_image_align' value='center'<?php if($Align_block == "center") {echo " checked";} ?> />
				</div>
				<div class='cp_aling_p center'>
					<input type='radio' name='Block<?php echo $cont; ?>_align' id='Block<?php echo $cont; ?>_image_align' value='right'<?php if($Align_block == "right"){echo "checked";} ?> />
				</div>
			</div>
	
		
<?php	//PIE DE FOTO ?>
			<div class='box_foot' style='width:100%;clear:both;height:55px;display:block;margin-bottom:5px;margin-top:10px;'>
				<div class='cp_formfield bold'>
					<label for='Block<?php echo $cont; ?>_foot'>Pié de imagen:</label>
				</div>
				<textarea name='Block<?php echo $cont; ?>_foot' id='Block<?php echo $cont; ?>_foot' style='width:435px;height:50px;'><?php echo $Foot_block; ?></textarea>
			</div>


<?php	//DESCARGAS			
				$q3 = "select * from ".preBD."paragraphs_file_temp where IDPARAGRAPH = " . $row_blocks["ID"] . " order by POSITION asc";
				
				$result_file = checkingQuery($connectBD, $q3);
				$num_files = mysqli_num_rows($result_file);
?>
				<?php if($cont < $max_subida): ?>
					<?php if($num_files > 0): 
						$j = 0;
					?>
						<div class='box_download' style='clear:both;'>
							<table style='vertical-align:top;'>
							<tbody>
								<tr>
									<td style='vertical-align:top;'>
										<div class='cp_formfield bold'>Documentos:</div>
									</td>
									<td style='vertical-align:top;'>
									<?php while($files = mysqli_fetch_assoc($result_file)): ?>
										<div class='row_download' style='margin-top:5px;width:358px;float:left;'>
											<div class='cp_table' style='margin-right:10px;'>
											<?php	
												$urlIcon = "images/icons/";
												$extension = explode(".", $files['URL']); 
												$num_extension = count($extension);
												$name_icon = "icon_".$extension[$num_extension-1].".gif";
												$url_icon="images/icons/".$name_icon;
												if(file_exists($url_icon)){
													echo "<img src='".$url_icon."' style='border: none;' />";
												}else{
													echo "<img src='".$urlIcon ."icon_unk.gif' style='border: none;' />";
												}
											?>
											</div>
											<div class='cp_table' style='margin-right:10px;'>
												<a href='../files/articles/doc/<?php echo $files['URL']; ?>' target='_blank'>
													<?php 
														if($files['TITLE'] != "") {
															echo cutting($files['TITLE'],40);
														} else {
															$name_file = explode("-", $files['URL'], 2);
															echo $name_file[1];
														}
													?>
													&nbsp;&nbsp;<?php echo $files["SIZE"]; ?>
												</a>
											</div>
										</div>
										<div style="float:left; margin-top:5px;">
											<a href='#' alt='Eliminar' title='Eliminar' onclick='optionActionFile("delete_file", "<?php echo $id; ?>","<?php echo $files["ID"]; ?>");validate(this);return false;'>
												<img src='images/delete.png' style='border:none' />
											</a>
											<img src='images/edit.png' style='border:none; cursor:pointer;' onclick='if(document.getElementById("box_adjunto<?php echo $files["ID"]; ?>").style.display=="none"){document.getElementById("box_adjunto<?php echo $files["ID"]; ?>").style.display="block"}else{document.getElementById("box_adjunto<?php echo $files["ID"]; ?>").style.display="none";}' />
											<!-- Posición de los adjuntos -->
											<?php 
											if ($files["POSITION"] == 1) { ?>
												<img class='image' src='images/up_off.png' alt='' title='' />
											<?php } else { ?>
												<img class='image' src='images/up.png' alt='Subir' title='Subir' style='border:none; cursor:pointer;' onclick='optionActionFile("go_up", "<?php echo $id; ?>", "<?php echo $files["ID"]; ?>");validate(this);return false;' />
											<?php }
											if ($files["POSITION"] == $num_files) { ?>
												<img class='image' src='images/down_off.png' alt='' title='' />
											<?php }else { ?>
												<img class='image' src='images/down.png' alt='Bajar' title='Bajar' style='border:none; cursor:pointer;' onclick='optionActionFile("lower", "<?php echo $id; ?>", "<?php echo $files["ID"]; ?>");validate(this);return false;' />
											<?php } ?>										
										</div>
									
										<div id ="box_adjunto<?php echo $files["ID"]?>" style="display:none;">
											<label>Título documento: </label>
											<input id="title_adjunto<?php echo $j?>_<?php echo $cont; ?>" name="title_adjunto<?php echo $j?>_<?php echo $cont; ?>" type="text" value="<?php echo $files["TITLE"]?>" >
											<input type="hidden" id="num_adjunto<?php echo $j?>_<?php echo $cont; ?>" name="num_adjunto<?php echo $j?>_<?php echo $cont; ?>" value="<?php echo $files['ID']?>">
											<br/><br/>
										</div>
										<input type="hidden" id="total_adjuntos<?php echo $cont; ?>" name="total_adjuntos<?php echo $cont; ?>" value="<?php echo $num_files; ?>" />
								<?php $j++; 
								endwhile; ?>
									</td>
								</tr>
							</tbody>
							</table>
					<?php endif; ?>
					<div class='add_download' id='add_file<?php echo $cont; ?>' style='display:block;margin-top:10px;clear:both;'>
						<div class='cp_formfield bold'>
							<label for='Agregar documeto'>&nbsp;</label>
						</div>
						Agregar adjunto&nbsp;
						<img class='image' src='images/add.png' alt='Agregar adjunto' title='Agregar adjunto' style='cursor:pointer;' onclick='openBoxFile(<?php echo $cont; ?>);'>
					</div>
					
					<div class='form_add_download' id='box_file<?php echo $cont; ?>' style='display:none;'>
						<div class='cp_formfield bold'>
							<label for='Block<?php echo $cont; ?>_title_file'>T&iacute;tulo documento:</label>
						</div>
						<input type='text' name='Block<?php echo $cont; ?>_title_file' id='Block<?php echo $cont; ?>_title_file' size='40' />
						<input type='file' name='Block<?php echo $cont; ?>_file' id='Block<?php echo $cont; ?>_file' size='40' />
					</div>
				
				<?php endif; ?>
				
			</div>
			<?php
			$cont++;
			endwhile; ?>
			<br/>
			
			<div class='cp_box dotted' style='height:390px;display:none;'>		
				<div class='cp_mnu_subtitle title_block'>Noticias relacionadas</div>
				<div style="width: 100%;height: 260px;margin-top:20px;">
					
					<?php
						$qArticles = "";
						for($a=0;$a < count($related);$a++) {
							$qArticles .= " and ID != " . $related[$a]->ID;
						}
						
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
							
							$u = "select * from ".preBD."articles where IDSECTION = ". $row2->ID ." and (STATUS = 1 or STATUS = 2) and TRASH = 0".$qArticles." order by DATE_START desc";
							
							$result4 = checkingQuery($connectBD, $u);
							?>
							<select class="select-origen" id="origen<?php echo $row2->ID; ?>" name="origen<?php echo $row2->ID; ?>[]" multiple="multiple" style="width:300px; height:200px; float:left;">
							<?php while($row4 = mysqli_fetch_object($result4)){ ?>
								<option id="option-<?php echo $row2->ID ?>_<?php echo $row4->ID ?>" value="<?php echo $row2->ID."_".$row4->ID ?>"> <?php echo $row4->ID . "-" .$row4->TITLE; ?></option>
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
						<select name="destino[]" id="destino" multiple="multiple" style="width:300px; height:200px; float:left;">
							<?php for($a=0;$a<count($related);$a++) { ?>
								<option id="option-<?php echo $related[$a]->IDSECTION; ?>_<?php echo $related[$a]->ID ?>" value="<?php echo $related[$a]->IDSECTION."_".$related[$a]->ID ?>"> <?php echo $related[$a]->ID . "-" .$related[$a]->TITLE; ?></option>
							<?php } ?>
						</select>
					</div>							
				</div>
				<div style="text-align:justify;font-weight:bold;font-style:italic;width:100%;display:block;clear:both;margin-top:10px;">
					<p><em style="color:#c00;">Debe seleccionar un número par de noticias</em></p>
					<br/>
					Para seleccionar más de un elemento a la vez de la lista, mantenga pulsada la tecla Ctrl (en Windows) o Cmd (en Macintosh).
				</div>
			</div>
			
			
			<br/>
			<div class='cp_table' style='float:right;'>
				<div class='cp_table150' >&nbsp;
				<input type='button' name='insertParagraphs' value='Insertar bloque' onclick='optionAction("add", "<?php echo $id; ?>");validate(this);return false;' />
				</div>
				<div class='cp_table20 save_ie3'>&nbsp;</div>
				<div class='cp_table'>
					<input type='button' name='save' value='Guardar' onclick='sendFormArticle();optionAction("save", "<?php echo $id; ?>");validate(this);return false;' />
				</div>
			</div>
			<br/><br/>
			<?php /*
			<div style='width:100%;display:block;'>
				<input type='button' style='margin-top:-35px;' name='save_preview' value='Previsualizar' onclick='optionAction("preview", "<?php echo $id; ?>");validate(this);window.open("../index.php?view=preview","preview");return false;'/>
			</div>
			*/ ?>
			
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