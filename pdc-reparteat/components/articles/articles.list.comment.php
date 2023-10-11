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
	}else {
		$search = trim($_GET['search']);
		$searchq = " and (NAME LIKE '%".$search."%'";
		$searchq .= " OR DATE LIKE '%".$search."%'";
		$searchq .= " OR TEXT LIKE '%".$search."%')";
	}

	if (!isset($_GET['record'])) {
		$record = 0;
		$recordq = "";
	} else {
		$record = abs(intval($_GET['record']));
		$recordq = " and ID='".$record."'";
		$searchq = "";
	}
	
	
	if (!isset($_GET['filterpost'])) {
		$filterpost = 0;
	} else {
		$filterpost = abs(intval($_GET['filterpost']));
	}
	
	if($filterpost == 0) {
		$blogq = "";
	}else {
		$blogq = " and IDARTICLE='".$filterpost."'";
	}
	
	$url_com = "mnu=".$mnu."&com=".$com."&tpl=".$tpl."&opt=".$opt;
	
	$q = "SELECT count(*)as total FROM ".preBD."articles_comment where true " . $recordq;
	if($record == 0) {
		$q .= $searchq . $blogq;
	}
	$resultGeneral = checkingQuery($connectBD, $q);
	$t = mysqli_fetch_object($resultGeneral);
	$totalcomment = $t->total;
	
	?>
	<div class='cp_box darkshaded cp_height30'>
		<form name='dropdown' method="get" action="index.php">
			<input type='hidden' name='mnu' value='<?php echo $mnu; ?>' />
			<input type='hidden' name='com' value='<?php echo $com; ?>' />
			<input type='hidden' name='tpl' value='<?php echo $tpl; ?>' />
			<input type='hidden' name='opt' value='<?php echo $opt; ?>' />
			<div class='cp_table160 top'>
				<span class=' white'>Mostrar&nbsp;&nbsp;</span>
				<select name='recordsperpage' id='recordsperpage' width='20' onchange='dropdown.submit();'>
					<option value='5'<?php if ($recordsperpage == 5) {echo " selected='selected'";} ?>>5</option>
					<option value='10'<?php if ($recordsperpage == 10) {echo " selected='selected'";} ?>>10</option>
					<option value='25'<?php if ($recordsperpage == 25) {echo " selected:'selected'";} ?>>25</option>
					<option value='50'<?php if ($recordsperpage == 50) {echo " selected:'selected'";} ?>>50</option>
				</select>
				<span class='white'>&nbsp;de <?php echo $totalcomment; ?></span>
			</div>
			<div class='cp_table top' style="width:175px;">
				<input type='text' name='search' id='search' size='20' maxlength='150' value='<?php echo $search; ?>'>
				<input type='submit' value='Buscar'>
			</div>
			<div class='cp_table210 top right'>
				<?php 
					
					$q3 = "SELECT ID, TITLE FROM ".preBD."articles where true ORDER BY DATE_START desc";
					$result3 = checkingQuery($connectBD, $q3);
				?>
				<span class='white'>Post: </span>
				<select name='filterpost' id='filterpost' onchange='dropdown.submit();' style="width:150px;">
					<option value='0'<?php if($filterpost == 0){echo " selected='selected'";} ?>></option>
				<?php while($p = mysqli_fetch_object($result3)): ?>
						<option value='<?php echo $p->ID; ?>'<?php if ($filterpost == $p->ID){echo " selected='selected'";}?>>
							<?php echo $p->TITLE; ?>
						</option>
					<?php endwhile; ?>
				</select>
			</div>
		</form>
	</div>
	
	
	
	<div class="cp_box cp_height30">
	<div class="cp_table120 cp_title top">
	<div class="cp_table top">&nbsp;</div>
	<div class="cp_table top">&nbsp;</div>
	</br>
	
	<div class="cp_table bold">#</div>
	</div>
	<div class="cp_table300 cp_title">
	<div class="cp_table300 bold top">Comentario</div>
	</br>
	<div class="cp_table300 bold top">Autor</div>
	
	</div>
	<div class="cp_table170 cp_title">
	<div class="cp_table bold top">Fecha</div>
	</br>

	<div class="cp_table bold top">Artículo</div>
	</div>
	<div class="cp_table100 cp_title">
	<div class="cp_table bold top">&nbsp;</div>
	</br>
	
	<div class="cp_table bold top" style="margin-left: 10px;">Estado</div>
	</div>
	</div>

<?php 
	$q = "SELECT * FROM ".preBD."articles_comment where true " . $recordq;
	if($record == 0) {
		$q .= $searchq . $blogq;
	}
	$q .= " order by DATE desc limit " . $firstrecord .", ". $recordsperpage;
	$resultGeneral = checkingQuery($connectBD, $q);
	$cont = 0;
	while ($commentBD = mysqli_fetch_object($resultGeneral)) {
		$id_comment = $commentBD->ID;
		$Status = $commentBD->STATUS;
		$Name = stripcslashes($commentBD->NAME);
		$Text = stripcslashes($commentBD->TEXT);
		$Date = $commentBD->DATE;
		$Ip = $commentBD->IP;
		$q = "select TITLE, THUMBNAIL from ".preBD."articles where ID = " . $commentBD->IDARTICLE;
		$result1 = checkingQuery($connectBD, $q);
		$postBD = mysqli_fetch_object($result1);
		
		$Image_thumb = $postBD->THUMBNAIL; ?>
		
		<div class="cp_box shaded cp_height60">
			<div class="cp_number bold center m1"><?php echo $id_comment ?></div>
			<?php 
			if ($postBD->THUMBNAIL != ""){
				$img_youtube = substr($postBD->THUMBNAIL, 0, 2);
				$url_youtube = substr($postBD->THUMBNAIL, 2, strlen($postBD->THUMBNAIL)); 
				if($img_youtube == "v="){
						$url =	"http://img.youtube.com/vi/".$url_youtube."/0.jpg";
					}else{
						$url =	"../files/articles/thumb/".$Image_thumb;		
					}
				} else {
					$url =	"../css/images/blog_empty.jpg";	
				} ?>
			<div class="cp_table120" style='height:63px;overflow:hidden;'>
				<img src="<?php echo $url ?>" style='border:none;width:110px;' />
			</div>		
			<div class="cp_table300 top">
				<div class="cp_table300">
					<a style="font-size:12px;" href="index.php?<?php echo $url_com; ?>&record=<?php echo $id_comment ?>&action=Viewcomment">
						<?php echo substr($Text, 0, 90) ?>...
					</a>
				</div>
				<div style="height: 7px;"></div>
				<div class="cp_table300 bold">
					<a style="font-size:12px;" href="index.php?<?php echo $url_com; ?>&record=<?php echo $id_comment ?>&action=Viewcomment">
						<?php echo $Name ?>
					</a>
				</div>		
			</div>
			<div class="cp_table170 top">
				<div class="cp_table">
					<?php echo  $Date ?>
				</div>
				<div style="height: 7px;"></div>
				<div class="cp_table" style="font-weight:bold;">
				<?php 
					echo substr($postBD->TITLE,0,45);
					if (strlen($postBD->TITLE) > 45) {echo "...";} 
				?>
				</div>
			</div>
			
			<div class="cp_table top" style="margin-left: 10px;">
			<?php if ($Status == 0) { 
				$msgAlert = "Va a publicar el comentario para el post " . $postBD->TITLE . "¿Está seguro?";
				$urlAlert = "modules/articles/publish_comment.php?record=".$id_comment."&status=1";
			?>
				<div class="cp_table">
					<img class="image pointer" src="images/unchecked.png" alt="Publicar" title="Publicar" onclick="alertConfirm('<?php echo $msgAlert; ?>', '<?php echo $urlAlert; ?>');" />
				</div>
			<?php }else { 
				$msgAlert = "Va a despublicar el comentario para el post " . $postBD->TITLE . "¿Está seguro?";
				$urlAlert = "modules/articles/publish_comment.php?record=".$id_comment."&status=0";
			?>
					<div class="cp_table">
						<img class="image pointer" src="images/checked.png" alt="Pasar a borrador" title="Pasar a borrador" onclick="alertConfirm('<?php echo $msgAlert; ?>', '<?php echo $urlAlert; ?>');" />
					</div>
			<?php } 
				$msgAlert = "Va a eliminar el comentario para el post " . $postBD->TITLE . "¿Está seguro?";
				$urlAlert = "modules/articles/delete_comment.php?record=".$id_comment;
			?>
				<div class="cp_table25" style="margin-left: 40px;">
					<img class="image pointer" src="images/delete.png" alt="Eliminar" title="Eliminar" onclick="alertConfirm('<?php echo $msgAlert; ?>', '<?php echo $urlAlert; ?>');" />
				</div>
			</div>
		</div>
		<?php $cont++;
	}
	$totalpages = ceil($totalcomment / $recordsperpage);
	$previouspage = $page - 1;
	$nextpage = $page + 1;
	$url_pag = "index.php?".$url_com."&filterpost=".$filterpost."&recordsperpage=".$recordsperpage."&search=".$search;
	if ($totalpages > 1) { ?>
		<div class="cp_box dotted cp_height25">
		<?php if ($page > 1) { ?>
			<div class="cp_table" style="margin-right:3px;">
				<a href="<?php echo $url_pag; ?>&page=<?php echo $previouspage; ?>">
					<<
				</a>
			</div>
		<?php }
		if ($page > 9) { ?>
			<div class="cp_table cp_pages center shaded">
				<a href="<?php echo $url_pag; ?>&page=1">1</a>
			</div>
			<div class="cp_table" style="margin-left:3px;">...</div>
		<?php }
		for ($i=1; $i < $totalpages + 1; $i++) {
			if ($i > ($page - 9) && $i < ($page + 9)) { ?>
				<div style="margin-right:3px;" class="cp_table cp_pages center<?php if ($page == $i) {echo" darkshaded";}else {echo " shaded";} ?>">
					<a href="<?php echo $url_pag; ?>&page=<?php echo $i ?>"<?php if ($page == $i){echo " style='color: white;'";} ?>><?php echo $i ?></a>
				</div>
			<?php }
		}
	}
	if ($page < ($totalpages - 9)) { ?>
			<div class="cp_table" style="margin-left:3px;">...</div>
			<div class="cp_table cp_pages center shaded" style="margin-right:3px;">
				<a href="<?php echo $url_pag; ?>&page=<?php echo $totalpages ?>"><?php echo $totalpages ?></a>
			</div>
		<?php }
	if ($page < $totalpages) { ?>
		<div class="cp_table" style="margin-left:3px;">
			<a href="<?php echo $url_pag; ?>&page=<?php echo $nextpage ?>">>></a>
		</div>
	<?php } ?>
	</div>	
