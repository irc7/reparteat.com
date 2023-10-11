<?php if (allowed ($mnu)) { ?>
	<div class='cp_mnu_title title_header_mod'>Listado de proveedores</div>
	<?php if (isset($_GET['msg'])){ ?>
		<?php $msg = utf8_encode($_GET['msg']); ?>
		<div class='cp_info'><img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' /><?php echo $msg ?></div>
		<br/>
	<?php }
		include ("components/product/product.list.php");
}else{

	echo "<p>No tiene permiso para acceder a esta sección.</p>";
	
}?>	