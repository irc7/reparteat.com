<?php if (allowed($mnu)) {
		$q = "Select * from ".preBD."configuration_modules where IDMENU = 8 and PERMISSION >= '" . $_SESSION[PDCLOG]["Type"] ."' order by POSITION asc";
		
		$result = checkingQuery($connectBD, $q);
		while($row = mysqli_fetch_object($result)){
			$codigo = build_menu($row->ID, $row->MODULE, $row->PERMISSION, $row->MENU, $row->IMAGE);
			echo $codigo;
		} 
	//pre($_SESSION);
		if(($_SESSION[PDCLOG]['Login'] == "webmaster@ismaelrc.es") && ($_SESSION[PDCLOG]['Type'] == 4)){ ?>
			<div class="cp_mnu"></div>		
			<div class="cp_mnu title_mnu">Configuración módulos</div>
			<div class="cp_mnu"><a href="index.php?mnu=configuration&com=module&tpl=option" class="cp_col1_opt">Listado de módulos</a></div>
			<div class='menu_fin'></div>
	<?php }	?>  	
<?php } ?>