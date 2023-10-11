<?php
	if (!isset($_GET['recodsperpage'])) {
		$recodsperpage = 25;
	}
	else {
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
	}else {
		$search = $_GET['search'];
		$searchq = " WHERE TITLE LIKE '%".$search."%'";
		$searchq .= " OR ID LIKE '%".$search."%'";
		$searchq .= " OR AUTHOR LIKE '%".$search."%'";
		$searchq .= " OR TEXT LIKE '%".$search."%'";
	}	
		
	if (!isset($_GET['record'])) {
		$record = NULL;
		$recordq = "";
	}else {
		$record = $_GET['record'];
		$recordq = " WHERE ID='".$record."'";
		$searchq = "";
	}
	
	if (!isset($_GET['filtergallery']) || intval($_GET['filtergallery']) == 0) {
		$filtergallery = NULL;
		$galleryq = '';
	}else {
		$filtergallery = $_GET['filtergallery'];
		if($search == NULL && $record == NULL) {
			$galleryq = " WHERE IDGALLERY='".$filtergallery."'";
		}else {
			$galleryq = " AND IDGALLERY='".$filtergallery."'";
		}
	}	
	
	$q1 = "SELECT * FROM ".preBD."images" . $searchq . $recordq . $galleryq;
	
	$result1 = checkingQuery($connectBD, $q1);
	$totalrecods = mysqli_num_rows($result1);
	$totalpages = ceil($totalrecods / $recodsperpage); 
?>

	<div class='cp_box darkshaded cp_height30'>
		<form method="get" name='dropdown' action="index.php">
			<div class='cp_table230 top'>
			
				<input type="hidden" name="mnu" value="<?php echo $mnu; ?>" />
				<input type="hidden" name="com" value="<?php echo $com; ?>" />
				<input type="hidden" name="tpl" value="<?php echo $tpl; ?>" />
				<span class=' white'>Mostrar&nbsp;&nbsp;</span>
				<select name='recodsperpage' id='recodsperpage' width='20' onchange='dropdown.submit();'>
					<option value='5'<?php if ($recodsperpage == 5) {echo " selected='selected'";} ?>>5</option>
					<option value='10'<?php if ($recodsperpage == 10) {echo " selected='selected'";} ?>>10</option>
					<option value='25'<?php if ($recodsperpage == 25) {echo " selected='selected'";} ?>>25</option>
					<option value='50'<?php if ($recodsperpage == 50) {echo " selected='selected'";} ?>>50</option>
				</select>
				<span class='white'>&nbsp;de <?php echo $totalrecods; ?></span>
			</div>
			<div class='cp_table240 top'>
				<input type='text' name='search' id='search' size='20' maxlength='150' value='<?php echo $search; ?>'>
				<input type='submit' value='Buscar'>
			</div>

			<div class='cp_table220 top right'>
				<span class='white'>Galería: </span>
				<select name='filtergallery' id='filtergallery' onchange='dropdown.submit();'>
					<option value='0'<?php if($filtergallery == NULL){echo " selected='selected'";} ?>>Seleccione galería</option>
					<?php 
					$q2 = "SELECT * FROM ".preBD."images_gallery order by TITLE asc";
					$result2 = checkingQuery($connectBD, $q2);
					while($row2 = mysqli_fetch_array($result2)) { ?>
						<option value='<?php echo $row2["ID"]; ?>'<?php if ($filtergallery == $row2['ID']) {echo "selected='selected'";} ?>><?php echo substr($row2['TITLE'],0,20); ?></option>
					<?php } ?>
				</select>
			</div>
		</form>
	</div>
	
	<div class='cp_box cp_height30'>
	
		<div class='cp_table130 cp_title top'>
			<div class='cp_table top'>#</div>
			</br>
			<div class='cp_table bold'>Miniatura</div>
		</div>
	
		<div class='cp_table300 cp_title'>
			<div class='cp_table300 bold top'>Título</div>
			</br>
			<div class='cp_table300 bold top'>Autor</div>
		</div>
		
		<div class='cp_table130 cp_title'>
			<div class='cp_table bold top'>&nbsp;</div>
			</br>
			<div class='cp_table bold top'>Galer&iacute;a</div>
		</div>
		
		<div class='cp_table60 cp_title'>
			<div class='cp_table bold top'>&nbsp;</div>
			</br>
			<div class='cp_table bold top'>
			<?php 
			if($record == NULL) { 
				echo "Posición";
			}else{
				echo "&nbsp;";
			} ?>
			</div>
		</div>
		
		<div class='cp_table60 cp_title'>
			<div class='cp_table bold'>&nbsp;</div>
			</br>
			<div class='cp_table bold top'>Estado</div>
		</div>

		
	
	</div>
	<?php
	if($filtergallery == NULL) {
		$order = " ORDER BY ID desc";
	} else {
		$order = " ORDER BY POSITION asc";
	}
	$q = "SELECT * FROM ".preBD."images" . $searchq . $recordq . $galleryq . $order ." LIMIT " . $firstrecord . ", " . $recodsperpage;
	
	$result = checkingQuery($connectBD, $q);
	$total_img = mysqli_num_rows($result);
		while ($row = mysqli_fetch_assoc($result)) { ?>
			
			<div class='cp_box shaded cp_height60'>
				<div class='cp_table130' style='height:60px !important;overflow:hidden;'>
					<div class='cp_number bold center m1'><?php echo $row['ID']; ?></div>
			<?php 
				if ($row['URL'] == ""){
					$Thumbnail_url =	"images/emptythumbnail.gif";		
				} else {
					$url =	"../files/gallery/image/".$row['URL'];
					$Thumbnail_url = "../files/gallery/thumb/".$row['URL'];
				}
				
				if ($row['URL'] != ""){
					$data_title = "<strong>".$row["TITLE"]."</strong><br/>".$row["TEXT"]; 
			?>
				<a href='<?php echo $url; ?>' class='lytebox' data-lyte-options='slide:false group:<?php echo $row["IDGALLERY"]; ?>' data-title="<?php echo $data_title; ?>">
			<?php } ?>
					<img class='image' src='<?php echo $Thumbnail_url; ?>' height='60'/>
			<?php 
				if ($row['URL'] != ""){ 
			?>
				</a>
			<?php } ?>
				</div>
			
				<div class='cp_table300 top'>
					<div class='cp_table300 bold'>
						<a style='font-size:14px;' href='index.php?mnu=content&com=gallery&tpl=edit&record=<?php echo $row['ID']; ?>' title='Editar'>
						<?php echo substr($row['TITLE'],0,80);
							if (strlen($row['TITLE'])>80) {
								echo "...";
							} ?>
						</a>
					</div>
					<div style='height: 7px;'></div>
					<div class='cp_table300'><?php echo $row['AUTHOR']; ?></div>
				</div>
				<div class='cp_table130 top'>
					<?php 
					$title_album = getgallery($row['IDGALLERY']); ?>
					<div class='cp_table'>
						<?php echo  substr($title_album,0,35);
						if (strlen($title_album) > 35) {
							echo "...";
						} ?>
					</div>
					<div style='height: 7px;'>&nbsp;</div>
					<div class='cp_table'>&nbsp;</div>
				</div>
				<?php $position = $row["POSITION"]; ?>
				<div class='cp_table60 top'>
				<?php 
					if($filtergallery != NULL && $search == NULL) {			
						if($position != $total_img) {	?>
							<div class='cp_table25'>
								<a href='modules/gallery/change_positionDOWN.php?mnu=<?php echo $mnu; ?>&record=<?php echo $row["ID"]; ?>&filtergallery=<?php echo $row['IDGALLERY']; ?>'>
									<img src='images/down.png' alt='Bajar' title='Bajar' />
								</a>
							</div>
						<?php 
						} else { ?>
							<div class='cp_table25'>
								<img src='images/down_off.png' alt='Bajar' title='Bajar' />
							</div>
						<?php }
						if($position != 1) {	?>
							<div class='cp_table25'>
								<a href='modules/gallery/change_positionUP.php?mnu=<?php echo $mnu; ?>&record=<?php echo $row["ID"]; ?>&filtergallery=<?php echo $row['IDGALLERY']; ?>'>
									<img src='images/up.png' alt='Subir' title='Subir' />
								</a>
							</div>
						<?php 
						} else { ?>
							<div class='cp_table25'>
								<img src='images/up_off.png' alt='Subir' title='Subir' />
							</div>
						<?php }
					}else{
						echo "&nbsp;";	
					} 
				?>
				</div>
			
				<div class='cp_table25 top center'>
				<?php 
					if ($row['STATUS'] == 0) {
						if (allowed($mnu) == 1 || $_SESSION[PDCLOG]['Login'] == $row['AUTHOR']){ 
							$urlAlert = "modules/gallery/publish_image.php?mnu=".$mnu."&image=".$row["ID"];
							$msgAlert = "¡ATENCIÓN! Va a publicar la imagen ".stripslashes($row["TITLE"])." en la galería.";
						?>
							<div class='cp_table25'>
								<img class='image pointer' src='images/unchecked.png' alt='Publicar' title='Publicar' onclick="alertConfirm('<?php echo $msgAlert; ?>', '<?php echo $urlAlert; ?>');" />
							</div>
						<?php }else { ?>
							<div class='cp_table25'>
								<img class='image' src='images/unchecked_off.png' alt='Borrador' title='Borrador' />
							</div>
						<?php }
					}else {
						if (allowed($mnu) == 1 || $_SESSION[PDCLOG]['Login'] == $row['AUTHOR']){ 
							$urlAlert = "modules/gallery/unpublish_image.php?mnu=".$mnu."&image=".$row["ID"];
							$msgAlert = "¡ATENCIÓN! Va a pasar a borrador la imagen ".stripslashes($row["TITLE"]);
						?>
							<div class='cp_table25'>
								<img class='image pointer' src='images/checked.png' alt='Pasar a borrador' title='Pasar a borrador' onclick="alertConfirm('<?php echo $msgAlert; ?>', '<?php echo $urlAlert; ?>');" />
							</div>
						<?php }	else { ?>
							<div class='cp_table25'>
								<img class='image' src='images/checked_off.png' alt='Pasar a borrador' title='Pasar a borrador' />
							</div>
						<?php }
					} ?>
				</div>
			
				<div class='cp_table25 top center'>
					<?php if ((allowed($mnu) == 1 || $_SESSION[PDCLOG]['Login'] == $row['AUTHOR'])){ 
						$urlAlert = "modules/gallery/delete_image.php?mnu=".$mnu."&image=".$row["ID"];
						$msgAlert = "¡ATENCIÓN! Va a eliminar la imagen ".stripslashes($row["TITLE"]);
					?>
						<div class='cp_table25'>
							<img class='image pointer' src='images/delete.png' alt='Eliminar' title='Eliminar' onclick="alertConfirm('<?php echo $msgAlert; ?>', '<?php echo $urlAlert; ?>');" />
						</div>
					<?php }	else { ?>
						<div class='cp_table25'>
							<img class='image' src='images/delete_off.png' alt='' title='No tiene permisos para realizar esta acción.' />
						</div>
					<?php } ?>
				</div>
			
			</div>
		<?php }

	$previouspage = $page - 1;
	$nextpage = $page + 1;
	if ($totalpages > 1) { ?>
		<div class='cp_box dotted cp_height25'>
		<?php if ($page > 1) { ?>
			<div class='cp_table' style='margin-right:3px;'>
				<a href='index.php?mnu=content&com=gallery&tpl=option<?php if($filtergallery != NULL){?>&filtergallery=<?php echo $filtergallery; }?>&recodsperpage=<?php echo $recodsperpage; ?>&page=<?php echo $previouspage; ?>&search=<?php echo $search; ?>'>
					<<
				</a>
			</div>
		<?php }
		if ($page > 9) { ?>
			<div class='cp_table cp_pages center shaded'>
				<a href='index.php?mnu=content&&com=gallery&tpl=option<?php if($filtergallery != NULL){?>&filtergallery=<?php echo $filtergallery; }?>&recodsperpage=<?php echo $recodsperpage; ?>&page=1&search=<?php echo $search; ?>'>
					1
				</a>
			</div>
			<div class='cp_table' style='margin-left:3px;'>...</div>
		<?php }
		for ($i=1; $i < $totalpages + 1; $i++) {
			if ($i > ($page - 9) && $i < ($page + 9)) { ?>
				<div style='margin-right:3px;' class='cp_table cp_pages center<?php if ($page == $i) {echo " darkshaded";}else {echo "shaded";} ?>'>
					<a href='index.php?mnu=content&com=gallery&tpl=option<?php if($filtergallery != ""){?>&filtergallery=<?php echo $filtergallery; }?>&recodsperpage=<?php echo $recodsperpage; ?>&page=<?php echo $i; ?>&search=<?php echo $search; ?>'<?php if ($page == $i) {echo " style='color: white;'";} ?>>
						<?php echo $i ?>
					</a>
				</div>
			<?php }
		}
	}
	if ($page < ($totalpages - 9)) { ?>
			<div class='cp_table' style='margin-left:3px;'>...</div>
			<div class='cp_table cp_pages center shaded' style='margin-right:3px;'>
				<a href='index.php?mnu=content&com=gallery&tpl=option<?php if($filtergallery != ""){?>&filtergallery=<?php echo $filtergallery; }?>&recodsperpage=<?php echo $recodsperpage; ?>&page=<?php echo $totalpages; ?>&search=<?php echo $search; ?>'>
					<?php echo $totalpages; ?>
				</a>
			</div>
		<?php }
	if ($page < $totalpages) { ?>
		<div class='cp_table' style='margin-left:3px;'>
			<a href='index.php?mnu=content&com=gallery&tpl=option<?php if($filtergallery != ""){?>&filtergallery=<?php echo $filtergallery; }?>&recodsperpage=<?php echo $recodsperpage; ?>&page=<?php echo $nextpage; ?>&search=<?php echo $search; ?>'>
				>>
			</a>
		</div>
	<?php } ?>
	</div>