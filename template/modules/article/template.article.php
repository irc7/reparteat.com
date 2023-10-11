<header id="<?php echo $view; ?>" class="intro-header">
	<div class="header-content">
		<div class="header-content-inner">
			<h1 class="arial" id=""><?php echo $view_article->tA; ?></h1>
		</div>
	</div>
</header>
<div class="separator5 bgYellow"></div>
<!-- Start article section -->
<section id="retarteat-article">
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="article-area">
					<div class="row">
						<div class="article-left article-details">
						<!-- Start single article post -->
							<article class="single-from-article ">
								<?php if(trim($view_article->sbA) != ""): ?>
									<div class="subtitle-article">
										<h3 class="arial grayNormal"><?php echo trim($view_article->sbA); ?></h3>
									</div>
								<?php endif; ?>
								<div class="intro-article">
								
								<?php if(trim($view_article->iA) != ""): ?>
									<h3 class="intro-area textBox grayStrong"><?php echo trim($view_article->iA); ?></h3>
								<?php endif; ?>
								</div>
							<?php while($paragraph = mysqli_fetch_object($result_p)): 
									$align = $paragraph->ALIGN;
									if ($align=="right"){$classPos="col-md-6 col-sm-12 col-xs-12"; $classImgPos="sfb-right";}
									if ($align=="left"){$classPos="col-md-6 col-sm-12 col-xs-12"; $classImgPos="sfb-left"; }
									if ($align=="center"){$classPos="col-md-12 col-sm-12 col-xs-12"; $classImgPos="sfb-center";}  
?>
								<div class="article-details-content">
									<div class="col-md-12 col-sm-12 col-xs-12 no-padding">
										<h4 class="arial grayStrong"><?php echo stripslashes($paragraph->TITLE); ?></h4>
									</div>
									<div class="separator5"></div>
								<?php if($paragraph->IMAGE != "" || $paragraph->VIDEO != "" ||  $paragraph->GALLERY > 0): ?>
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
						<?php 
							if($view_article->idS == 8) {
								require_once("template/modules/atencion/atencion.php"); 
							}
						?>
					</div>
				</div>
			</div>       
		</div>
	</div>
</section>

