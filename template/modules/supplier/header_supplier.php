<?php
	$zoneObj = new Zone();
	$zones = $zoneObj->listZones();
?>
<header id="<?php echo $view; ?>" class="intro-header">
	<div class="header-content">
		<div class="header-content-inner">
		<?php if(isset($_POST["search-home"])) { ?>
			<h1 class="arial" id="">UNA COMIDA</h1>
		<?php }else{ ?>
			<h1 class="arial" id="">Todos los restaurantes</h1>
		<?php } ?>
		</div>
	</div>
</header>