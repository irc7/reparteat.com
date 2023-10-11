<?php if (allowed($mnu)) { ?>
		
		<div class='cp_mnu_title'>Papelera de art&iacute;culos</div>
		<?php
		if (isset($_GET['msg'])) {
			$msg = utf8_encode($_GET['msg']); ?>
			<div class='cp_info'><img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' /><p><?php echo $msg; ?></p></div>
			<br/>
		<?php }
		$trash = 1;
		include ("components/articles/articles.list.php");
		if (isset($_GET['action'])) {
			$action = $_GET['action'];
	// DELETE RECORD
			if ($action == 'Deleterecord') {
				$record = $_GET['record']; ?>
				<br/>
				<div class='cp_info'>
					<img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' />
					<p>¡ATENCIÓN! Va a eliminar el artículo <?php echo $record; ?> definitivamente</p>
				</div>
				<br/>
				<form method='post' action='modules/articles/delete_record.php'>
					<input type='hidden' name='mnu' value='<?php echo $mnu; ?>' />
					<input type='hidden' name='type' value='<?php echo $typeArticle; ?>' />
					<input type='hidden' name='option' value='1' />
					<input type='hidden' name='record' value='<?php echo $record; ?>' />
					<input type='hidden' name='trash' value='1' />
					<input type='submit' value='Eliminar artículo' />
				</form>
			<?php }
		}
}else{
	echo "<p>No tiene permiso para acceder a esta sección.</p>";
}?>	