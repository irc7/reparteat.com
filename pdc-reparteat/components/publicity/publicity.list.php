<?php
	if (!isset($_GET['recordsperpage'])) {
		$recordsperpage = 25;
	}
	else {
		$recordsperpage = $_GET['recordsperpage'];
	}
	if (!isset($_GET['page'])) {
		$page = 1;
		$firstrecord = 0;
	}
	else {
		$page = $_GET['page'];
		$firstrecord = ($page-1) * $recordsperpage;
	}
	
	if (!isset($_GET['filterHook']) || intval($_GET['filterHook']) == 0) {
		$filterHook = 0;
	//	$filterHook = "";
	}else {
		$filterHook = intval($_GET['filterHook']);
	//	$filterHookq .= " and HOOK = '".$filterHook."'";
	}
	if (!isset($_GET['filterZone']) || intval($_GET['filterZone']) == 0) {
		$filterZone = 0;
	//	$filterZoneq = "";
	}else {
		$filterZone = intval($_GET['filterZone']);
	//	$filterZoneq .= " and IDSPECIALITY = '".$filterSp."'";
	}

	
	$urlCom = "index.php?mnu=".$mnu."&com=".$com."&tpl=".$tpl."&recordsperpage=".$recordsperpage."&filterHook=".$filterHook."&filterZone=".$filterZone."&page=".$page;;
	$urlMod = "mnu=".$mnu."&com=".$com."&tpl=".$tpl."&recordsperpage=".$recordsperpage."&filterHook=".$filterHook."&filterZone=".$filterZone."&page=".$page;;
	
	require_once("includes/classes/Zone/class.Zone.php");
	require_once("includes/classes/Image/class.Image.php");
	require_once("includes/classes/Publicity/class.Publicity.php");
	require_once("includes/classes/Publicity/class.PublicityHook.php");
	
	$zoneObj = new Image();
	$zoneObj = new Zone();
	$hookObj = new PublicityHook();
	$publicityObj = new Publicity();
	
	$hooks = array(); 
	$hooks = $hookObj->listHook(); 
	
	$zones = array(); 
	$zones = $zoneObj->allZones(); 

	$list = $publicityObj->listFilter($filterHook, $filterZone);

	$totalrecords = count($list);
	
	$totalpages = ceil($totalrecords / $recordsperpage);
	
	
?>
<div class='container container-admin darkshaded'>
	<div class='row'>
		<form name='dropdown' method='get' action='index.php'>
			<input type='hidden' name='mnu' value='<?php echo $mnu; ?>' />
			<input type='hidden' name='com' value='<?php echo $com; ?>' />
			<input type='hidden' name='tpl' value='<?php echo $tpl; ?>' />
			<input type='hidden' name='opt' value='<?php echo $opt; ?>' />
		<?php /*	
			<div class='col-sm-5 top header-list'>
				<label class="label-field white">Mostrar&nbsp;&nbsp;</label>
				<select class="form-control form-s" name='recordsperpage' id='recordsperpage' width='20' onchange='dropdown.submit();'>
					<option value='5'<?php if ($recordsperpage == 5) {echo " selected";} ?>>5</option>
					<option value='10'<?php if ($recordsperpage == 10) {echo " selected";} ?>>10</option>
					<option value='25'<?php if ($recordsperpage == 25) {echo " selected";} ?>>25</option>
					<option value='50'<?php if ($recordsperpage == 50) {echo " selected";} ?>>50</option>
				</select>
				<span class="label-field white">&nbsp;de <?php echo $totalrecords; ?></span>
			</div>
		*/ ?>
			<div class='col-sm-6 col-xs-12'>
				<label class="label-field white" style="width:100%;">Zonas de visualización</label>
			</div>
			<div class='col-sm-6 col-xs-12'>
				<label class="label-field white" style="width:100%;">Zonas de reparto</label>
			</div>
			<div class="separator1">&nbsp;</div>
			<div class='col-sm-6 col-xs-12'>
				<select name='filterHook' id='filterHook' class="form-control form-l" onchange='dropdown.submit();'>
					<option value="0"<?php if($filterHook == 0){echo " selected";} ?>>Todos las zonas de visualización</option>
					<?php foreach($hooks as $h){ ?>
						<option value="<?php echo $h->ID; ?>"<?php if($filterHook == $h->ID){echo " selected";} ?>><?php echo $h->TITLE; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class='col-sm-6 col-xs-12'>
				<select name='filterZone' id='filterZone' class="form-control form-l" onchange='dropdown.submit();'>
					<option value="0"<?php if($filterZone == 0){echo " selected";} ?>>Todos las zonas de reparto</option>
					<?php foreach($zones as $z){ ?>
						<option value="<?php echo $z->ID; ?>"<?php if($filterZone == $z->ID){echo " selected";} ?>><?php echo $z->CITY ." - ".$z->CP; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="separator15">&nbsp;</div>
		</form>
	</div>
</div>
<div class="separator30">&nbsp;</div>
	<div class='container container-admin'>
		<div class='row'>
			<div class='col-sm-4 cp_title top'>
				<div class='bold textLeft'>#</div>
			</div>
			<div class='col-sm-4 cp_title top'>
				<div class='bold textLeft'>Título</div>
			</div>
			<div class='col-sm-2 cp_title top'>
				<?php if($filterHook > 0 && $filterZone > 0) { ?>
					<div class='bold textCenter'>Posicion</div>
				<?php }else{ ?>
					<div class='bold textLeft'>&nbsp;</div>
				<?php } ?>
			</div>
			<div class='col-sm-2 cp_title top'>
				<div class='bold textLeft'>Estado</div>
			</div>
		</div>
	</div>
	<div class="separator30">&nbsp;</div>
	<div class='container container-admin'>
		<div class='row'>
<?php	
			foreach($list as $row){
				$imgLogo = new Image();
				$imgLogo->path = "publicity";
				$imgLogo->pathoriginal = "original";
				$imgLogo->paththumb = "image";
				$urlImg = $imgLogo->dirView.$imgLogo->path."/".$imgLogo->paththumb."/1-".$row->IMAGE;
?>		
				<div class='col-md-12 shaded item-list'>
					<div class='col-sm-4'>
						<div class='cp_number bold center m1' style='font-size:14px;'><?php echo $row->ID; ?></div>
							<a style='font-size:14px;' href='index.php?<?php echo $urlMod; ?>&id=<?php echo $row->ID; ?>'>
							<img src='<?php echo $urlImg; ?>' style='border:none;max-height:50px;'/>
						</a>
					</div>
				
					<div class='col-sm-4'>
						<div class='bold'>
							<a class="transition" style='font-size:14px;' href='index.php?mnu=<?php echo $mnu; ?>&com=<?php echo $com; ?>&tpl=edit&opt=<?php echo $opt; ?>&id=<?php echo $row->ID; ?>'>
								<?php echo $row->TITLE; ?>
							</a>
						</div>
					</div>
					<div class='col-sm-2 textCenter'>
						<?php if($filterHook > 0 && $filterZone > 0) { 
								$position = $publicityObj->infoPositionById($filterHook,$filterZone, $row->ID);
						?>
							<div class='bold'><?php echo $position; ?></div>
						<?php } ?>
					</div>
					<div class='col-sm-2 textCenter'>
						<div class='col-xs-6'>
					<?php 
						if($row->STATUS == 0) {
							$urlAlert = "modules/publicity/action.php?".$urlMod."&action=publish&id=".$row->ID;
							$msgAlert = "¡ATENCI&Oacute;N! Va a publicar el baner de publicidad ".$row->TITLE.". &iquest;Est&aacute; seguro?";
					?>
							<i class="fa fa-eye-slash orange pointer iconBotton transition" title='Activar' onclick='alertConfirm("<?php echo $msgAlert; ?>", "<?php echo $urlAlert; ?>");'></i>
							
				<?php 	}else { 
							$urlAlert = "modules/publicity/action.php?".$urlMod."&action=unpublish&id=".$row->ID;
							$msgAlert = "¡ATENCI&Oacute;N! Va a despublicar el baner de publicidad ".$row->TITLE.". &iquest;Est&aacute; seguro?";
				?>
							<i class="fa fa-eye green pointer iconBotton transition" title='Desactivar' onclick='alertConfirm("<?php echo $msgAlert; ?>", "<?php echo $urlAlert; ?>");'></i>
							
					<?php } ?>
						</div>
						<div class='col-xs-6'>
							<?php
								$urlAlert = "modules/publicity/action.php?".$urlMod."&action=delete&id=".$row->ID;
								$msgAlert = "¡ATENCI&Oacute;N! Va a eliminar el baner de publicidad ".$row->TITLE.". &iquest;Est&aacute; seguro?";
							?>
							<i class="fa fa-trash grayStrong pointer iconBotton transition" title='Eliminar' onclick='alertConfirm("<?php echo $msgAlert; ?>", "<?php echo $urlAlert; ?>");'></i>
						</div>
					</div>
				</div>
				<div class="separator">&nbsp;</div>
	<?php 	} ?>
		</div>
	</div>
	
<?php 
	$previouspage = $page - 1;
	$nextpage = $page + 1;
	
	$urlPag .= $urlCom . "&recordsperpage=".$recordsperpage;
	if($search != "") {
		$urlPag .= "&search=".$search;
	}
	if ($totalpages > 1):
	?>
		<div class='cp_box dotted cp_height25'>
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
