<?php
	require_once ("includes/database.php");
	
	if (!isset($_GET['recodsperpage'])) {
		$recodsperpage = 5;
	}else {
		$recodsperpage = $_GET['recodsperpage'];
	}
	
	if (!isset($_GET['page'])) {
		$page = 1;
		$firstrecord = 0;
	}else {
		$page = $_GET['page'];
		$firstrecord = ($page-1) * $recodsperpage;
	}
	
	if (!isset($_GET['record'])) {
		$record = NULL;
		$recordq = '';
	}else {
		$record = $_GET['record'];
		$recordq = " WHERE ID='".$record."'";
		$searchq = '';
	}
	
	if (!isset($_GET['filteralbum']) || $_GET['filteralbum'] == 0) {
		$filteralbum = NULL;
		$albumq = '';
	}else {
		$filteralbum = $_GET['filteralbum'];
		if($record == NULL) {
			$albumq = " WHERE IDALBUM='".$filteralbum."'";
		}else {
			$albumq = " AND IDALBUM='".$filteralbum."'";
		}
	}

	if($filteralbum != ""){
		$order = " order by POSITION asc";
	}else{
		$order = " order by ID desc";
	}
	
	if($filteralbum != ""){
		$order = " order by POSITION asc";
	}else{
		$order = " order by ID desc";
	}
	
	$com_url = "mnu=".$mnu."&com=".$com."&tpl=".$tpl;
	
	
	$q1 = "SELECT * FROM ".preBD."slider".$recordq.$albumq;
	$result1 = checkingQuery($connectBD, $q1);
	$totalrecods = mysqli_num_rows($result1);
	$totalpages = ceil($totalrecods / $recodsperpage); ?>
	
	<div class='cp_box darkshaded cp_height30'>
		<form method="get" action="index.php" name='dropdown'>
			<input type="hidden" name="mnu" value="<?php echo $mnu; ?>" />
			<input type="hidden" name="com" value="<?php echo $com; ?>" />
			<input type="hidden" name="tpl" value="<?php echo $tpl; ?>" />
			<div class='cp_table230'>
				<span class='white'>Mostrar&nbsp;&nbsp;</span>
				<select name='recodsperpage' id='recodsperpage' width='20' onchange='dropdown.submit();'>
					<option value='5'<?php if ($recodsperpage == 5) {echo " selected='selected'";} ?>>5</option>
					<option value='10'<?php if ($recodsperpage == 10) {echo " selected='selected'";} ?>>10</option>
					<option value='25'<?php if ($recodsperpage == 25) {echo " selected='selected'";} ?>>25</option>
					<option value='50'<?php if ($recodsperpage == 50) {echo " selected='selected'";} ?>>50</option>
				</select>
				<span class='white'>&nbsp;de <?php echo $totalrecods; ?></span>
			</div>
			<div class='cp_table220 top' style='float:right;'>
				<span class='white'>Banner: </span>
				<select name='filteralbum' id='filteralbum' onchange='dropdown.submit();'>
					<option value='0'<?php if($filteralbum == NULL){echo " selected='selected'";} ?>>Seleccione banner</option>
					<?php 
					$q2 = "SELECT * FROM ".preBD."slider_gallery order by TITLE asc";
					$result2 = checkingQuery($connectBD, $q2);
					while($row2 = mysqli_fetch_object($result2)) { ?>
						<option value='<?php echo $row2->ID; ?>'<?php if($filteralbum == $row2->ID){echo " selected='selected'";} ?>><?php echo substr(stripslashes($row2->TITLE),0,20); ?></option>
					<?php } ?>
				</select>
			</div>
		</form>
	</div>
	
	<div class='cp_box cp_height30'>
		<div class='cp_table140 cp_title top'>
			<div class='cp_table top'>#</div>
			</br>
			<div class='cp_table bold'>Miniatura</div>
		</div>
		<div class='cp_table250 cp_title'>
			<div class='cp_table250 bold top'>Título</div>
			<br/>
			<div class='cp_table250 bold top'>Banner</div>
		</div>
	
		<div class='cp_table150 cp_title'>
			<div class='cp_table150 bold top'>Fecha inicial</div>
			<br/>
			<div class='cp_table150 bold top'>Fecha final</div>
		</div>
	
		<div class='cp_table50 cp_title'>
			<div class='cp_table bold top'>&nbsp;</div>
			</br>
			<div class='cp_table bold top'>&nbsp;</div>
		</div>
	
		<div class='cp_table75 cp_title'>
			<div class='cp_table bold top'>&nbsp;</div>
			<br/>
			<div class='cp_table bold top' style='margin-left: 20px;'>Estado</div>
		</div>
	</div>
	<?php 
	if($filteralbum != NULL) {
		$q = "SELECT count(*) as total FROM ".preBD."slider where IDALBUM = " . $filteralbum;
		$result = checkingQuery($connectBD, $q);
		$row_total = mysqli_fetch_assoc($result);
		$totalbanners = $row_total["total"];
	}

	$q = "SELECT * FROM ".preBD."slider".$recordq.$albumq. $order. " LIMIT ".$firstrecord.", ".$recodsperpage;	
	//pre($q);
	$result = checkingQuery($connectBD, $q);
	
	while ($row = mysqli_fetch_object($result)) {
		$Date_start = $row->DATE_START;
		$Date_end = $row->DATE_END;
		$Thumbnail_url = $row->IMAGE;
		$Title = strip_tags(stripslashes($row->TITLE));
		$id = $row->ID;
		$Position = $row->POSITION;
		$Link = stripslashes($row->LINK);
		$Album = $row->IDALBUM; 
	?>
		<div class='cp_box shaded cp_height75'>
			<div class='cp_number bold center m1'><?php echo $id."-".$Position; ?></div>
			<div class='cp_table140'>
				<a href='index.php?mnu=design&com=slide&tpl=edit&record=<?php echo $id; ?>'>
					<img class='border' src='../files/slide/image/<?php echo $Thumbnail_url; ?>' alt='<?php echo $Title; ?>' title='<?php echo $Title; ?>' style='max-width:120px;max-height:60px' />
				</a>
			</div>
			<div class='cp_table250 top'>
				<div class='cp_table250 bold'>
					<a href='index.php?mnu=design&com=slide&tpl=edit&record=<?php echo $id; ?>' title='<?php echo $Title; ?>'>
						<?php echo substr($Title,0,35);
							if (strlen($Title)>35) {
								echo "...";
							} 
						?>
					</a>
				</div>
				<div class='cp_table_s bold'>
				<?php 
					$qA = "select TITLE from ".preBD."slider_gallery where ID = " . $Album;
					$resultA = checkingQuery($connectBD, $qA);
					$Talbum  = mysqli_fetch_object($resultA);
					echo stripslashes($Talbum->TITLE); ?>
				</div>
			</div>

			<div class='cp_table150 top'>
				<div class='cp_table150 bold'>
					<?php echo format_date($Date_start); ?>
				</div>
				<br/>
				<div class='cp_table_s bold'>
					<?php 
						if ($Date_end == "0000-00-00 00:00:00"){ 
							echo "Publicación indefinida";
						} else {
							echo format_date($Date_end);
						} 
					?>
				</div>
			</div>
<!-- POSITION -->
			<div class='cp_table70 top'>
				<div class='cp_table25'>
			<?php if($filteralbum != NULL){ ?>
					<?php if ($Position == 1): ?>
						<img class='image' src='images/first_off.png' alt='' title='' style="margin-bottom:3px;"/>
						<img class='image' src='images/up_off.png' alt='' title='' style="margin-bottom:16px;"/>
					<?php else: ?>
						<a href='<?php echo DOMAIN; ?>pdc-reparteat/modules/slide/first_position_banner.php?recodsperpage=<?php echo $recodsperpage; ?>&page=<?php echo $page; ?>&record=<?php echo $id; ?>'>
							<img class='image' src='images/first.png' alt='Subir a primera posición' title='Subir a primera posición'  style="margin-bottom:3px;"/>
						</a>					
						<a href='<?php echo DOMAIN; ?>pdc-reparteat/modules/slide/moveup_banner.php?recodsperpage=<?php echo $recodsperpage; ?>&page=<?php echo $page; ?>&record=<?php echo $id; ?>'>
							<img class='image' src='images/up.png' alt='Subir' title='Subir' />
						</a>&nbsp;&nbsp;
					<?php endif; ?>
					<?php if ($Position == $totalbanners): ?>
						<img class='image' src='images/down_off.png' alt='' title=''  style="margin-bottom:3px;"/>
						<img class='image' src='images/last_off.png' alt='' title='' />
					<?php else: ?>
						<a href='<?php echo DOMAIN; ?>pdc-reparteat/modules/slide/movedown_banner.php?recodsperpage=<?php echo $recodsperpage; ?>&page=<?php echo $page; ?>&record=<?php echo $id; ?>'>
							<img class='image' src='images/down.png' alt='Bajar' title='Bajar' style="margin-bottom:3px;"/>
						</a>
						<a href='<?php echo DOMAIN; ?>pdc-reparteat/modules/slide/last_position_banner.php?recodsperpage=<?php echo $recodsperpage; ?>&page=<?php echo $page; ?>&record=<?php echo $id; ?>'>
							<img class='image' src='images/last.png' alt='Bajar a última posición' title='Bajar a última posición' />
						</a>						
					<?php endif; ?>
			<?php } ?>
					&nbsp;
				</div>
			</div>

	<!-- PUBLISH -->
			<div class='cp_table80'>
				<div class='cp_table30'>
					<?php if ($row->STATUS == 0) { ?>
						<?php if (allowed($mnu) == 1): 
							$msgAlert = "¡ATENCI&Oacute;N! Va a publicar la imagen ". stripslashes($album->TITLE)." &iquest;Desea continuar?";
							$urlAlert = "modules/slide/publish_banner.php?&record=".$id."&recordperpage=".$recodsperpage."&page=".$page;
							if($filteralbum != 0 && $filteralbum != NULL) {
								$urlAlert .= "&filteralbum=".$filteralbum;
							}
						?>
							<img class='image pointer' src='images/unchecked.png' alt='Publicar imagen' title='Publicar imagen' onclick="alertConfirm('<?php echo $msgAlert; ?>', '<?php echo $urlAlert; ?>');" />
						<?php else: ?>
							<img class='image' src='images/unchecked_off.png' alt='Borrador' title='Borrador' />
						<?php endif; ?>
					<?php }	else { ?>
						<?php if (allowed($mnu) == 1): 
							$msgAlert = "¡ATENCI&Oacute;N! Va a pasar a borrador la imagen ". stripslashes($album->TITLE)." &iquest;Desea continuar?";
							$urlAlert = "modules/slide/unpublish_banner.php?&record=".$id."&recordperpage=".$recodsperpage."&page=".$page;
							if($filteralbum != 0 && $filteralbum != NULL) {
								$urlAlert .= "&filteralbum=".$filteralbum;
							}
						?>
							<img class='image pointer' src='images/checked.png' alt='Pasar a borrador' title='Pasar a borrador' onclick="alertConfirm('<?php echo $msgAlert; ?>', '<?php echo $urlAlert; ?>');" />
						<?php else: ?>
						<img class='image' src='images/checked_off.png' alt='Publicado' title='Publicado' />
						<?php endif; ?>
					<?php } ?>
				</div>
	<!-- DELETE -->
				<div class='cp_table30' style='text-align:right;'>
					<?php if (allowed($mnu) == 1){ 
							$msgAlert = "¡ATENCI&Oacute;N! Va a eliminar la imagen ". stripslashes($album->TITLE)." &iquest;Desea continuar?";
							$urlAlert = "modules/slide/delete_banner.php?&record=".$id."&recordperpage=".$recodsperpage."&page=".$page;
							if($filteralbum != 0 && $filteralbum != NULL) {
								$urlAlert .= "&filteralbum=".$filteralbum;
							} ?>
						<img class='image pointer' src='images/delete.png' alt='Eliminar imagen' title='Eliminar imagen' onclick="alertConfirm('<?php echo $msgAlert; ?>', '<?php echo $urlAlert; ?>');" />
					<?php }else { ?>
						<img class='image' src='images/delete_off.png' alt='' title='' />
					<?php } ?>
				</div>
			
			</div>
		</div>
	<?php }


// PAGINACIÓN
	$previouspage = $page - 1;
	$nextpage = $page + 1;
	if ($totalpages > 1) { ?>
		<div class='cp_box cp_height25'>
		<?php 
		if ($page > 1) { ?>
			<div class='cp_table' style='margin-right:3px;'>
				<a href='index.php?<?php echo $com_url; ?>&filteralbum=<?php echo $filteralbum; ?>&recodsperpage=<?php echo $recodsperpage; ?>&page=<?php echo $previouspage; ?>&search=<?php echo $search; ?>'>
					&lt;&lt;
				</a>
			</div>
		<?php }
		if ($page > 9) { ?>
			<div class='cp_table cp_pages center shaded'>
				<a href='index.php?<?php echo $com_url; ?>&filteralbum=<?php echo $filteralbum; ?>&recodsperpage=<?php echo $recodsperpage; ?>&page=1&search=<?php echo $search; ?>'>
					1
				</a>
			</div>
			<div class='cp_table' style='margin-left:3px;'>...</div>
		<?php }
		for ($i=1; $i < $totalpages + 1; $i++) {
			if ($i > ($page - 9) && $i < ($page + 9)) { ?>
				<div style='margin-right:3px;' class='cp_table cp_pages center<?php if ($page == $i){ echo " darkshaded";}else {echo " shaded";} ?>'>
		<?php } ?>
					<a href='index.php?<?php echo $com_url; ?>&filteralbum=<?php echo $filteralbum; ?>&recodsperpage=<?php echo $recodsperpage; ?>&page=<?php echo $i; ?>&search=<?php echo $search; ?>'<?php if ($page == $i) {echo " style='color: white;'";} ?>>
						<?php echo $i; ?>
					</a>
				</div>
			
<?php 	}
	}
	if ($page < ($totalpages - 9)) { ?>
			<div class='cp_table' style='margin-left:3px;'>...</div>
			<div class='cp_table cp_pages center shaded' style='margin-right:3px;'>
				<a href='index.php?<?php echo $com_url; ?>&filteralbum=<?php echo $filteralbum; ?>&recodsperpage=<?php echo $recodsperpage; ?>&page=<?php echo $totalpages; ?>&search=<?php echo $search; ?>'>
					<?php echo $totalpages; ?>
				</a>
			</div>
		<?php }
	if ($page < $totalpages) { ?>
		<div class='cp_table' style='margin-left:3px;'>
			<a href='index.php?<?php echo $com_url; ?>&filteralbum=<?php echo $filteralbum; ?>&recodsperpage=<?php echo $recodsperpage; ?>&page=<?php echo $nextpage; ?>&search=<?php echo $search; ?>'>
				&gt;&gt;
			</a>
		</div>
	<?php } ?>
	</div>