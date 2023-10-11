<div class="header-content text-center">
	<img class="img-responsive header-news-img" src="<?php echo DOMAIN; ?>files/section/image/<?php echo $view_article->imageS; ?>" />
</div>
<div class="separator5 bgOrange">&nbsp;</div>
<!-- Start article section -->
<section id="ihp-article">
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="article-area">
					<div class="row">
						<div class="article-left article-details">
						<!-- Start single article post -->
							<article id="header-article-news">
								<div class="col-md-6 col-sm-6 col-xs-6 no-padding">
									<div class="section-post titleBold grayNormal">
										<?php $titleSection = explode(" ", $view_article->tS, 2); ?>
										<h2>
										<span class="grayStrong"><?php echo utf8_encode(strtoupper(utf8_decode($titleSection[0]))); ?></span>&nbsp;<span class="orange"><?php echo utf8_encode(strtoupper(utf8_decode($titleSection[1]))); ?></span>
										</h2>
									</div>
									<div class="date-new titleLight grayStrong">
										<?php echo $daysNews[$dateArt->format("w")].", " . $dateArt->format("d") . " de " . $months[intval($dateArt->format("m"))-1] . " de " . $dateArt->format("Y"); ?>
									</div>
								</div>
								<div class="col-md-6 col-sm-6 col-xs-6 no-padding">
									<div class="share-article">
									<?php require("template/modules/share/social.networks.php"); ?>
									</div>
								</div>
								<div class="separator15">&nbsp;</div>
								<div class="title-post">
									<h1 class="title grayStrong"><?php echo stripslashes($view_article->tA); ?></h1>
								</div>
								<?php /*
								<div class="separator15">&nbsp;</div>
								<div class="firm-post titleLight grayStrong">
									<em><?php echo $view_article->firm; ?></em>
								</div>
								*/ ?>
							</article>
							<article class="single-from-article article-dinamic">
								<?php if(trim($view_article->sbA) != ""): ?>
									<div class="subtitle-article">
										<h3 class="textBox grayNormal"><?php echo trim($view_article->sbA); ?></h3>
									</div>
								<?php endif; ?>
								<?php if(trim($view_article->iA) != ""): ?>
									<div class="intro-article">
										<h4 class="textBox grayNormal"><em><?php echo trim($view_article->iA); ?></em></h3>
									</div>
								<?php endif; ?>
							<?php while($paragraph = mysqli_fetch_object($result_p)): 
									$align = $paragraph->ALIGN;
									if ($align=="right"){$classPos="col-md-6 col-sm-12 col-xs-12"; $classImgPos="sfb-right";}
									if ($align=="left"){$classPos="col-md-6 col-sm-12 col-xs-12"; $classImgPos="sfb-left"; }
									if ($align=="center"){$classPos="col-md-12 col-sm-12 col-xs-12"; $classImgPos="sfb-center";}  
?>
								<div class="article-details-content">
									<div class="col-md-12 col-sm-12 col-xs-12 no-padding">
										<h5 class="title red"><?php echo stripslashes($paragraph->TITLE); ?></h5>
									</div>
									
								<?php if($paragraph->IMAGE != "" || $paragraph->VIDEO != "" ||  $paragraph->IDALBUM > 0): ?>
									<div class="<?php echo $classImgPos;?>">
										<div class="sfb-content">
										<?php include("template/modules/article/paragraph.".$paragraph->TYPE.".php"); ?>
										</div>
									</div>
								<?php endif; ?>
									<div class="textParagraph textBox grayNormal">
										<?php echo stripcslashes($paragraph->TEXT); ?>
									</div>
									<div class="<?php echo $classImgPos;?>">
									<?php include("template/modules/article/paragraph.download.php"); ?>
									</div>
								</div> 
							<?php endwhile;?> 
							</article>
						</div>      
					</div>
				</div>
			</div>       
		</div>
	</div>
</section>

