<header id="<?php echo $view; ?>-product" class="intro-header-product"
	<?php if($supView->IMAGE != ""){ ?> 
		style="background-image:url(<?php echo DOMAIN; ?>files/supplier/image/<?php echo $supView->IMAGE; ?>);"
	<?php } ?>>
	<div class="header-content">
		<div class="header-content-inner">
			<h1 class="arial" id=""><?php echo $supView->TITLE; ?></h1>
			<h3 class="arial" id=""><?php echo $supView->ESLOGAN; ?></h3>
		</div>
	</div>
	<?php if($view == "supplier") { ?>
		<a href="<?php echo DOMAIN.SLUGSUP; ?>" title="Volver al listado" alt="Volver al listado">
	<?php }else if($view == "product") { ?>
		<a href="<?php echo DOMAIN.SLUGSUP."/".$supView->SLUG; ?>" title="Volver a <?php echo $supView->TITLE; ?>" alt="Volver a <?php echo $supView->TITLE; ?>">
	<?php } ?>
			<button class="btn return-back yellow bgGreen transition">
				<span>Volver</span> <i class="fa fa-arrow-left"></i>
			</button>
	</a>
</header>