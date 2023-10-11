<header id="<?php echo $view; ?>" class="intro-header">
	<div class="header-content">
		<div class="header-content-inner">
			<h1 class="arial" id="">
			<?php if(isset($_GET["supplier"]) && intval($_GET["supplier"]) > 0) { ?>
				TRAMITAR PEDIDO
			<?php }else if(isset($_GET["ref"]) && intval($_GET["ref"]) > 0) { ?>
				PEDIDO REALIZADO
			<?php } ?>
			</h1>
			<div class="separator10"></div>
			<div class="separator1 bgYellow"></div>
			<h3><?php echo $supplierCart->TITLE; ?></h3>
		</div>
	</div>
</header>