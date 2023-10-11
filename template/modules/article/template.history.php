<div class="header-content text-center header-history">
	<img class="img-responsive header-history" src="<?php echo DOMAIN; ?>files/section/image/<?php echo $view_article->imageS; ?>" />
</div>
<div class="separator5 bgOrange">&nbsp;</div>
<div class="title-block-home titleBold title-article">
	<h1>
	<?php $title = explode(" ", $view_article->tA, 2); ?>
		<span class="grayStrong"><?php echo $title[0]; ?></span> <span class="orange"><?php echo $title[1]; ?></span>
	</h1>
</div>
<!-- History Article -->
	<link href="<?php echo DOMAIN; ?>template/css/article.history.css" rel="stylesheet">
    <section id="history">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="timeline">
                    <?php $cont = 0;
						while($cont < ($totalParagraphs-1)): 
							$part = mysqli_fetch_object($result_p);
					?>
								<li<?php if($cont % 2 == 0){echo ' class="timeline-inverted"';} ?>>
									<div class="timeline-image bgOrange">
										<img class="img-circle img-responsive" src="<?php echo DOMAIN; ?>files/articles/thumb/<?php echo $part->IMAGE; ?>" alt="">
									</div>
									<div class="timeline-panel">
										<div class="timeline-heading">
											<h3 class="titleBold grayStrong"><?php echo $part->TITLE; ?></h3>
											<!--<h4 class="subheading">Our Humble Beginnings</h4>-->
										</div>
										<div class="timeline-body">
											<h5 class="text-muted grayNormal textBox"><?php echo $part->TEXT; ?></h5>
										</div>
									</div>
								</li>
					<?php	
							$cont++; 
						endwhile; 
/*
                        <li class="timeline-inverted">
                            <div class="timeline-image">
                                <h4>Be Part
                                    <br>Of Our
                                    <br>Story!</h4>
                            </div>
                        </li>
*/ ?>
                    </ul>
					<?php $part = mysqli_fetch_object($result_p); ?>	
					<div class="history-bottom">
						<h4 class="textBox grayNormal"><?php echo $part->TEXT; ?></h4>
					</div>
                </div>
            </div>
        </div>
    </section>