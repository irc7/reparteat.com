<?php
	
	if($view == "article" || $view == "blog"){ 
			if($view == "article") {
				$urlShare = DOMAIN.$row_title_seo->slug;	
			}elseif($view == "blog") {
				$urlShare = DOMAIN.slugBlog.$row_title_seo->slug;
			}
	?>

		<?php if($row_title_seo->THUMBNAIL != ""){ 
				$img_youtube = substr($row_title_seo->THUMBNAIL, 0, 2);
				$url_youtube = substr($row_title_seo->THUMBNAIL, 2, strlen($row_title_seo->THUMBNAIL));	
				if($img_youtube == "v="){
					$urlThumbSEO = "http://img.youtube.com/vi/".$url_youtube."/0.jpg";
				}else{
					$urlThumbSEO = DOMAIN."files/articles/thumb/".$row_title_seo->THUMBNAIL; 						
				}
			}else{
				$urlThumbSEO = DOMAIN."files/articles/thumb/default.jpg"; 
			}	
		
			$row_title_seo->SUMARY=str_replace('"','',$row_title_seo->SUMARY);
			if($row_title_seo->SUMARY != ""){
				$descriptionWEB = strip_tags(stripslashes($row_title_seo->SUMARY));
			}else{
				$descriptionWEB = calculateResume($id, 247);
			} 
		?>
	
		
			<!-- Twitter Card data -->
			<meta name="twitter:card" content="summary_large_image">
			<meta name="twitter:site" content="@">
			<meta name="twitter:title" content="<?php echo $titleWEB; ?>">
			<meta name="twitter:description" content="<?php echo cutting($descriptionWEB, 200); ?>">
			<meta name="twitter:image:src" content="<?php echo $urlThumbSEO; ?>">

			<!-- Open Graph data -->
			<meta property="og:title" content="<?php echo $titleWEB; ?>">
			<meta property="og:type" content="article" />
			<meta property="og:url" content="<?php echo $urlShare; ?>"> 
			<meta property="og:image" content="<?php echo $urlThumbSEO; ?>">
			<meta property="og:description" content="<?php echo $descriptionWEB; ?>" />
			<meta property="og:site_name" content="<?php echo TITLEWEB; ?>" />
			
		
<?php 	} elseif($view == "home"){ ?>
			<meta name="twitter:card" content="summary_large_image">
			<meta name="twitter:site" content="@">
			<meta name="twitter:title" content="<?php echo TITLEWEB; ?>">
			<meta name="twitter:description" content="<?php echo stripslashes($row_meta2["TEXT"]); ?>">
			<meta name="twitter:image:src" content="<?php echo DOMAIN; ?>template/images/img-rrss-default.jpg">
			
			<meta property="og:image" content="<?php echo DOMAIN; ?>template/images/img-rrss-default.jpg">
			<meta property="og:url" content="<?php echo DOMAIN; ?>"> 
			<meta property="og:description" content="<?php echo stripslashes($row_meta2["TEXT"]); ?>">			
			<meta property="og:image:type" content="image/jpg" /> 
			<meta property="og:title" content="<?php echo $titleWEB; ?>">

<?php 	} ?>