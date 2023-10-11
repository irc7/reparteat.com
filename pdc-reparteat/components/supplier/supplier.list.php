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
		$searchq = " AND ".preBD."supplier.ESLOGAN LIKE '%".$search."%'";
		$searchq .= " OR ".preBD."supplier.TITLE LIKE '%".$search."%'";
	}
	
	if (!isset($_GET['id'])) {
		$record = NULL;
		$recordq = "";
	}else {
		$record = intval($_GET['id']);
		$recordq = " AND ".preBD."supplier.ID='".$record."'";
		$searchq = "";
	}
	require_once("includes/classes/Image/class.Image.php");
	require_once("includes/classes/Supplier/class.Supplier.php");
	
	$supObj = new Supplier();
	
	$urlMod = "mnu=".$mnu."&com=".$com."&tpl=".$tpl."&opt=".$opt."&recordsperpage=".$recordsperpage."&search=".$search."&page=".$page;
	
	$q1 = "SELECT * FROM ".preBD."suppliers where true " . $searchq . $recordq;
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
			<div class='col-sm-2 cp_title top'>
				<div class='bold textLeft'>&nbsp;</div>
				<div class='bold textLeft'>&nbsp;</div>
				<div class='bold textLeft'>#</div>
			</div>
			<div class='col-sm-4 cp_title top'>
				<div class='bold textLeft'>Nombre</div>
				<div class='bold textLeft'>Proveedor</div>
				<div class='bold textLeft'>Repartidor</div>
			</div>
			<div class='col-sm-2 cp_title top'>
				<div class='bold textLeft'>&nbsp;</div>
				<div class='bold textLeft'>Teléfono</div>
				<div class='bold textLeft'>Móvil</div>
			</div>
			<div class='col-sm-2 cp_title top'>
				<div class='bold textLeft'>&nbsp;</div>
				<div class='bold textLeft'>&nbsp;</div>
				<div class='bold textCenter'>Miniatura productos</div>
			</div>
			<div class='col-sm-2 cp_title top'>
				<div class='bold textLeft'>&nbsp;</div>
				<div class='bold textLeft'>&nbsp;</div>
				<div class='bold textLeft'>Estado</div>
			</div>
		</div>
	</div>
	<div class="separator30">&nbsp;</div>
	<div class='container container-admin'>
		<div class='row'>
<?php	
			$q = "SELECT ".preBD."suppliers.* FROM 
					".preBD."suppliers 
					where true " . $searchq . $recordq . " ORDER BY TITLE desc LIMIT " . $firstrecord . ", " . $recordsperpage;
			$result = checkingQuery($connectBD, $q);
			
			while ($row = mysqli_fetch_object($result)):
				$imgLogo = new Image();
				$imgLogo->path = "supplier";
				$imgLogo->pathoriginal = "original";
				$imgLogo->paththumb = "thumb";
				$urlImg = $imgLogo->dirView.$imgLogo->path."/".$imgLogo->paththumb."/1-".$row->LOGO;
?>		
				<div class='col-md-12 shaded item-list'>
					<div class='col-sm-2'>
						<div class='cp_number bold center m1' style='font-size:14px;'><?php echo $row->ID; ?></div>
							<a style='font-size:14px;' href='index.php?<?php echo $urlMod; ?>&id=<?php echo $row->ID; ?>'>
							<img src='<?php echo $urlImg; ?>' style='border:none;width:110px;'/>
						</a>
					</div>
				
					<div class='col-sm-4'>
						<div class='bold'>
							<a class="transition" style='font-size:14px;' href='index.php?mnu=<?php echo $mnu; ?>&com=<?php echo $com; ?>&tpl=edit&opt=<?php echo $opt; ?>&id=<?php echo $row->ID; ?>'>
								<?php echo $row->TITLE; ?>
							</a>
						</div>
						<div class='grayNormal'>
							<?php 
								$pro = $supObj->totalInfoSupplierUser($row->ID, 'proveedor');
								echo $pro->NAME . " " . $pro->SURNAME; 
							?>
						</div>
						<div class='grayNormal'>
							<?php 
								$pro = $supObj->totalInfoSupplierUser($row->ID, 'repartidor');
								echo $pro->NAME . " " . $pro->SURNAME; 
							?>
						</div>
					</div>
				
					<div class='col-sm-2'>
						<div class='grayNormal'>
							<?php echo $row->PHONE; ?>
						</div>
						<div class='grayNormal'>
							<?php echo $row->MOVIL; ?>
						</div>
					</div>
					<div class='col-sm-2 textCenter'>
					<?php 
						if($row->VIEW_IMG == 0) {
							$urlAlert = "modules/supplier/action.php?".$urlMod."&action=viewimg&id=".$row->ID;
							$msgAlert = "¡ATENCI&Oacute;N! Va a activar las imágenes en el listado de ".$Name.". &iquest;Est&aacute; seguro?";
					?>
							<i class="fa fa-eye-slash grayLight pointer iconBotton transition" title='Activar' onclick='alertConfirm("<?php echo $msgAlert; ?>", "<?php echo $urlAlert; ?>");'></i>
							
				<?php 	}else { 
							$urlAlert = "modules/supplier/action.php?".$urlMod."&action=noviewimg&id=".$row->ID;
							$msgAlert = "¡ATENCI&Oacute;N! Va a desactivar las imágenes en el listado de ".$Name.". &iquest;Est&aacute; seguro?";
				?>
							<i class="fa fa-eye grayStrong pointer iconBotton transition" title='Desactivar' onclick='alertConfirm("<?php echo $msgAlert; ?>", "<?php echo $urlAlert; ?>");'></i>
							
					<?php } ?>
					</div>
					<div class='col-sm-2 textCenter'>
						<div class='col-xs-6'>
							<i class="fa fa-<?php if($row->STATUS == 1){echo 'check-circle green';}else if($row->STATUS == 2){echo 'check-circle orange';}else{echo 'minus-circle grayStrong';} ?> pointer iconBotton transition"></i>
						</div>
					<?php 
						/*$totalProduct = $supObj->getSupplierProduct($row->ID);
						if($totalProduct == 0) {
							$urlAlert = "modules/supplier/action.php?".$urlMod."&action=delete&id=".$row->ID;
							$msgAlert = "¡ATENCI&Oacute;N! Va a eliminar al usuario ".$Name.". &iquest;Est&aacute; seguro?";
					?>
							<div class='col-xs-6'>
								<i class="fa fa-trash grayStrong pointer iconBotton transition" title='Eliminar' onclick='alertConfirm("<?php echo $msgAlert; ?>", "<?php echo $urlAlert; ?>");'></i>
							</div>
				<?php 	}else { */?>
							<div class='col-xs-6'>
								<i class="fa fa-trash grayLight pointer iconBotton transition" title='Eliminar' onclick="alert('Debe desvincular al proveedor de los productos asociados a el.');"></i>
							</div>
					<?php //} ?>
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
