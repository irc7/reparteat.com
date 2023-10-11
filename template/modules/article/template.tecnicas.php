<section id="wrap-section">
	<div class="header-content text-center">
		<img class="img-responsive header-te-img" src="<?php echo DOMAIN; ?>files/section/image/<?php echo $sectionBD->THUMBNAIL; ?>" />
	</div>
	<div class="separator5 bgOrange">&nbsp;</div>
	<div class="title-block-home titleBold title-article">
		<h1>
			<?php $titleSection = explode(" ", $sectionBD->TITLE); ?>
			<span class="grayStrong"><?php echo $titleSection[0]; ?></span> <span class="orange"><?php echo $titleSection[1]; ?></span>
		</h1>
	</div>	
	<div id="content-article-list">
		<div class="container">	
			<div class="row">
	
<?php 			$i = 0;
				while($art = mysqli_fetch_object($r)) { 
					$effect = "flipInX";
?>
				<article class="wrap-special-tec">
					<a class="link-sp-tec-list transition" href="<?php echo DOMAIN; ?><?php echo $art->slug; ?>" alt="<?php echo $art->tsA; ?>" title="<?php echo $art->tsA; ?>">
						<div class="item-tecnicas col-md-4 col-sm-6 wow <?php echo $effect; ?> animated" data-wow-duration="1s" style="visibility: hidden;">
							<div class="wrap-sp-tec col-md-12 no-padding transitionSlow">
								<div class="col-md-12 no-padding wrap-img-sp-tec">
									<img class="img-responsive transition" src="<?php echo DOMAIN; ?>files/articles/thumb/<?php echo $art->image; ?>" alt="<?php echo $art->tsA; ?>" />
								</div>
								<div class="wrap-info-sp-tec col-md-12 no-padding transitionSlow">
									<div class="info-sp-tec transitionSlow">
										<div class="title-sp-tec col-md-12 no-padding">
											<h3 class="grayStrong title transition"><?php echo $art->tA; ?></h3>
										</div>
									</div>
									<center>
										<img class="read-more-sp-tec transitionSlow" src="<?php echo DOMAIN; ?>template/images/leermas-blog-home.png" />
									</center>
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