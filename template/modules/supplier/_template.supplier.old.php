<?php require_once("template/modules/supplier/header_supplier.php"); ?>
<div class='separator5 bgYellow'>&nbsp;</div>
<!-- Start article section -->
<div class="container-fluid">
	<section id="ree-supplier">
		<div class="container">
			<div class="row">
				<div class="supplier-area">
					<?php /*
					<div class="filter-supplier col-sm-3 col-xs-12 bgGrayLight">
						require("template/modules/supplier/column.left.php"); 
					</div>
					<div class="list-supplier col-sm-9 col-xs-12">
					*/	
					?>
					<div class="list-supplier col-sm-12 col-xs-12">
					<?php foreach($supplierList as $sup){ 
						if($sup->ID == 3 && isset($_SESSION[PDCLOG]) || $sup->ID != 3) {
							$timeSup = $supObj->checkingOpen($sup->ID,$idZone);
							$catSup = $supObj->infoCategories($sup->ID);
					?>
						<div class="col-md-4 col-sm-6 col-xs-12 item-supplier wow fadeInUp  animated"  data-wow-duration="2s" style="visibility: visible; animation-duration: 1s; animation-name: fadeInUp;">
						<a href="<?php echo DOMAIN.$slug."/".$sup->SLUG; ?>">
							<article class="single-from-supplier">

								<div class="supplier-thumb">
									<img class="img-responsive img-dest-supplier" src="<?php echo DOMAIN; ?>files/supplier/thumb/1-<?php echo $sup->IMAGE; ?>" />
									<img class="img-responsive img-logo-supplier shadow" src="<?php echo DOMAIN; ?>files/supplier/thumb/1-<?php echo $sup->LOGO; ?>" />
								</div>

								<div class="supplier-title">
									<h3 class="arial green"><?php echo $sup->TITLE; ?></h3>
								</div>
								<div class="supplier-cats graySemiLight">
									<?php  
										for($i=0;$i<count($catSup);$i++) { 
											echo $catSup[$i]->TITLE;
											if($i < count($catSup)-1) {
												echo " • ";
											}
										} 
									?>
								</div>
								<div class="time-control-supplier">
									<!--<i class="fa fa-clock-o" aria-hidden="true"></i>-->
									<img class="img-responsive icon-list-supplier" src="<?php echo DOMAIN; ?>template/images/icon-supplier.png" />
									<?php 
										if($sup->STATUS == 1) {
											if($timeSup["status"] == 1) {
												$textTime = "Disponible para pedidos";
												$class="green";
											} else {
												if($timeSup["time"] == null) {
													$textTime = "No disponible";
													$class="danger";
												}else {
													$textTime = "Disponible a partir de las " . $timeSup["time"]->START_H .":";
													if(strlen($timeSup["time"]->START_M) == 1) {
														$textTime .= "0";
													}
													$textTime .= $timeSup["time"]->START_M;
													$class="yellow";
												}
											}
										}else{
											$textTime = "No disponible";
											$class="danger";
										}
										echo '<span class="textBoxBold '.$class.'">'.$textTime.'</span>'; 
									?>
								</div>
							</article>
						</a>
						<div class="separator15">&nbsp;</div>
						</div>
					<?php 
							}
						} ?>
					</div>  
				</div>      
			</div>
		</div>
	</section>
</div>

