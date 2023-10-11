<?php
	if (!isset($_GET['recordsperpage'])) {
		$recordsperpage = 25;
	}else {
		$recordsperpage = $_GET['recordsperpage'];
	}
	if (!isset($_GET['page'])) {
		$page = 1;
		$firstrecord = 0;
	}else {
		$page = $_GET['page'];
		$firstrecord = ($page-1) * $recordsperpage;
	}
	
	if (!isset($_GET['search']) || $_GET['search'] == "") {
		$search = NULL;
		$searchq = "";
	} else {
		$search = $_GET['search'];
		$searchq .= " OR ".preBD."".preBD."user_sup_web_address.STREET LIKE '%".$search."%'";
	}
	
	if (!isset($_GET['id'])) {
		$record = NULL;
		$recordq = "";
	}else {
		$record = intval($_GET['id']);
		$recordq = " AND ".preBD."".preBD."user_sup_web_address.ID='".$record."'";
		$searchq = "";
	}
	require_once("includes/classes/Zone/class.Zone.php");
	require_once("includes/classes/Address/class.Address.php");
	require_once("includes/classes/Image/class.Image.php");
	$zoneObj = new Zone();
	$pointObj = new Address();
	$imgObj = new Image();
	
	$urlMod = "mnu=".$mnu."&com=".$com."&tpl=".$tpl."&opt=".$opt."&recordsperpage=".$recordsperpage."&search=".$search."&page=".$page;
	
	$q1 = "SELECT * FROM ".preBD."user_sup_web_address where TYPE='points'" . $searchq . $recordq;
	$result1 = checkingQuery($connectBD, $q1);
	$totalrecods = mysqli_num_rows($result1);
	$totalpages = ceil($totalrecods / $recordsperpage);
?>
	<div class='container container-admin darkshaded'>
		<div class='row'>
			<form name='dropdown' method='get' action='index.php'>
				<input type='hidden' name='mnu' value='<?php echo $mnu; ?>' />
				<input type='hidden' name='com' value='<?php echo $com; ?>' />
				<input type='hidden' name='tpl' value='<?php echo $tpl; ?>' />
				<input type='hidden' name='opt' value='<?php echo $opt; ?>' />
				
				<div class='col-sm-5 top header-list'>
					<span class=' white'>Mostrar&nbsp;&nbsp;</span>
					<select name='recordsperpage' id='recordsperpage' width='20' onchange='dropdown.submit();'>
						<option value='5'<?php if ($recordsperpage == 5) {echo " selected";} ?>>5</option>
						<option value='10'<?php if ($recordsperpage == 10) {echo " selected";} ?>>10</option>
						<option value='25'<?php if ($recordsperpage == 25) {echo " selected";} ?>>25</option>
						<option value='50'<?php if ($recordsperpage == 50) {echo " selected";} ?>>50</option>
					</select>
					<span class='white'>&nbsp;de <?php echo $totalrecods; ?></span>
				</div>
				<div class='col-sm-6 top header-list'>
					<input type='text' name='search' id='search' size='20' maxlength='150' value='<?php echo $search; ?>'>
					<input type='submit' value='Buscar'>
				</div>
			</form>
		</div>
	</div>
	<div class="separator30">&nbsp;</div>
	<div class='container container-admin'>
		<div class='row'>
			<div class='col-sm-1 cp_title top'>
				<div class='bold textLeft'>#</div>
			</div>
			<div class='col-sm-2 cp_title top'>
				<div class='bold textLeft'>Imagen</div>
			</div>
			<div class='col-sm-4 cp_title top'>
				<div class='bold textLeft'>Nombre/dirección</div>
			</div>
			<div class='col-sm-3 cp_title top'>
				<div class='bold textLeft'>Zona de reparto</div>
			</div>
			<div class='col-sm-2 cp_title top textCenter'>
				<div class='bold textCenter'>Estado</div>
			</div>
		</div>
	</div>
	<div class="separator30">&nbsp;</div>
	<div class='container container-admin'>
		<div class='row'>
<?php	
			$q = "SELECT ".preBD."user_sup_web_address.* 
					FROM ".preBD."user_sup_web_address 
					where TYPE='points'
					 " . $searchq . $recordq . " ORDER BY STREET desc LIMIT " . $firstrecord . ", " . $recordsperpage;
			$result = checkingQuery($connectBD, $q);
			
			while ($row = mysqli_fetch_object($result)):
				$zone = $zoneObj->infoZone($row->IDZONE);
				$imgLogo = new Image();
				$imgLogo->path = "points";
				$imgLogo->pathoriginal = "original";
				$imgLogo->paththumb = "icon";
				$urlImg = $imgLogo->dirView.$imgLogo->path."/".$imgLogo->paththumb."/".$row->IMAGE;
				if($row->IMAGE == "") {
					$urlImg = $imgLogo->dirView.$imgLogo->path."/".$imgLogo->paththumb."/default.png";
				}
?>		
				<div class='col-md-12 shaded item-list'>
					<div class='col-sm-1'>
						<div class='cp_number bold center m1' style='font-size:14px;'><?php echo $row->ID; ?></div>
					</div>
					<div class='col-sm-2'>
						<a class="transition" style='font-size:14px;' href='index.php?mnu=<?php echo $mnu; ?>&com=<?php echo $com; ?>&tpl=edit&opt=<?php echo $opt; ?>&id=<?php echo $row->ID; ?>'>
							<img src='<?php echo $urlImg; ?>' style='border:none;max-height:80px;'/>
						</a>
					</div>
					<div class='col-sm-4'>
						<div class='bold'>
							<a class="transition" style='font-size:14px;' href='index.php?mnu=<?php echo $mnu; ?>&com=<?php echo $com; ?>&tpl=edit&opt=<?php echo $opt; ?>&id=<?php echo $row->ID; ?>'>
								<?php echo $row->STREET; ?>
							</a>
						</div>
					</div>
					<div class='col-sm-3'>
						<?php echo $zone->CITY." (".$zone->CP.")"; ?>
					</div>
					
					<div class='col-sm-2 textCenter'>
						<?php 
							if($row->ACTIVE == 1) {
								$urlAlert = "modules/points/action.php?".$urlMod."&action=unpublish&id=".$row->ID;
								$msgAlert = "¡ATENCI&Oacute;N! Va a desactivar el punto de recogida ".$row->STREET.". &iquest;Est&aacute; seguro?";
							}else if($row->ACTIVE == 0) {
								$urlAlert = "modules/points/action.php?".$urlMod."&action=publish&id=".$row->ID;
								$msgAlert = "¡ATENCI&Oacute;N! Va a activar el punto de recogida ".$row->STREET.". &iquest;Est&aacute; seguro?";
							}
						?>
						<div class='col-xs-12'>
							<i class="fa fa-<?php if($row->FAV == 1){echo 'check-circle green';}else if($row->FAV == 0){echo 'check-circle orange';}else{echo 'minus-circle grayStrong';} ?> pointer iconBotton transition"></i>
						</div>
						<?php 
							/*/No se puede borrar por asociaciones en productos
							$urlAlert = "modules/supplier/action.php?".$urlMod."&action=delete&id=".$row->ID;
							$msgAlert = "¡ATENCI&Oacute;N! Va a eliminar al usuario ".$Name.". &iquest;Est&aacute; seguro?";
							<div class='col-xs-6'>
								<i class="fa fa-trash grayStrong pointer iconBotton transition" title='Eliminar' onclick='alertConfirm("<?php echo $msgAlert; ?>", "<?php echo $urlAlert; ?>");'></i>
							</div>
						*/	?>
					</div>
				</div>
				<div class="separator">&nbsp;</div>
	<?php 	endwhile; ?>
		</div>
	</div>
<?php
	$previouspage = $page - 1;
	$nextpage = $page + 1;
	$url_pag = "index.php?mnu=".$mnu."&com=".$com."&tpl=".$tpl."&opt=".$opt."&recordsperpage=".$recordsperpage."&search=".$search;
	if ($totalpages > 1) {
?>
		<div class='cp_box dotted cp_height45'>
		<?php if ($page > 1) { ?>
			<div class='cp_table' style='margin-right:3px;'>
				<a href='<?php echo $url_pag."&page=".$previouspage; ?>'>
					<<
				</a>
			</div>
		<?php }
		if ($page > 9) {
	?>
			<div class='cp_table cp_pages center shaded'>
				<a href='<?php echo $url_pag."&page=1"; ?>'>1</a>
			</div>
			<div class='cp_table' style='margin-left:3px;'>...</div>
		<?php }
		for ($i=1; $i < $totalpages + 1; $i++) {
			if ($i > ($page - 9) && $i < ($page + 9)) {
		?>
				<div style='margin-right:3px;' class='cp_table cp_pages center<?php if ($page == $i) {echo" darkshaded";}	else {echo" shaded";}?>'>
					<a href='<?php echo $url_pag."&page=".$i; ?>'<?php if ($page == $i) {echo" style='color: white;'";}?>>	
						<?php echo $i; ?>
					</a>
				</div>
	<?php 	}
		}
	}
	if ($page < ($totalpages - 9)) {
	?>
			<div class='cp_table' style='margin-left:3px;'>...</div>
				<div class='cp_table cp_pages center shaded' style='margin-right:3px;'>
					<a href='<?php echo $url_pag."&page=".$totalpages; ?>'>
						<?php echo $totalpages; ?>
					</a>
				</div>
	<?php 
		}
	if ($page < $totalpages) {
	?>
		<div class='cp_table' style='margin-left:3px;'>
			<a href='<?php echo $url_pag."&page=".$nextpage; ?>'>
				>>
			</a>
		</div>
<?php } ?>
	</div>
