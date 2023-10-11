<?php if (allowed($mnu)) { ?>	
		
		<div class='cp_mnu_title title_header_mod'>Listado de art&iacute;culos</div>
		<?php 
		if (isset($_GET['msg'])) {
			$msg = utf8_encode($_GET['msg']); ?>
			<div class='cp_info'><img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' /><p><?php echo $msg; ?></p></div>
			<br/>
		<?php }
		$trash = 0;
		include ("components/articles/articles.list.php");
		if (isset($_GET['action'])) {
			$action = $_GET['action'];

	// DELETE RECORD
			if ($action == 'Deleterecord') {
				$article = $_GET['record']; ?>
				<br/>
				<div class='cp_alert'>
					<img class='cp_msgicon' src='images/alert.png' alt='¡INFORMACIÓN!' />
					<p>¡ATENCIÓN! Va a eliminar el artículo <?php echo $article; ?>. Seleccione una opción:</p>
				</div>
				<br/>
				<form method='post' action='modules/articles/delete_record.php'>
					<input type='hidden' name='mnu' value='<?php echo $mnu; ?>' />
					<input type='hidden' name='type' value='<?php echo $typeArticle; ?>' />
					<div class='cp_table650'>
						<div class='cp_formfield_xl'>
							<input type='radio' name='option' id='option' value='0' checked>Enviar a la papelera
						</div>
						<br/>
					</div>
					<div class='cp_table250'>
						<div class='cp_formfield_xl'>
							<input type='radio' name='option' id='option' value='1'>Borrar definitivamente
						</div>
						&nbsp;&nbsp;
					</div>
					<input type='hidden' name='record' value='<?php echo $article; ?>' />
					<div class='cp_table'>
						<input type='submit' value='Eliminar artículo' />
					</div>
				</form>
			<?php }
		}
}else{
	echo "<p>No tiene permiso para acceder a esta sección.</p>";
}?>	