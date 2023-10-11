<?php
	if (isset($_GET['msg'])) {
		$msg = utf8_encode($_GET['msg']); ?>
	<div style="width:100%;clear:both;margin-bottom:20px;">	
		<div class='cp_info'>
			<img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' />
			<p><?php echo $msg; ?></p>
		</div>
	</div>
<?php 
	}
	showInfo();

	if(check_temp()){
		echo "<div style='float:right;margin-top:100px;'>
			<a href='#' onclick='if(confirm(\"¡ATENCI&Oacute;N! Si alg&uacute;n usuario est&aacute; editando alg&uacute;n art&iacute;culo, perder&aacute; los cambios. &iquest;Desea continuar?\"))document.location = (\"modules/articles/delete_file_temp.php?return=home\");else history.back();'><span>Vaciar temporales&nbsp;&nbsp;<img src='images/temp.png' class='image middle' style='margin-bottom: 5px;' alt='Eliminar archivos temporales' title='Eliminar archivos temporales' /></span></a>\r\n";
	}

?>