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
	
	if (!isset($_GET['filtersup']) || $_GET['filtersup'] == 0) {
		$filtersup = 0;
		$filtersupq = "";
	} else {
		$filtersup = intval($_GET['filtersup']);
		$filtersupq = " AND ".preBD."products.IDSUPPLIER = '".$filtersup."'";
	}
	if (!isset($_GET['search']) || $_GET['search'] == "") {
		$search = NULL;
		$searchq = "";
	} else {
		$search = $_GET['search'];
		$searchq = " AND ".preBD."products.TITLE LIKE '%".$search."%'";
		$searchq .= " OR ".preBD."products.SUMARY LIKE '%".$search."%'";
		$searchq .= " OR ".preBD."products.TEXT LIKE '%".$search."%'";
	}
	
	if (!isset($_GET['user'])) {
		$record = NULL;
		$recordq = "";
	}else {
		$record = intval($_GET['id']);
		$recordq = " AND ".preBD."product.ID='".$record."'";
		$searchq = "";
	}
	require_once("includes/classes/Image/class.Image.php");
	require_once("includes/classes/Product/class.Product.php");
	require_once("includes/classes/Supplier/class.Supplier.php");
	
	$proObj = new Product();
	$supObj = new Supplier();
	
	$urlMod = "mnu=".$mnu."&com=".$com."&tpl=".$tpl."&opt=".$opt."&recordsperpage=".$recordsperpage."&search=".$search."&page=".$page;
	
	$q1 = "SELECT * FROM ".preBD."products where true " . $searchq . $filtersupq . $recordq;
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
				
				<div class='col-sm-4 top header-list'>
					<span class=' white'>Mostrar&nbsp;&nbsp;</span>
					<select name='recordsperpage' id='recordsperpage' width='20' onchange='dropdown.submit();'>
						<option value='5'<?php if ($recordsperpage == 5) {echo " selected";} ?>>5</option>
						<option value='10'<?php if ($recordsperpage == 10) {echo " selected";} ?>>10</option>
						<option value='25'<?php if ($recordsperpage == 25) {echo " selected";} ?>>25</option>
						<option value='50'<?php if ($recordsperpage == 50) {echo " selected";} ?>>50</option>
					</select>
					<span class='white'>&nbsp;de <?php echo $totalrecods; ?></span>
				</div>
				<div class='col-sm-4 top header-list'>
					<span class=' white'>Proveedores&nbsp;&nbsp;</span>
					<select name='filtersup' id='filtersup' width='20' onchange='dropdown.submit();'>
						<option value='0'<?php if ($filtersup == 0) {echo " selected";} ?>>Seleccione un proveedor</option>
						<?php
							$sups = $supObj->allSupplier();
							foreach($sups as $sup) {
						?>
								<option value='<?php echo $sup->ID; ?>'<?php if ($filtersup == $sup->ID) {echo " selected";} ?>><?php echo $sup->TITLE; ?></option>
						
							<?php } ?>
					</select>
				</div>
				<div class='col-sm-4 top header-list'>
					<input type='text' name='search' id='search' size='20' maxlength='150' value='<?php echo $search; ?>'>
					<input type='submit' value='Buscar'>
				</div>
			</form>
		</div>
	</div>
	<div class="separator30">&nbsp;</div>
	<div class='container container-admin'>
		<div class='row'>
			<div class='col-xs-2 cp_title top'>
				<div class='bold textLeft'>&nbsp;</div>
				<div class='bold textLeft'>#</div>
			</div>
			<div class='col-xs-5 cp_title top'>
				<div class='bold textLeft'>Nombre</div>
				<div class='bold textLeft'>Proveedor</div>
			</div>
			<div class='col-xs-3 cp_title top'>
				<div class='bold textLeft'>&nbsp;</div>
				<div class='bold textLeft'>Categorías</div>
			</div>
			<div class='col-xs-2 cp_title top'>
				<div class='bold textLeft'>&nbsp;</div>
				<div class='bold textLeft'>Estado</div>
			</div>
		</div>
	</div>
	<div class="separator30">&nbsp;</div>
	<div class='container container-admin'>
		<div class='row'>
<?php	
			$q = "SELECT ".preBD."products.* FROM 
					".preBD."products 
					where true " . $searchq . $filtersupq . $recordq . " ORDER BY TITLE desc LIMIT " . $firstrecord . ", " . $recordsperpage;
			$result = checkingQuery($connectBD, $q);
			
			while ($row = mysqli_fetch_object($result)):
				$imgObj = new Image();
				$imgObj->path = "product";
				$imgObj->pathoriginal = "original";
				$imgObj->paththumb = "thumb";
				
				$thumb = $proObj->productImageFav($row->ID);
				$urlThumb = $imgObj->dirView.$imgObj->path."/".$imgObj->paththumb."/1-".$thumb->URL;
?>		
				<div class='col-md-12 shaded item-list'>
					<div class='col-sm-2'>
						<div class='cp_number bold center m1' style='font-size:14px;'><?php echo $row->ID; ?></div>
							<a style='font-size:14px;' href='index.php?<?php echo $urlMod; ?>&id=<?php echo $row->ID; ?>'>
							<img src='<?php echo $urlThumb; ?>' style='border:none;width:110px;'/>
						</a>
					</div>
				
					<div class='col-sm-5'>
						<div class='bold'>
							<a class="transition" style='font-size:14px;' href='index.php?mnu=<?php echo $mnu; ?>&com=<?php echo $com; ?>&tpl=edit&opt=<?php echo $opt; ?>&id=<?php echo $row->ID; ?>'>
								<?php echo $row->TITLE; ?>
							</a>
						</div>
						<div class='grayNormal'>
							<?php 
								$sup = $proObj->infoSupplier($row->ID);
								echo $sup->TITLE; 
							?>
						</div>
					</div>
				
					<div class='col-sm-3'>
						<div class='grayNormal'>
						<?php 
							$cats = $proObj->totalInfoCategories($row->ID);
							for($c=0;$c<count($cats);$c++) {
								echo "<em>".$cats[$c]->TITLE. "</em>"; 
								if($c<count($cats)-1) {
									echo ", ";
								}
							}
						?>
						</div>
					</div>
					<div class='col-sm-2 textCenter'>
				<?php 
						if ($row->STATUS == 0) {
							$urlAlert = "modules/product/action.php?".$urlMod."&action=publish&id=".$row->ID;
							$msgAlert = "¡ATENCI&Oacute;N! Va a publicar el producto ".$row->TITLE.". &iquest;Est&aacute; seguro?";
						}else { 
							$urlAlert = "modules/product/action.php?".$urlMod."&action=unpublish&id=".$row->ID;
							$msgAlert = "¡ATENCI&Oacute;N! Va a desactivar al producto ".$row->TITLE.". &iquest;Est&aacute; seguro?";
						}
				?>
						<div class='col-xs-6'>
							<i class="fa fa-<?php if($row->STATUS == 1){echo 'check-circle grayStrong';}else{echo 'minus-circle grayStrong';} ?> pointer iconBotton transition" onclick='alertConfirm("<?php echo $msgAlert; ?>", "<?php echo $urlAlert; ?>");'></i>
						</div>
					<?php 
						$urlAlert = "modules/product/action.php?".$urlMod."&action=delete&id=".$row->ID;
						$msgAlert = "¡ATENCI&Oacute;N! Va a eliminar el producto ".$row->TITLE.". &iquest;Est&aacute; seguro?";
					?>
						<div class='col-xs-6'>
							<i class="fa fa-trash grayStrong pointer iconBotton transition" title='Eliminar' onclick='alertConfirm("<?php echo $msgAlert; ?>", "<?php echo $urlAlert; ?>");'></i>
						</div>
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
