<?php require_once("template/modules/supplier/header_supplier_product.php"); ?>
	<div class='separator5 bgYellow'>&nbsp;</div>
	<!-- Start article section -->
	<div class="container-fluid">
		<section id="ree-product">
			<div class="container">
				<div class="row">
					<div class="product-area">
					<?php if($supView->STATUS != 0 && $proView->STATUS == 1) { ?>
						
						<div class="product-tpl col-sm-8 col-xs-12">
							<article class="single-from-product">
								<div class="title-product">
									<h2 class="arial green"><?php echo $proView->TITLE; ?></h2>
								</div>
								<div class="list-category-product">
									<h5 class="textBox grayNormal"><em>
									<?php 
										for($i=0;$i<count($catPro);$i++) { 
											echo $catPro[$i]->TITLE;
											if($i < count($catPro)-1) {
												echo " • ";
											}
										} 
									?></em>
									</h5>
								</div>
								<div class="list-icon-product">
									<ul>	
										<?php 
											for($i=0;$i<count($icons);$i++) { 
												echo "<li><img class='img-responsive' src='".DOMAIN."files/product/icon/1-".$icons[$i]->ICON."' alt='".$icons[$i]->TITLE."' title='".$icons[$i]->TITLE."' /></li>";
											} 
										?>
									</ul>
								</div>	
							<?php if(trim($proView->TEXT) != "" && trim($proView->TEXT) != "<div> </div>") { ?>
								<div class="separator20">&nbsp;</div>
								<div class="text-product">
									<h4 class="textBoxBold grayNormal">Descripción</h4>
									<h5 class="textBox grayNormal"><?php echo $proView->TEXT; ?></h5>
								</div>
							<?php } ?>
								
							<?php if(count($images) > 0) { ?>
								<div class="separator20">&nbsp;</div>
								<div id="product<?php echo $proView->ID; ?>" class="carousel slide bgWhite" data-ride="carousel">
									<!-- Indicators -->
									<ol class="carousel-indicators">
									<?php for($i=0;$i<count($images);$i++) { ?>
										<li data-target="#product<?php echo $proView->ID; ?>" data-slide-to="<?php echo $i; ?>"<?php if($i == 0){ ?> class="active"<?php } ?>></li>
									<?php } ?>
									</ol>

									<!-- Wrapper for slides -->
									<div class="carousel-inner">
										<?php for($i=0;$i<count($images);$i++) { ?>
											<div class="item<?php if($i == 0){ ?> active<?php } ?>">
												<img src="<?php echo DOMAIN; ?>files/product/thumb/1-<?php echo $images[$i]->URL; ?>" alt="<?php echo $images[$i]->TITLE; ?>">
											</div>
										<?php } ?>
									</div>

									<!-- Left and right controls -->
									<a class="left carousel-control" href="#product<?php echo $proView->ID; ?>" data-slide="prev">
										<span class="glyphicon glyphicon-chevron-left"></span>
										<span class="sr-only">Previous</span>
									</a>
									<a class="right carousel-control" href="#product<?php echo $proView->ID; ?>" data-slide="next">
										<span class="glyphicon glyphicon-chevron-right"></span>
										<span class="sr-only">Next</span>
									</a>
								</div>
							<?php } ?>
								<div class="separator50">&nbsp;</div>
							</article>  
						</div>  
						<div id="product-right" class="filter-supplier col-sm-4 col-xs-12 bgGrayLight">
							<?php require("template/modules/product/column.left.product.php"); ?>
						</div>
					</div>      
					<?php }else{ ?>
						<div class="page-not-found">
							<h3 class="danger arial" ><i class="fa fa-exclamation-triangle"></i> Producto no disponible en estos momentos</h3>
							<div class="separator50">&nbsp;</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</section>
	</div>

