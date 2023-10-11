<section id="wrap-section">
	<div class="header-content text-center">
		<img class="img-responsive header-news-img" src="<?php echo DOMAIN; ?>files/section/image/<?php echo $sectionBD->THUMBNAIL; ?>" />
	</div>
	<div class="separator5 bgOrange">&nbsp;</div>
	<div class="title-block-home titleBold title-article">
		<h1>
			<?php $titleSection = explode(" ", $sectionBD->TITLE); ?>
			<span class="grayStrong"><?php echo $titleSection[0]; ?></span> <span class="orange"><?php echo $titleSection[1]; ?></span>
		</h1>
	</div>
<?php if($section == 7) { ?>
	<div id="mnu-actualidad">
		<div class="container">
			<div class="row">
				<ul>
					<li<?php if($filter == "datestart"){echo ' class="bgOrange"';} ?>>
						<a href="<?php echo DOMAIN.$sectionBD->SLUG; ?>">
							<img src="<?php echo DOMAIN; ?>template/images/more-news-<?php if($filter == "datestart"){echo 'white';}else{echo 'orange';} ?>.png" />
							<span class="transition <?php if($filter == "datestart"){echo 'white';}else{echo 'orange';} ?> textBox">ÚLTIMAS</span>
						</a>
					</li>
					<li<?php if($filter == "statistics"){echo ' class="bgOrange"';} ?>>
						<a class="transition" href="<?php echo DOMAIN.$sectionBD->SLUG; ?>/mas-visitadas">
							<img class="transition" src="<?php echo DOMAIN; ?>template/images/more-news-<?php if($filter == "statistics"){echo 'white';}else{echo 'orange';} ?>.png" />
							<span class="transition <?php if($filter == "statistics"){echo 'white';}else{echo 'orange';} ?> textBox">VISITADAS</span>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="separator5 bgOrange">&nbsp;</div>
<?php } ?>	
	<div id="content-article-list">
		<div class="container">	
			<div class="row">
	
<?php 			$i = 0;
				while($art = mysqli_fetch_object($r)) { 
					$effect = "fadeInUp";
?>
				<article>
					<a class="link-news-list transition" href="<?php echo DOMAIN; ?><?php echo $art->slug; ?>" alt="<?php echo $art->tsA; ?>" title="<?php echo $art->tsA; ?>">
						<div class="col-md-4 wow <?php echo $effect; ?> animated" data-wow-duration="1s" style="visibility: hidden;">
							<div class="wrap-news col-md-12 no-padding transitionSlow">
								<div class="col-md-12 no-padding wrap-img-news">
									<img class="img-responsive transition" src="<?php echo DOMAIN; ?>files/articles/thumb/<?php echo $art->image; ?>" alt="<?php echo $art->tsA; ?>" />
								</div>
								<div class="wrap-info-news col-md-12 no-padding bgGrayLight transitionSlow">
									<div class="info-news">
										<div class="date-news col-md-12 no-padding">
											<span class="grayNormal textBox transition">
										<?php 
											$newsDate = new DateTime($art->dateA); 
											echo $days[($newsDate->format("N") - 1)].", ". $newsDate->format("j"). " de " . $months[($newsDate->format("n") - 1)] . " de " . $newsDate->format("Y");
										?>
											</span>
										</div>
										<div class="title-news col-md-12 no-padding">
											<h3 class="grayStrong title transition"><?php echo stripslashes($art->tA); ?></h3>
										</div>
										<div class="text-news col-md-12 no-padding">
											<h5 class="grayNormal textBox transition"><?php echo stripslashes(cutting($art->resA, 200)); ?></h5>
										</div>
									</div>
									<div class="read-more-absolute">
										<span class="titleLight orange transition">LEER</span>&nbsp;<img class="img-read-more" src="<?php echo DOMAIN; ?>template/images/leer-mas.png" />
									</div>
								</div>
							</div>
						</div>
					</a>
				</article>
				
<?php 				$i++;
				} ?>
				<div class="separator20">&nbsp;</div>
<?php 		if($num_pages > 1) {
				require_once("template/modules/article/pagination.php");
}
?>
			</div>
		</div>
	</div>
</section>