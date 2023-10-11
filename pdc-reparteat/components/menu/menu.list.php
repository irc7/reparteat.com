<div class='cp_table240 cp_title'>Elemento de men&uacute;</div>
<div class='cp_table350 cp_title'>Enlace</div>
<div class='cp_table60 cp_title'>Posición</div>
<?php
	$i=0;
	while($i<2) {
		echo "<div class='cp_table25 cp_title'>&nbsp;</div>";
		$i++;
	}
?>
<br/>
<br/>
<?php
	$q = "SELECT * FROM ".preBD."menu_item where PARENT = 0  and IDMENU = ".$filtermenu." ORDER BY POSITION ASC";
	$result = checkingQuery($connectBD, $q);
	$total_parents = mysqli_num_rows($result);
	if($total_parents == 0){
?>
	<div class='cp_alert noerror' id='info-Parent'></div>
	<div class='cp_alert noerror' id='info-Title'></div>
		<form method='post' action='modules/menu/create_menu.php' id='mainform' name='mainform'>
			<div class='cp_table650'>
				<div class='cp_table' style='width:180px;padding-top:3px;font-weight:bold;'>Nombre del menú:&nbsp;</div>
				<input type='text' name='Title' id='Title' title='Nombre de menú' style='width:200px;float:none;' />
			</div>
			<div class='cp_table650'>
               	<div class='cp_table' style='width:180px;padding-top:3px;font-weight:bold;'>Nº de elementos principales:&nbsp;</div>
                <input type='text' name='Parent' id='Parent' title='Elementos principales' value='0' style='width:40px;text-align:right;float:none;' />
				<img class='image middle' src='images/loading.gif' style='margin-left:20px;visibility: hidden; padding: 10px 20px 0px 0px;' id='loading'>
            </div>
			<div style='display:block;clear:both;'>
				<div class='cp_table' style='width:160px;'>&nbsp;</div>
				<div class='cp_table'>
                	<input type='button' name='save' value='Crear menú' onclick='showloading(1); validate(this); return false;' />
                </div>
			</div>
		</form>
<script type='text/javascript'>
	includeField('Title','string');
	includeField('Parent','number');
</script>
<?php
	} else { 
		while($row = mysqli_fetch_array($result)) {
			$id = $row['ID'];
			$position = $row['POSITION'];
			echo "<div class='cp_table' style='border-bottom:1px solid #ededed;padding-bottom:1px;padding-top:3px;'>";
			$q = "select ID, TITLE from ".preBD."menu_item where PARENT = " . $id;
			$result2 = checkingQuery($connectBD, $q);
			$num_submenu = mysqli_num_rows($result2);
		//TITULO SEGUN TENGA MENU O SUBMENU	
			if($num_submenu > 0) {
				echo "<div class='cp_table240'><a href='#' title='".$row['TITLE']."' onclick='view_submenu_level1(".$id.", \"".DOMAIN."\");return false;'>";
				echo "<img id='leyend_menu_".$id."' src='images/leyen_menu_con.png' style='border:none;margin-right:5px;margin-top:5px;' />" . $row['TITLE']."</a></div>\r\n";
			} else {
				echo "<div class='cp_table240'><img src='images/leyen_menu_sin.png' style='border:none;margin-right:5px;margin-top:5px;' />" . $row['TITLE'] . "</div>";
			}
		//ENLACE A
			if($row["TYPE"] == -1) {
				echo "<div class='cp_table350'>".$row['IDVIEW']."</a></div>";
			} else if($row["TYPE"] == 2){
				$q = "select TITLE from ".preBD."articles where ID = " . $row['IDVIEW'];
				$result2 = checkingQuery($connectBD, $q);
				$title_url = mysqli_fetch_assoc($result2);
				if($title_url["TITLE"] != ""){
					echo "<div class='cp_table350'>".cutting($title_url["TITLE"],35)."</div>"; 	
				}else{
					echo "<div class='cp_table350' style='color:red;'>Artículo eliminado!</div>"; 	
				}
			} else if($row["TYPE"] == 1){
				$q = "select TITLE from ".preBD."articles_sections where ID = " . $row['IDVIEW'];
				$result2 = checkingQuery($connectBD, $q);
				$title_url = mysqli_fetch_assoc($result2);
				if($title_url["TITLE"] != ""){
					echo "<div class='cp_table350'>".cutting($title_url["TITLE"],35)."</div>"; 	
				}else{
					echo "<div class='cp_table350' style='color:red;'>¡Sección eliminada!</div>"; 	
				}
			} else if($row["TYPE"] == 3){
				$q = "select TITLE from ".preBD."download_sections where ID = " . $row['IDVIEW'];
				$result2 = checkingQuery($connectBD, $q);
				$title_url = mysqli_fetch_assoc($result2);
				if($title_url["TITLE"] != ""){
					echo "<div class='cp_table350'>".cutting($title_url["TITLE"],35)."</div>"; 	
				}else{
					echo "<div class='cp_table350' style='color:red;'>¡Sección descarga eliminada!</div>"; 	
				}	
			} else if($row["TYPE"] == 4){
				$q = "select TITLE from ".preBD."downloads where ID = " . $row['IDVIEW'];
				$result2 = checkingQuery($connectBD, $q);
				$title_url = mysqli_fetch_assoc($result2);
				if($title_url["TITLE"] != ""){
					echo "<div class='cp_table350'>".cutting($title_url["TITLE"],35)."</div>"; 	
				}else{
					echo "<div class='cp_table350' style='color:red;'>¡Descarga eliminada!</div>"; 	
				}	
			} else if($row["TYPE"] == 5){
				$q = "select TITLE from ".preBD."videos_gallery where ID = " . $row['IDVIEW'];
				$result2 = checkingQuery($connectBD, $q);
				$title_url = mysqli_fetch_assoc($result2);
				if($title_url["TITLE"] != ""){
					echo "<div class='cp_table350'>".cutting($title_url["TITLE"],35)."</div>"; 	
				}else{
					echo "<div class='cp_table350' style='color:red;'>Sección vídeo eliminada!</div>"; 	
				}	
			} else if($row["TYPE"] == 6){
				$q = "select TITLE from ".preBD."videos where ID = " . $row['IDVIEW'];
				$result2 = checkingQuery($connectBD, $q);
				$title_url = mysqli_fetch_assoc($result2);
				if($title_url["TITLE"] != ""){
					echo "<div class='cp_table350'>".cutting($title_url["TITLE"],35)."</div>"; 	
				}else{
					echo "<div class='cp_table350' style='color:red;'>Vídeo eliminada!</div>"; 	
				}	
			} else if($row["TYPE"] == 7){
				$q = "select TITLE from ".preBD."images_gallery_sections where ID = " . $row['IDVIEW'];
				$result2 = checkingQuery($connectBD, $q);
				$title_url = mysqli_fetch_assoc($result2);
				if($title_url["TITLE"] != ""){
					echo "<div class='cp_table350'>".cutting($title_url["TITLE"],35)."</div>"; 	
				}else{
					echo "<div class='cp_table350' style='color:red;'>Álbum de galería eliminada!</div>"; 	
				}	
			} else if($row["TYPE"] == 8){
				$q = "select TITLE from ".preBD."images_gallery where ID = " . $row['IDVIEW'];
				$result2 = checkingQuery($connectBD, $q);
				$title_url = mysqli_fetch_assoc($result2);
				if($title_url["TITLE"] != ""){
					echo "<div class='cp_table350'>".cutting($title_url["TITLE"],35)."</div>"; 	
				}else{
					echo "<div class='cp_table350' style='color:red;'>Galería eliminada!</div>"; 	
				}
			} else if($row["TYPE"] == 9) {
				$q = "select TITLE,URL from ".preBD."download_docs where ID = " . $row['IDVIEW'];
				$result2 = checkingQuery($connectBD, $q);
				
				if($title_url = mysqli_fetch_assoc($result2)){
					echo "<div class='cp_table350'><em>Doc:</em> <a href='".DOMAIN."files/download/doc/".$title_url["URL"]."' target='_blank'>".cutting($title_url["TITLE"],35)."</a></div>"; 	
				}else{
					echo "<div class='cp_table350' style='color:red;'>¡Documento de descarga eliminado!</a></div>"; 	
				}	
			} else {
				echo "<div class='cp_table350'>Sin enlazar</div>";	
			} ?>
			
<!-- POSITION -->
		<div class='cp_table60 top'>
		<div class='cp_table60'>
        <?php
		$q_last = "select MAX(POSITION) as lastPosition from ".preBD."menu_item where PARENT = " . $row["PARENT"] . " and IDMENU = " . $filtermenu; 
			$result_last = checkingQuery($connectBD, $q_last) ;
			$last = mysqli_fetch_assoc($result_last);
			$lastPosition = $last["lastPosition"];
			if ($position == 1) { ?>
				<img class='image' src='images/up_off.png' alt='' title='' />
			<?php
			} else { ?>
				<a href='modules/menu/moveUp_item_menu.php?filtermenu=<?php echo $filtermenu ?>&item=<?php echo $id ?>'><img class='image' src='images/up.png' alt='Subir' title='Subir' /></a>
			<?php
			}
			if ($position == $lastPosition) {
			echo "<img class='image' src='images/down_off.png' alt='' title='' />";
			}
			else {
				echo "<a href='modules/menu/moveDown_item_menu.php?filtermenu=".$filtermenu."&item=".$id."'><img class='image' src='images/down.png' alt='Bajar' title='Bajar' /></a>";
			}
			
			echo "&nbsp;</div>";
			echo "</div>";
			
			echo "<div class='cp_table60'>";
			if (allowed($mnu) != 1) {
				echo "<div class='cp_table25'><img class='image' src='images/edit_off.png' alt='' title='' /></a></div>\r\n";
			}
			else { ?>
				<div class='cp_table25'><a href='index.php?mnu=design&com=menu&tpl=option&action=EditItem&filtermenu=<?php echo $filtermenu ?>&item=<?php echo $id ?>#EditItem'><img class='image' src='images/edit.png' alt='Editar <?php echo $row["TITLE"] ?>' title='Editar <?php echo $row["TITLE"] ?>' /></a></div>
			<?php
            }
			if (allowed($mnu) != 1) { ?>
				<div class='cp_table25'><img class='image' src='images/delete_off.png' alt='' title='' /></a></div>
            <?php
			} else { ?>
				<div class='cp_table25'><a href='index.php?mnu=design&com=menu&tpl=option&action=DeleteItem&filtermenu=<?php echo$filtermenu ?>&item=<?php echo $id ?>#DeleteItem'><img class='image' src='images/delete.png' alt='Eliminar item' title='Eliminar item' /></a></div>
			<?php
			} ?>
		</div>
		</div>
		<br/>
        <?php
        if($num_submenu > 0) { 
		?>
			<div id='submenu_<?php echo $id ?>' class='cp_table_' style='padding-left:<?php echo ((($row["LEVEL"]+1)*2)*5) ?>px;display:none;'>
				<?php listar_submenu($id, $row["LEVEL"], $filtermenu); ?>
			</div>
		<?php
        } ?>
    <?php
    } ?>
	<br/>
	<br/>
	<div class='cp_table240'>&nbsp;</div>
	<div class='cp_table350'>&nbsp;</div>
	<div class='cp_table60'>&nbsp;</div>
	<div class='cp_table25'>&nbsp;</div>
	<?php
	if (allowed($mnu) == 1) { ?>
		<div class='cp_table25 new_section_ie'><a href='index.php?mnu=design&com=menu&tpl=option&action=CreateItem<?php if(isset($filtermenu)) {echo "&filtermenu=".$filtermenu;} ?>#CreateItem'><img class='image' src='images/add.png' alt='Crear item' title='Crear item' /></a></div>
	<?php
	} else { ?>
		<div class='cp_table25 new_section_ie'><img class='image' src='images/add_off.png' alt='' title='' /></div>
    <?php
    } ?>
<?php
} ?>