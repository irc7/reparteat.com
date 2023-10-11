<?php
	if(($_SESSION[PDCLOG]['Login'] == "webmaster@ismaelrc.es") && ($_SESSION[PDCLOG]['Type'] == 4)){ 
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
		}
		else {
			$search = $_GET['search'];
			$searchq = " AND MODULE LIKE '%".$search."%'";
		}
		
		if (!isset($_GET['record']) || $_GET['record'] == 0) {
			$record = NULL;
			$recordq = "";
		}else {
			$record = $_GET['record'];
			$recordq = " AND ID='".$record."'";
			$searchq = "";
		}
		
		if (!isset($_GET['filtersection']) || $_GET['filtersection'] == 0) {
			$filtersection = 0;
			$sectionq = '';
			$order = " ORDER BY IDMENU asc";
		}else {
			$filtersection = $_GET['filtersection'];
			$sectionq = " AND IDMENU='".$filtersection."'";
			$order = " ORDER BY POSITION ASC";		
		}
		
		if ($trash == 1) {
			$tpl = 'trash';
		}else {
			$tpl = 'option';
		}
		
		$q1 = "SELECT * FROM ".preBD."configuration_modules where TRUE " . $searchq . $recordq . $trashq . $sectionq; 
		
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

				<div class='cp_table240 top'>
					<input type='text' name='search' id='search' size='20' maxlength='150' value='<?php echo $search; ?>'>
					<input type='submit' value='Buscar'>
				</div>
			
				<div class='cp_table220 top right'>
					<span class='white'>Sección: </span>
					<select name='filtersection' id='filtersection' onchange='dropdown.submit();'>
						<option value='0'<?php if($filtersection == 0){echo " selected='selected'";} ?>></option>
						<?php
							for($j=1;$j<=count($array_header);$j++){
								if($array_header[$j] == $array_modules[$j]){ ?>
									<option value='<?php echo $j?>' <?php if($filtersection == $j){echo " selected='selected'";} ?>><?php echo $traducccion[$j-1]; ?></option>
								<?php }
							}					
						?>						
					</select>
				</div>
			</form>
		</div>
		
		<div class='cp_box cp_height30'>
			<div class='cp_table100 cp_title top'>
				<div class='cp_table120 top'>Imagen</div>
			</div>
			<div class='cp_table370 cp_title'>
				<div class='cp_table350 bold top'>Título</div>
			</div>
			<div class='cp_table120 cp_title'>
				<div class='cp_table120 bold top'>Sección</div>
			</div>
			<div class='cp_table50 cp_title'>
				<div class='cp_table100 bold top'>&nbsp;</div>
			</div>
			<div class='cp_table50 cp_title'>
				<div class='cp_table100 bold top'>Permiso</div>
			</div>		
		</div>
			<?php 
			
			if($filtersection != NULL) {
				$q = "SELECT count(*) as total FROM ".preBD."configuration_modules where IDMENU = " . $filtersection;
				$result = checkingQuery($connectBD, $q);
				$row_total = mysqli_fetch_assoc($result);
				$totalmodules = $row_total["total"];
			}		
			
			$q = "SELECT * FROM ".preBD."configuration_modules where TRUE " . $searchq . $recordq . $sectionq . $order . " LIMIT " . $firstrecord . ", " . $recodsperpage;
			$result = checkingQuery($connectBD, $q);
			$cont = 0;
			while ($row = mysqli_fetch_assoc($result)){			
				$titulo_module = str_replace('"','',$row["MODULE"]); 
				$Position = $row["POSITION"];
				$id = $row["ID"];
			?>	
			<div class='cp_box shaded cp_height25'>
				<div class='cp_number bold center m1'><?php echo $row['ID']; ?></div>
				<?php	
					if ($row['IMAGE'] == ""){
						$url =	"images/emptythumbnail.gif";		
					}else{
						$url =	"../pdc-reparteat/images/modules/".$row['IMAGE'];
					}
				?>
				<div class='cp_table100' style='overflow:hidden;'>
					<a style='font-size:14px;' href='index.php?mnu=content&com=module&tpl=edit&module=<?php echo $row['ID']; ?>'>
						<img class="image_module" src='<?php echo $url; ?>' width="24" height="24"/>
					</a>
				</div>	
				<div class='cp_table370 top'>
					<div class='cp_table350 bold'>	
						<a style='font-size:14px;' href='index.php?mnu=configuration&com=module&tpl=edit&module=<?php echo $row['ID']; ?>'>
							<?php echo cutting($titulo_module,80); ?>
						</a>
					</div>
				</div>
				<div class='cp_table120 top'>
					<?php 
						$aux = $row['IDMENU'];
						$title_section = $traducccion[$aux-1] ?>
					<div class='cp_table'>
						<?php 
							echo cutting($title_section,35);
						?>
					</div>
				</div>
				
				<div class='cp_table50 top' style="margin-top:-5px;">
					<div class='cp_table25'>
				<?php if($filtersection != NULL){ ?>
						<?php if ($Position == 1): ?>
							<img class='image' src='images/up_off.png' alt='' title='' style="margin-bottom:2px;"/>
						<?php else: ?>
							<a href='<?php echo DOMAIN; ?>pdc-reparteat/modules/module/moveup_module.php?recodsperpage=<?php echo $recodsperpage; ?>&page=<?php echo $page; ?>&module=<?php echo $id; ?>'>
								<img class='image' src='images/up.png' alt='Subir' title='Subir' style="float:left;"/>
							</a>&nbsp;&nbsp;
						<?php endif; ?>
						<?php if ($Position == $totalmodules): ?>
							<img class='image' src='images/down_off.png' alt='' title='' />
						<?php else: ?>
							<a href='<?php echo DOMAIN; ?>pdc-reparteat/modules/module/movedown_module.php?recodsperpage=<?php echo $recodsperpage; ?>&page=<?php echo $page; ?>&module=<?php echo $id; ?>'>
								<img class='image' src='images/down.png' alt='Bajar' title='Bajar' style="float:left;clear:both;"/>
							</a>
						<?php endif; ?>
				<?php } ?>
						&nbsp;
					</div>
				</div>	
				<?php
					$msgAlert = "¡ATENCIÓN! Va a cambiar el nivel de permiso del módulo ". $title_section;
					$urlAlert = "modules/module/permission_module.php?module=".$row["ID"];
				?>
				<div class='cp_table50'>
					<form id='permission' name='permission' method="get" action="modules/module/permission_module.php">
						<input type="hidden" name='id_module' value="<?php echo $row['ID']; ?>">
						<select name='level_permission'  id='level_permission' onchange='this.form.submit()'>
							<option value="0" <?php if($row['PERMISSION'] == 0){ ?> selected <?php } ?>>0</option>
							<option value="1" <?php if($row['PERMISSION'] == 1){ ?> selected <?php } ?>>1</option>
							<option value="2" <?php if($row['PERMISSION'] == 2){ ?> selected <?php } ?>>2</option>
							<option value="3" <?php if($row['PERMISSION'] == 3){ ?> selected <?php } ?>>3</option>
						</select>
					</form>
				</div>				
		</div>
			
		<?php $cont++;
		} 
		
		$previouspage = $page - 1;
		$nextpage = $page + 1;
		$urlPag = "index.php?mnu=".$mnu."&com=".$com."&tpl=".$tpl;
		$urlPag .= "&filtersection=".$filtersection."&recodsperpage=".$recodsperpage."&page=".$previouspage."&search=".$search;
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
<?php }else{ ?>
	<p>No tiene permiso para acceder a esta sección.</p>
<?php } ?>