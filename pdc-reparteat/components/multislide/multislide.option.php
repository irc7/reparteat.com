<?php if (allowed($mnu)) { ?>	
		<div class='cp_mnu_title title_header_mod'>Listado de slider</div>
		<?php 
		if (isset($_GET['msg'])) {
			$msg = utf8_encode($_GET['msg']); ?>
			<div class='cp_info'><img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' /><p><?php echo $msg; ?></p></div>
			<br/>
		<?php }
	
		include ("components/multislide/multislide.list.php");
	
	}else{
		echo "<p>No tiene permiso para acceder a esta sección.</p>";
	}
?>	