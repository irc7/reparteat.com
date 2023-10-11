<?php 

	function listar_submenu($item, $level, $Menu) {
		global $connectBD;
		$q = "select * from ".preBD."menu_item where PARENT = ".$item." order by POSITION asc";

		$result3 = checkingQuery($connectBD, $q);
		while($row = mysqli_fetch_array($result3)) {
			$id = $row['ID'];
			$position = $row['POSITION'];
			
			echo "<div class='cp_table' style='border-bottom:1px solid #ededed;padding-bottom:1px;padding-top:3px;'>";
			$q = "select ID, TITLE from ".preBD."menu_item where PARENT = " . $id;
			$result2 = checkingQuery($connectBD, $q);
			$num_sub = mysqli_num_rows($result2);
		//TITULO SEGUN TENGA MENU O SUBMENU	
			$width = 240-($row["LEVEL"]*10);
			if($num_sub > 0) {
				echo "<div class='cp_table".$width."'><a href='#' title='".$row['TITLE']."' onclick='view_submenu_level2(".$item.", ".$id.", \"".DOMAIN."\");return false;'>";
				echo "<img id='leyend_menu_".$item."_".$id."' src='images/leyen_menu_con.png' style='border:none;margin-right:5px;margin-top:5px;' />" . $row['TITLE']."</a></div>\r\n";
			} else {
				echo "<div class='cp_table".$width."'><img src='images/leyen_menu_sin.png' style='border:none;margin-right:5px;margin-top:5px;' />" . $row['TITLE'] . "</div>";
			}
			//ENLACE A
			
			if($row["TYPE"] == -1) {
				echo "<div class='cp_table350'>".cutting($row['IDVIEW'],35)."</a></div>";
			} else if($row["TYPE"] == 2){
				$q = "select TITLE from ".preBD."articles where ID = " . $row['IDVIEW'];
				$result2 = checkingQuery($connectBD, $q);
				$title_url = mysqli_fetch_assoc($result2);
				if($title_url["TITLE"] != ""){
					echo "<div class='cp_table350'>".cutting($title_url["TITLE"],35)."</a></div>"; 	
				}else{
					echo "<div class='cp_table350' style='color:red;'>Artículo eliminado!</a></div>"; 	
				}		
			} else if($row["TYPE"] == 1){
				$q = "select TITLE from ".preBD."articles_sections where ID = " . $row['IDVIEW'];
				$result2 = checkingQuery($connectBD, $q);
				$title_url = mysqli_fetch_assoc($result2);
				if($title_url["TITLE"] != ""){
					echo "<div class='cp_table350'>".cutting($title_url["TITLE"],35)."</a></div>"; 	
				}else{
					echo "<div class='cp_table350' style='color:red;'>¡Sección eliminada!</a></div>"; 	
				}
			} else if($row["TYPE"] == 3){
				$q = "select TITLE from ".preBD."download_sections where ID = " . $row['IDVIEW'];
				$result2 = checkingQuery($connectBD, $q);
				$title_url = mysqli_fetch_assoc($result2);
				if($title_url["TITLE"] != ""){
					echo "<div class='cp_table350'>".cutting($title_url["TITLE"],35)."</a></div>"; 	
				}else{
					echo "<div class='cp_table350' style='color:red;'>¡Sección descarga eliminada!</a></div>"; 	
				}	
			} else if($row["TYPE"] == 4){
				$q = "select TITLE from ".preBD."downloads where ID = " . $row['IDVIEW'];
				$result2 = checkingQuery($connectBD, $q);
				$title_url = mysqli_fetch_assoc($result2);
				if($title_url["TITLE"] != ""){
					echo "<div class='cp_table350'>".cutting($title_url["TITLE"],35)."</a></div>"; 	
				}else{
					echo "<div class='cp_table350' style='color:red;'>¡Descarga eliminada!</a></div>"; 	
				}	
			} else if($row["TYPE"] == 5){
				$q = "select TITLE from ".preBD."videos_gallery where ID = " . $row['IDVIEW'];
				$result2 = checkingQuery($connectBD, $q);
				$title_url = mysqli_fetch_assoc($result2);
				if($title_url["TITLE"] != ""){
					echo "<div class='cp_table350'>".cutting($title_url["TITLE"],35)."</a></div>"; 	
				}else{
					echo "<div class='cp_table350' style='color:red;'>Sección vídeo eliminada!</a></div>"; 	
				}	
			} else if($row["TYPE"] == 6){
				$q = "select TITLE from ".preBD."videos where ID = " . $row['IDVIEW'];
				$result2 = checkingQuery($connectBD, $q);
				$title_url = mysqli_fetch_assoc($result2);
				if($title_url["TITLE"] != ""){
					echo "<div class='cp_table350'>".cutting($title_url["TITLE"],35)."</a></div>"; 	
				}else{
					echo "<div class='cp_table350' style='color:red;'>Vídeo eliminada!</a></div>"; 	
				}
			} else if($row["TYPE"] == 7){
				$q = "select TITLE from ".preBD."images_gallery_sections where ID = " . $row['IDVIEW'];
				$result2 = checkingQuery($connectBD, $q);
				$title_url = mysqli_fetch_assoc($result2);
				if($title_url["TITLE"] != ""){
					echo "<div class='cp_table350'>".cutting($title_url["TITLE"],35)."</a></div>"; 	
				}else{
					echo "<div class='cp_table350' style='color:red;'>Álbum de galería eliminada!</a></div>"; 	
				}
			} else if($row["TYPE"] == 8){
				$q = "select TITLE from ".preBD."images_gallery where ID = " . $row['IDVIEW'];
				$result2 = checkingQuery($connectBD, $q);
				$title_url = mysqli_fetch_assoc($result2);
				if($title_url["TITLE"] != ""){
					echo "<div class='cp_table350'>".cutting($title_url["TITLE"],35)."</a></div>"; 	
				}else{
					echo "<div class='cp_table350' style='color:red;'>Galería eliminada!</a></div>"; 	
				}
			} else if($row["TYPE"] == 9) {
				$q = "select TITLE, URL from ".preBD."download_docs where ID = " . $row['IDVIEW'];
				$result2 = checkingQuery($connectBD, $q);
				if($title_url = mysqli_fetch_assoc($result2)){
					echo "<div class='cp_table350'><em>Doc:</em> <a href='".DOMAIN."files/download/doc/".$title_url["URL"]."' target='_blank'>".cutting($title_url["TITLE"],35)."</a></div>"; 	
				}else{
					echo "<div class='cp_table350' style='color:red;'>¡Documento de descarga eliminado!</a></div>"; 	
				}					
			} else {
				echo "<div class='cp_table350'>Sin enlazar</div>";	
			}
			
			//POSITION
			echo "<div class='cp_table60 top'>\r\n";
			echo "<div class='cp_table60'>";
			$q_last = "select MAX(POSITION) as lastPosition from ".preBD."menu_item where PARENT = " . $row["PARENT"]; 
			$result_last = checkingQuery($connectBD, $q_last) ;
			$last = mysqli_fetch_assoc($result_last);
			$lastPosition = $last["lastPosition"];
			if ($position == 1) {
				echo "<img class='image' src='images/up_off.png' alt='' title='' />&nbsp;";
			}
			else {
				echo "<a href='modules/menu/moveUp_item_menu.php?filtermenu=".$Menu."&item=".$id."'><img class='image' src='images/up.png' alt='Subir' title='Subir' /></a>&nbsp;";
			}
			if ($position == $lastPosition) {
			echo "<img class='image' src='images/down_off.png' alt='' title='' />";
			}
			else {
				echo "<a href='modules/menu/moveDown_item_menu.php?filtermenu=".$Menu."&item=".$id."'><img class='image' src='images/down.png' alt='Bajar' title='Bajar' /></a>";
			}
			
			echo "&nbsp;</div>";
			echo "</div>";
			
			echo "<div class='cp_table60'>";
			if (allowed("design") != 1) {
				echo "<div class='cp_table25'><img class='image' src='images/edit_off.png' alt='' title='' /></a></div>\r\n";
			}
			else {
				echo "<div class='cp_table25'><a href='index.php?mnu=design&com=menu&tpl=option&action=EditItem&filtermenu=".$Menu."&item=".$id."'><img class='image' src='images/edit.png' alt='Editar ".$row["TITLE"]."' title='Editar ".$row["TITLE"]."' /></a></div>\r\n";
			}
			if (allowed("design") != 1) {
				echo "<div class='cp_table25'><img class='image' src='images/delete_off.png' alt='' title='' /></a></div>\r\n";
			}
			else {
				echo "<div class='cp_table25'><a href='index.php?mnu=design&com=menu&tpl=option&filtermenu=".$Menu."&action=DeleteItem&item=".$id."'><img class='image' src='images/delete.png' alt='Eliminar item' title='Eliminar item' /></a></div>\r\n";
			}
			echo "</div>\r\n";
			echo "</div>\r\n";
			echo "<br/>\r\n";
			if($num_sub > 0) {
				echo "<div id='submenu_".$item."_".$id."' class='cp_table' style='padding-left: 10px;display:none;'>\r\n";
				listar_submenu($id, $row["LEVEL"], $Menu);
				echo "</div>\r\n";
			}
		}
	}

	
	function module_active($id_module){
		global $connectBD;
		$q = "Select PERMISSION from ".preBD."configuration_modules where ID = ".$id_module;
		//pre($q);die();
		$result = checkingQuery($connectBD, $q);
		$permission = mysqli_fetch_assoc($result);
		
		if($permission['PERMISSION'] >= $_SESSION[PDCLOG]["Type"]){
			$permiso = 1; 
		}else{
			$permiso = 0; 
		}
		return $permiso;
	}
	
?>