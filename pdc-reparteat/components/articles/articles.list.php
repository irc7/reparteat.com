<?php

	if (!isset($_GET['recodsperpage'])) {
		$recodsperpage = 25;
	}else {
		$recodsperpage = $_GET['recodsperpage'];
	}
	if (!isset($_GET['page'])) {
		$page = 1;
		$firstrecord = 0;
	}
	else {
		$page = $_GET['page'];
		$firstrecord = ($page-1) * $recodsperpage;
	}
	if (!isset($_GET['search']) || $_GET['search'] == "") {
		$search = NULL;
		$searchq = "";
	}
	else {
		$search = $_GET['search'];
		$searchq = " AND ".preBD."articles.TITLE LIKE '%".$search."%'";
		$searchq .= " OR ".preBD."articles.SUBTITLE LIKE '%".$search."%'";
		$searchq .= " OR ".preBD."articles.ID LIKE '%".$search."%'";
		$searchq .= " OR ".preBD."articles.DATE_START LIKE '%".$search."%'";
		$searchq .= " OR ".preBD."articles.AUTHOR LIKE '%".$search."%'";
		$searchq .= " OR ".preBD."articles.SUMARY LIKE '%".$search."%'";
		$searchq .= " OR ".preBD."articles.INTRO LIKE '%".$search."%'";
	}
	
	if (!isset($_GET['record']) || $_GET['record'] == 0) {
		$record = NULL;
		$recordq = "";
	}else {
		$record = $_GET['record'];
		$recordq = " AND ".preBD."articles.ID='".$record."'";
		$searchq = "";
	}

	if ($searchq == "" && $recordq == "") {
		$trashq = " AND ".preBD."articles.TRASH='".$trash."'";
	}else {
		$trashq = " AND ".preBD."articles.TRASH='".$trash."'";
	}
	
	if (!isset($_GET['filtersection']) || $_GET['filtersection'] == 0) {
		$filtersection = 0;
		$sectionq = '';
	}else {
		$filtersection = $_GET['filtersection'];
		$sectionq = " AND ".preBD."articles.IDSECTION='".$filtersection."'";
	}
	
	if (isset($_GET['type']) || trim($_GET['type']) != "") {
		$typeArticle = trim($_GET['type']);
	}else {
		$typeArticle = "article";
	}
	if ($trash == 1) {
		$tpl = 'trash';
	}else {
		$tpl = 'option';
	}
	
	$q1 = "SELECT ".preBD."articles.* FROM ".preBD."articles where ".preBD."articles.TYPE = '".$typeArticle."'" . $searchq . $recordq . $trashq . $sectionq;

	$result1 = checkingQuery($connectBD, $q1);
	$totalrecods = mysqli_num_rows($result1);
	$totalpages = ceil($totalrecods / $recodsperpage);
?>
	<div class='cp_box darkshaded cp_height30'>
		<form id='dropdown' name='dropdown' method="get" action="index.php">
			<div class='cp_table230 top'>
				<input type='hidden' name='mnu' value='<?php echo $mnu; ?>'>
				<input type='hidden' name='com' value='<?php echo $com; ?>'>
				<input type='hidden' name='tpl' value='<?php echo $tpl; ?>'>
				<span class=' white'>Mostrar&nbsp;&nbsp;</span>
				<select name='recodsperpage' id='recodsperpage' width='20' onchange='dropdown.submit();'>
					<option value='5'<?php if($recodsperpage == 5){echo " selected='selected'";} ?>>5</option>
					<option value='10'<?php if($recodsperpage == 10){echo " selected='selected'";} ?>>10</option>
					<option value='25'<?php if($recodsperpage == 25){echo " selected='selected'";} ?>>25</option>
					<option value='50'<?php if($recodsperpage == 50){echo " selected='selected'";} ?>>50</option>
				</select>
				<span class='white'>&nbsp;de <?php echo $totalrecods; ?></span>
			</div>
		<?php if($tpl == "trash"): ?>
			<div class='cp_table180 top'>	
		<?php else: ?>
			<div class='cp_table240 top'>
		<?php endif; ?>
				<input type='text' name='search' id='search' size='20' maxlength='150' value='<?php echo $search; ?>'>
				<input type='submit' value='Buscar'>
			</div>
		<?php if ($trash == 0): ?>
			<div class='cp_table220 top right'>
				<span class='white'>Sección: </span>
				<select name='filtersection' id='filtersection' onchange='dropdown.submit();'>
					<option value='0'<?php if($filtersection == 0){echo " selected='selected'";} ?>></option>
					<?php
					$q2 = "SELECT * FROM ".preBD."articles_sections where TYPE = '".$typeArticle."' ORDER BY TITLE ASC";
					$result2 = checkingQuery($connectBD, $q2);
					while($row2 = mysqli_fetch_array($result2)):
					?>
					<option value='<?php echo $row2['ID']; ?>'<?php if ($filtersection == $row2['ID']) {echo " selected='selected'";} ?>>
						<?php echo strip_tags(substr($row2['TITLE'],0,22)); ?>
					</option>
					<?php endwhile; ?>
				</select>
			</div>
		<?php else: ?>
			<div class='cp_table top right'>
				<div class='cp_table140 top right' style='padding-top: 5px;'>
				<?php if (allowed($mnu)){
					if(check_temp()){
						$msgAlert = "¡ATENCI&Oacute;N! Si alg&uacute;n usuario est&aacute; editando alg&uacute;n art&iacute;culo, perder&aacute; los cambios. &iquest;Desea continuar?";
						$urlAlert = "modules/articles/delete_file_temp.php?mnu=".$mnu."&type=".$typeArticle;
					?>
					<a href='#' onclick="alertConfirm('<?php echo $msgAlert . "', '" . $urlAlert; ?>');" alt='Eliminar archivos temporales' title='Eliminar archivos temporales'>
						<span class='white'>Vaciar temporales&nbsp;&nbsp;
							<img src='images/temp_off.png' class='image middle' style='margin-bottom: 5px;' />
						</span>
					</a>
				<?php }
				}else{ ?>
					<span class='white'>
						Vaciar temporales&nbsp;&nbsp;
						<img src='pdc-ihp/images/temp.png' class='image middle' style='margin-bottom: 5px;' alt='' title='' />
					</span>
				<?php } ?>
				</div>
				<div class='cp_table140 top right' style='padding-top: 5px;'>
					<?php if (allowed($mnu) == 1): 
						$msgAlert = "¡ATENCIÓN! Va a vaciar todo el contenido de la papelera";
						$urlAlert = "modules/articles/empty_trash.php";
					?>
						<a href='#' onclick="alertConfirm('<?php echo $msgAlert . "', '" . $urlAlert; ?>');">
							<span class='white'>
								Vaciar papelera&nbsp;&nbsp;
								<img src='images/trash_white.png' class='image middle' style='margin-bottom: 5px;' alt='Vaciar papelera' title='Vaciar papelera' />
							</span>
						</a>
					<?php else: ?>
						<span class='white'>
							Vaciar papelera&nbsp;&nbsp;
							<img src='pdc-ihp/images/trash_off.png' class='image middle' style='margin-bottom: 5px;' alt='Vaciar papelera' title='Vaciar papelera' />
						</span>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
		</form>
	</div>
	
		<div class='cp_box cp_height30 row'>
			<div class='col-xs-2 cp_title top'>
				<div class='top'>&nbsp;</div>
				<div class='bold'>#</div>
			</div>
			<div class='col-xs-5 cp_title'>
				<div class='bold top'>Título</div>
				<div class='bold top'>Autor</div>
			</div>
			<div class='col-xs-2 cp_title'>
				<div class='bold top'>Sección</div>
				<div class='bold top'>Fecha/Hora</div>
			</div>
			<div class='col-xs-3 cp_title'>
				<div class='bold top'>&nbsp;</div>
				<div class='bold top' style='margin-left: 10px;'>Estado</div>
			</div>
		</div>
		<div class="separator20">&nbsp;</div>
		<?php 
		$q = "SELECT ".preBD."articles.*,
				".preBD."url_web.SLUG as slug
				FROM ".preBD."articles 
				inner join ".preBD."url_web 
				on ".preBD."url_web.ID_VIEW = ".preBD."articles.ID and ".preBD."url_web.TYPE = 'article'
				where ".preBD."articles.TYPE = '".$typeArticle."'" . $searchq . $recordq . $trashq . $sectionq . " ORDER BY ".preBD."articles.ID desc LIMIT " . $firstrecord . ", " . $recodsperpage;
	
		
		$result = checkingQuery($connectBD, $q);
		$cont = 0;
		while ($row = mysqli_fetch_assoc($result)):
			$type = $row["TYPE"];
			
			$img_youtube = substr($row["THUMBNAIL"], 0, 2);
			$url_youtube = substr($row["THUMBNAIL"], 2, strlen($row["THUMBNAIL"]));	
			
			$titulo_article = str_replace('"','',$row["TITLE"]); 
		?>	
		<div class='cp_box shaded cp_height105 row'>
			<div class='cp_number bold center m1'><?php echo $row['ID']; ?></div>
			<?php	
				if ($row['THUMBNAIL'] == ""){
					$url =	"images/emptythumbnail.gif";		
				} else if($img_youtube  == "v="){
					$url =	"http://img.youtube.com/vi/".$url_youtube."/0.jpg";
				}else{
					$url =	"../files/articles/thumb/".$row['THUMBNAIL'];
				}
			?>
			<div class='col-xs-2' style='height:75px;overflow:hidden;'>
				<a style='font-size:14px;' href='index.php?mnu=<?php echo $mnu; ?>&com=articles&tpl=edit&record=<?php echo $row['ID']; ?>'>
					<img src='<?php echo $url; ?>' style='border:none;width:110px;'/>
				</a>
			</div>	
			<div class='col-xs-5 top'>
				<div class='bold'>	
					<a style='font-size:14px;' href='index.php?mnu=<?php echo $mnu; ?>&com=articles&tpl=edit&record=<?php echo $row['ID']; ?>'>
						<?php echo strip_tags(cutting(stripslashes($row['TITLE']),80)); ?>
					</a>
				</div>
				<div style='height: 7px;'>&nbsp;</div>
				<div class=''><?php echo $row['AUTHOR']; ?></div>
				<div style='height: 7px;'>&nbsp;</div>
				<div class=''>
					<a href='<?php echo DOMAIN . $row["slug"]; ?>' target="_blank">
						<img src='images/link.png' style='border:none; padding-top: 4px;' alt='Abrir en nueva ventana' title='Abrir en nueva ventana' />
					</a>
					<div id='content_url<?php echo $cont; ?>' style='visibility:hidden;position:absolute;border:1px solid #fff;width:470px;padding:5px;background-color:#eee;'>
						<p class='cp_table60 bold floatLeft' style='margin-top:0px'>Enlace: </p>
						<p style='margin-top:0px;margin-left:60px;'>
							<?php echo DOMAIN . $row["slug"]; ?>
						</p>
					</div>
				</div>
			</div>
			<div class='col-xs-2 top'>
				<?php $title_section = getsection($row['IDSECTION']); ?>
				<div class=''>
					<?php 
						echo substr($title_section,0,35);
						if (strlen($title_section) > 35) {
							echo "...";
						}
					?>
				</div>
				<br/>
				<div class='separator5'>&nbsp;</div>
				<div class=''><?php echo transDate($row['DATE_START']); ?></div>
				<div class='separator5'>&nbsp;</div>
				
				<?php if ($row['DATE_END'] != "0"): ?>
					<div class='' style='font-size:11px;'><?php echo transDate($row['DATE_END']); ?></div>
				<?php endif; ?>
			</div>
			<div class='col-xs-3 top' >
				<div class='col-xs-5'>
					<?php 
					$msgAlert_borrador = "¡ATENCIÓN! Va a cambiar el estado del artículo ". $titulo_article . " a Borrador";
					$urlAlert_borrador = "modules/articles/unpublish_record.php?mnu=".$mnu."&type=".$typeArticle."&record=".$row["ID"]."&trash=".$row["TRASH"];
					
					$msgAlert_publicado = "¡ATENCIÓN! Va a cambiar el estado del artículo ". $titulo_article . " a Publicado";
					$urlAlert_publicado = "modules/articles/publish_record.php?mnu=".$mnu."&type=".$typeArticle."&record=".$row["ID"]."&trash=".$row["TRASH"];		
					
					$msgAlert_invisible = "¡ATENCIÓN! Va a cambiar el estado del artículo ". $titulo_article . " a Invisible";
					$urlAlert_invisible = "modules/articles/invisible_record.php?mnu=".$mnu."&type=".$typeArticle."&record=".$row["ID"]."&trash=".$row["TRASH"];		
					
					if ($row['STATUS'] == 0){ ?>
						<img class='image pointer' src='images/checked_off.png' alt='Pasar a publicado' title='Pasar a publicado'  onclick='alertConfirm("<?php echo $msgAlert_publicado; ?>", "<?php echo $urlAlert_publicado; ?>")' style="float:left; clear:both; margin-bottom:5px;"/><span style="margin-left:3px;position:relative;">Pub.</span>					
						<br/>
						<img class='image pointer' src='images/invisible_off.png' alt='Pasar a invisible' title='Pasar a invisible'  onclick='alertConfirm("<?php echo $msgAlert_invisible; ?>", "<?php echo $urlAlert_invisible; ?>")' style="float:left; clear:both; margin-bottom:5px;"/><span style="margin-left:3px;position:relative; top:3px;">Inv.</span>								
						<br/>
						<img class='image' src='images/unchecked.png' alt='Borrador' title='Borrador' style="float:left; clear:both;"/><span style="position:relative; top:5px;margin-left:3px;">Bor.</span>				
					<?php }else if ($row['STATUS'] == 1){ ?>
						<img class='image' src='images/checked.png' alt='Publicado' title='Publicado' style="float:left; clear:both; margin-bottom:5px;"/><span style="margin-left:3px;position:relative;">Pub.</span>								
						<br/>
						<img class='image pointer' src='images/invisible_off.png' alt='Pasar a invisible' title='Pasar a invisible'  onclick='alertConfirm("<?php echo $msgAlert_invisible; ?>", "<?php echo $urlAlert_invisible; ?>")' style="float:left; clear:both; margin-bottom:5px;"/><span style="margin-left:3px;position:relative; top:3px;">Inv.</span>				
						<br/>
						<img class='image pointer' src='images/unchecked_off.png' alt='Pasar a vorrador' title='Pasar a borrador' onclick='alertConfirm("<?php echo $msgAlert_borrador; ?>", "<?php echo $urlAlert_borrador; ?>")' style="float:left; clear:both;"/><span style="position:relative; top:5px;margin-left:3px;">Bor.</span>					
					<?php }else if ($row['STATUS'] == 2){ ?>				
						<img class='image pointer' src='images/checked_off.png' alt='Pasar a publicado' title='Pasar a publicado'  onclick='alertConfirm("<?php echo $msgAlert_publicado; ?>", "<?php echo $urlAlert_publicado; ?>")' style="float:left; clear:both; margin-bottom:5px;"/><span style="margin-left:3px;position:relative;">Pub.</span>					
						<br/>
						<img class='image' src='images/invisible.png' alt='Invisible' title='Invisible'  style="float:left; clear:both; margin-bottom:5px;"/><span style="margin-left:3px;position:relative; top:3px;">Inv.</span>								
						<br/>
						<img class='image pointer' src='images/unchecked_off.png' alt='Pasar a vorrador' title='Pasar a borrador' onclick='alertConfirm("<?php echo $msgAlert_borrador; ?>", "<?php echo $urlAlert_borrador; ?>")' style="float:left; clear:both;"/><span style="position:relative; top:5px;margin-left:3px;">Bor.</span>					
					<?php }?>						
				</div>
				<div class='col-xs-7'>
				<!-- DUPLICAR ARTÍCULOS -->
					<div class='col-xs-5'>					
				<?php if (allowed($mnu)){
						$msgAlert = "¡ATENCIÓN! Va a duplicar el artículo ". $titulo_article;
						$urlAlert = "modules/articles/duplicate_record.php?mnu=".$mnu."&type=".$typeArticle."&record=".$row["ID"];
					?>			
						<img class='image pointer' src='images/duplicar_on.png' alt='Duplicar artículo' title='Duplicar artículo' onclick='alertConfirm("<?php echo $msgAlert; ?>", "<?php echo $urlAlert; ?>")' />
					
				<?php }else{ ?>
						<img class='image' src='images/duplicar_off.png' alt='' title='' />
				<?php } ?>		
					</div>
			
					<div class='col-xs-5' style="float:right;">
				<?php if (allowed($mnu)): ?>
						<a href='index.php?mnu=<?php echo $mnu; ?>&com=<?php echo $com; ?>&tpl=<?php echo $tpl; ?>&action=Deleterecord&record=<?php echo $row['ID']; ?>'>
							<img class='image' src='images/delete.png' alt='Eliminar' title='Eliminar' />
						</a>
					
				<?php else: ?>
					
						<img class='image' src='images/delete_off.png' alt='' title='' />
				<?php endif; ?>
					</div>
				<?php if ($trash == 1): ?>
					<div style='height: 7px;'></div>
					<?php if (allowed($mnu)): 
						$msgAlert = "¡ATENCIÓN! Va a recuperar de la papelera el artículo ". $titulo_article;
						$urlAlert = "modules/articles/recover_record.php?mnu=".$mnu."&type=".$typeArticle."&record=".$row["ID"];
					?>
						<div class='col-xs-5'>
							<img class='image pointer' src='images/recover.png' alt='Recuperar' title='Recuperar' onclick='alertConfirm("<?php echo $msgAlert; ?>", "<?php echo $urlAlert; ?>")' />
						</div>
					<?php else: ?>
						<div class='col-xs-5'>
							<img class='image' src='images/recover_off.png' alt='' title='' />
						</div>
					<?php endif; ?>
					
				<?php elseif($trash == 0 && $row['IDSECTION'] == 7): ?>
						<div class="separator10">&nbsp;</div>
						<div class='col-xs-12'>
					<?php if($row['VIEW_FUNDATION'] == 0): 
							$msgAlert = "¡ATENCIÓN! Va a mostrar la noticia ". $titulo_article . " en la Fundación.¿Desea continuar?";
							$urlAlert = "modules/articles/publish_fundation.php?mnu=".$mnu."&type=".$typeArticle."&record=".$row["ID"]."&fundacion=1";
					?>
							<img class='image pointer' src='images/fundacion.png' alt='Mostrar en Fundación IHP' title='Mostrar en Fundación IHP' onclick='alertConfirm("<?php echo $msgAlert; ?>", "<?php echo $urlAlert; ?>")' />
						
					<?php elseif($row['VIEW_FUNDATION'] == 1): 
							$msgAlert = "¡ATENCIÓN! Va a quitar la noticia ". $titulo_article . " de la Fundación.¿Desea continuar?";
							$urlAlert = "modules/articles/publish_fundation.php?mnu=".$mnu."&type=".$typeArticle."&record=".$row["ID"]."&fundacion=0";
					?>
						
							<img class='image pointer' src='images/fundacion_on.png' alt='Quitar de la Fundación IHP' title='Quitar de la Fundación IHP'  onclick='alertConfirm("<?php echo $msgAlert; ?>", "<?php echo $urlAlert; ?>")' />
					<?php endif; ?>
						</div>
						<div class="separator10">&nbsp;</div>
						<div class='col-xs-12'>
					<?php if($row['VIEW_ZP'] == 0): 
							$msgAlert = "¡ATENCIÓN! Va a mostrar la noticia ". $titulo_article . " en la ZonaPrivada.¿Desea continuar?";
							$urlAlert = "modules/articles/publish_zp.php?mnu=".$mnu."&type=".$typeArticle."&record=".$row["ID"]."&zp=1";
					?>
							<img class='image pointer' src='images/zp.png' alt='Mostrar en Fundación IHP' title='Mostrar en Fundación IHP' onclick='alertConfirm("<?php echo $msgAlert; ?>", "<?php echo $urlAlert; ?>")' />
						
					<?php elseif($row['VIEW_ZP'] == 1): 
							$msgAlert = "¡ATENCIÓN! Va a quitar la noticia ". $titulo_article . " de la ZonaPrivada.¿Desea continuar?";
							$urlAlert = "modules/articles/publish_zp.php?mnu=".$mnu."&type=".$typeArticle."&record=".$row["ID"]."&zp=0";
					?>
							<img class='image pointer' src='images/zp_on.png' alt='Quitar de la Fundación IHP' title='Quitar de la Fundación IHP'  onclick='alertConfirm("<?php echo $msgAlert; ?>", "<?php echo $urlAlert; ?>")' />
					<?php endif; ?>
						</div>
				<?php endif; ?>
				</div>
				
			</div>
			<div class="separator10">&nbsp;</div>
		</div>
		
	<?php $cont++;
		endwhile; 
	
	$previouspage = $page - 1;
	$nextpage = $page + 1;
	$urlPag = "index.php?mnu=".$mnu."&com=".$com."&tpl=".$tpl;
	$urlPag .= "&type=".$typeArticle."&filtersection=".$filtersection."&recodsperpage=".$recodsperpage."&page=".$previouspage."&search=".$search;
	if ($totalpages > 1):
	?>
		<div class='cp_box dotted cp_height25' style="display:block;clear:both">
		<?php if ($page > 1): ?>
			<div class='cp_table' style='margin-right:3px;'>
				<a href='<?php echo $urlPag; ?>'>
					<<
				</a>
			</div>
		<?php endif;
		if ($page > 9): ?>
			<div class='cp_table cp_pages center shaded'>
			<a href='<?php echo $urlPag; ?>&page=1'>1</a></div>
			<div class='cp_table' style='margin-left:3px;'>...</div>
		<?php endif;
		for ($i=1; $i < $totalpages + 1; $i++):
			if ($i > ($page - 9) && $i < ($page + 9)):
		?>
				<div style='margin-right:3px;' class='cp_table cp_pages center<?php if ($page == $i) {echo" darkshaded";}else{echo" shaded";} ?>'>
					<a href='<?php echo $urlPag . "&page=".$i; ?>'<?php if ($page == $i) {echo" style='color: white;'";} ?>><?php echo $i; ?></a>
				</div>
			<?php endif; 
			endfor; 
		endif;
		if ($page < ($totalpages - 9)): ?>
			<div class='cp_table' style='margin-left:3px;'>...</div>
			<div class='cp_table cp_pages center shaded' style='margin-right:3px;'>
				<a href='<?php echo $urlPag . "&page=".$totalpages; ?>'>
					<?php echo $totalpages; ?>
				</a>
			</div>
	<?php endif; ?>
	<?php if ($page < $totalpages): ?>
		<div class='cp_table' style='margin-left:3px;'>
			<a href='<?php echo $urlPag . "&page=".$nextpage; ?>'>
				>>
			</a>
		</div>
	<?php endif; ?>
	</div>	