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
		$searchq = " AND ID LIKE '%".$search."%'";
		$searchq .= " OR TITLE LIKE '%".$search."%'";
		$searchq .= " OR TEXT LIKE '%".$search."%'";
	}
	
	$urlMod = "mnu=".$mnu."&com=".$com."&tpl=".$tpl."&opt=".$opt."&recordsperpage=".$recordsperpage."&search=".$search."&page=".$page;
	
	$catObj = new Category();
	$totalrecods = $catObj->totalCategory();
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
			<div class='col-sm-9 cp_title top'>
				<div class='bold textLeft'>Título</div>
			</div>
			<div class='col-sm-2 cp_title top'>
				<div class='bold textLeft'>&nbsp;</div>
			</div>
		</div>
	</div>
	<div class="separator30">&nbsp;</div>
	<div class='container container-admin'>
		<div class='row'>
<?php	
			$catList = $catObj->listCategory($searchq, $recordq,$firstrecord,$recordsperpage);
			
			for($i=0;$i<count($catList);$i++) {
?>		
				<div class='col-md-12 shaded item-list'>
					<div class='col-sm-1'>
						<div class='cp_number bold center m1' style='font-size:14px;'><?php echo $catList[$i]->ID; ?></div>
					</div>
					<div class='col-sm-8'>
						<div class='bold'>
							<?php echo $catList[$i]->TITLE; ?>
						</div>
					</div>
					<div class='col-sm-2 textCenter'>
						<i id="open-editcat-<?php echo $catList[$i]->ID; ?>" class="fa fa-edit pointer iconBotton transition open-editcat"></i>
					</div>
					<div class='col-sm-1 textCenter'>
					<?php 
						$urlAlert = "modules/supplier/action_cat.php?".$urlMod."&action=delete&id=".$catList[$i]->ID;
						$msgAlert = "¡ATENCI&Oacute;N! Va a eliminar la categoría de proveedores ".$catList[$i]->TITLE." y sus asociaciones a estos mismos. &iquest;Est&aacute; seguro?";
					?>
						<div class='col-xs-6'>
							<i class="fa fa-trash grayStrong pointer iconBotton transition" title='Eliminar' onclick='alertConfirm("<?php echo $msgAlert; ?>", "<?php echo $urlAlert; ?>");'></i>
						</div>
					</div>
				</div>
				<div class="separator">&nbsp;</div>
				<div id="editcat-<?php echo $catList[$i]->ID; ?>" class="form-category">
					<form method='post' action='modules/supplier/edit_category.php' enctype='multipart/form-data' id='mainform-<?php echo $catList[$i]->ID; ?>' name='mainform'>
						<input type="hidden" name="mnu" value="<?php echo $mnu; ?>" />
						<input type="hidden" name="com" value="<?php echo $com; ?>" />
						<input type="hidden" name="tpl" value="<?php echo $tpl; ?>" />
						<input type="hidden" name="opt" value="<?php echo $opt; ?>" />
						<input type="hidden" name="idCat" value="<?php echo $catList[$i]->ID; ?>" />
						
						<div class='row dotted padding-space' style="margin:0px;">	
							<div class="form-group">
								<label class="label-field" for="Name">Título *:</label>
								<input type="text" name="Title" id="Title-<?php echo $catList[$i]->ID; ?>" class="form-control form-s" title="Título" value="<?php echo $catList[$i]->TITLE; ?>" disabled />
								<p id="error-Title"></p>
							</div>
							<div class="form-group">
								<label class="label-field" for="Surame">Descripción:</label>
								<textarea name="Text" id="Text-<?php echo $catList[$i]->ID; ?>" class="form-control textarea-field-100x3" disabled ><?php echo $catList[$i]->TEXT; ?></textarea>
							</div>
							<div class='form-group'>	
								<div class='col-md-5'>&nbsp;</div>
									<div class='col-md-2'>
									<img class='image middle' src='images/loading.gif' style='visibility: hidden; padding: 10px 20px 0px 0px;' id='loading'>
								</div>
								<div class='col-md-5'>
									<input class="btn tf-btn btn-default transition floatRight bgGreen white bold" type='submit' name='save' value='GUARDAR' disabled />
								</div>
							</div>
						</div>	
					</form>
					<script type="text/javascript">
						var validation_options<?php echo $catList[$i]->ID; ?> = {
							form: document.getElementById("mainform-<?php echo $catList[$i]->ID; ?>"),
							fields: [
								{
									id: "Title-<?php echo $catList[$i]->ID; ?>",
									type: "string",
									min: 2,
									max: 256
								}
							]
						};
						var v2<?php echo $catList[$i]->ID; ?> = new Validation(validation_options<?php echo $catList[$i]->ID; ?>);
					</script>
					<div class="separator30">&nbsp;</div>
				</div>
				<div class="separator">&nbsp;</div>
	<?php 	} ?>
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
