<div id="header-content-section">
	<div id="box-hilos" class="backgroundGreenB subtitleBox">
		<div id="hilos">
			<a href="<?php echo DOMAIN; ?>" title="Inicio" alt="Inicio">
				Inicio
			</a>
			&nbsp;>&nbsp;
			<?php echo $view_article->tS; ?>
		</div>
	</div>
	<div id="title-section-content" style="background-image:url(<?php echo DOMAIN; ?>files/section/image/<?php echo $view_article->imageS;; ?>);">
		<div id="title-section" class="titleBox"><?php echo $view_article->tA; ?></div>
	</div>
	<div id="title-content" style="display:none;">
		<h1 class="content_page_title titleBox greenB">
			<?php echo $view_article->tA; ?>
		</h1>
	</div>
</div>
<div id="page-content">
    <div id="wrapper-article">
			<?php if($view_article->sbA != ""): ?>
                <div class="article-subtitle">
                    <?php echo stripslashes($view_article->sbA); ?>
                </div>
            <?php endif; ?>
            <?php if($view_article->iA != ""): ?>
                <div class="article-intro">
                    <?php echo stripslashes(nl2br($view_article->iA)); ?>
                </div>
            <?php endif; ?>
           
           <?php while($paragraph = mysqli_fetch_object($result_p)): 
					//pre($paragraph);
		   ?>
                <div class="box-paragraph paragraph-<?php echo $paragraph->TYPE; ?>">
                	<h2 id="title-block<?php echo $paragraph->POSITION; ?>" class="titleBlock greenB" <?php if($paragraph->TITLE == ""){echo "style='display:none;'";} ?>>
                        <?php echo stripcslashes($paragraph->TITLE); ?>
                    </h2>
                    <div id="text_block<?php echo $paragraph->POSITION; ?>" class="text-block">
                    <?php 
                        if($paragraph->ALIGN == "center"){
                            $width_box = $view_article->size_C;
                            $float_box = "none";
                            $margin = "margin:1% auto;";
							$styleAux = "text-align: center;";
                        } else {
                            $width_box = $view_article->size_LR;
                            $styleAux = "";
                            $float_box = $paragraph->ALIGN;
							if($float_box == "left") {
								$margin = "margin:0% 1% 1% 0%;";
							}else{
								$margin = "margin:0% 0% 1% 1%;";
							}
                        }
                        include("template/modules/article/paragraph.".$paragraph->TYPE.".php"); 
                    ?>
                    
                        <?php echo str_replace("http://redaccion2/cofhuelva.org/", DOMAIN, stripcslashes($paragraph->TEXT)); ?>
                    </div> 
					<br />
					<?php include("template/modules/article/paragraph.download.php"); ?>
                </div>
            <?php endwhile;?>        
              	
    </div>
</div>
