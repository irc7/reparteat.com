<?php
	$zoneObj = new Zone();
	$zones = $zoneObj->listZones();
	
	$article = infoArticleByID(8);
?>
<header id="<?php echo $view; ?>">
	<?php 
		$slideObj = new Multislide();

		if($slider = $slideObj->multislideByHook('slider-home')) {
			include("template/modules/multislide/multislide.tpl.php");	
		} 
	?>
	<div class="header-content wow fadeIn animated"  data-wow-duration="1s" style="visibility: visible; animation-duration: 1s; animation-name: fadeIn;">
		
		<div class="header-content-inner">
			<h1 class="arial textShadow" id="homeHeading">PIDE POR ESA BOQUITA</h1>
			<h2 class="arial textShadow" id="homeHeading">Y NOSOTROS TE LO LLEVAMOS</h2>
			<hr class="yellow">
			<p class="textBoxF textShadow">Descubre los resturantes de tu zona</p>
			<div class="row">
			<?php /*	
			<?php foreach($zones as $zone) { ?>
				<div class="col-sm-6 col-sm-offset-3">
					<a class="btn btn-primary arialBold btn-search" href="<?php echo DOMAIN; ?>buscar?search=<?php echo $zone->ID; ?>">
						<?php echo $zone->CITY . " | " . $zone->CP; ?>
					</a>
					<div class="separator20"></div>		
				</div>
			<?php } ?>
				<div class="col-sm-4 textCenter">
			*/ ?>
				<div class="col-sm-6 col-sm-offset-3">
					<form method="post" action="<?php echo DOMAIN; ?>buscar" id="search-home">
						<div class="col-sm-9 col-xs-12 no-padding">
							<div class="form-group">
								<select class="form-control arial" id="search-zone" name="search" title="Seleccione una zona" required>
									<option value="0">SELECCIONA TU CIUDAD O ZONA</option>
								<?php foreach($zones as $zone) { ?>
									<option value="<?php echo $zone->ID; ?>"><?php echo $zone->CITY . " | " . $zone->CP; ?></option>
								<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-3 col-xs-12 no-padding">
							<button type="submit" class="btn btn-primary arial btn-search">Buscar</button>
						</div>
					</form>
					<div class="separator1"></div>
					<div class="" id="error-search-zone">Debe seleccionar una ciudad o zona para poder continuar.</div>
					
				</div>
				<div class="separator1"></div>
				<div class="col-sm-6 col-sm-offset-3">
					<h3 class="arial textShadow"><?php echo $article->tA; ?></h3>
					<div class="separator10"></div>
					<a class="btn btn-primary arialBold btn-search" href="<?php echo DOMAIN.$article->slug; ?>" title="<?php echo $article->tA; ?>" alt="<?php echo $article->tA; ?>">
						VER VIDEO
					</a>
				</div>
			</div>
		</div>
	</div>
</header>
