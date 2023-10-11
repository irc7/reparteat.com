<?php	
		$qA = "select * from ".preBD."images where IDGALLERY = " . $paragraph->IDALBUM;
		$qA .= " and STATUS = 1 order by POSITION";
		
		$resultGallery = checkingQuery($connectBD,$qA);

        $qt = "select * from ".preBD."images_gallery where ID = " . $paragraph->IDALBUM;
	
		
		$resultGalleryt = checkingQuery($connectBD,$qt);
        $imagest = mysqli_fetch_object($resultGalleryt);   

?> 

               
	<div class="single-widget">
		<h2 class="titleBold grayNormal"><?php echo $imagest->TITLE;?></h2>
		<div class="gallery-article owl-carousel owl-theme">
	<?php while($images = mysqli_fetch_object($resultGallery)): ?>
			<div class="item">
				<a href="<?php echo DOMAIN; ?>files/gallery/image/<?php echo $images->URL; ?>" class="lytebox" data-title="<?php echo $images->TITLE; ?>" data-lyte-options="slide:false group:gallery<?php echo $paragraph->ID; ?>">
				<div class="thumbnail">
					<img class="img-responsive transition" src="<?php echo DOMAIN; ?>files/gallery/thumb/<?php echo $images->URL; ?>" alt="<?php echo $images->TITLE; ?>">
					<h4 class="grayStrong title transition"><?php echo $images->TITLE; ?></h4>
				</div>
				</a>
			</div>
	<?php endwhile; ?>   
		</div>
		
	</div>

<?php if($paragraph->FOOT != "" && $paragraph->FOOT != null): ?>	
	<div class="foot-gallery bgOrange white textBox">
		<div><?php echo stripslashes($paragraph->FOOT); ?></div>
	</div>
<?php endif; ?>