<?php require_once("template/modules/supplier/header_supplier.php"); ?>
<div class='separator5 bgYellow'>&nbsp;</div>
<!-- Start article section -->
<div class="container-fluid">
	<section id="ree-supplier">
		<div class="container">
			<div class="row">
				<?php 
					$pubObj = new Publicity();

					if($publicity = $pubObj->publicityByHookZone('list-supplier', $_SESSION[sha1("zone")])) {
						include("template/modules/publicity/publicity.tpl.php");	
					} 
				?>
				<div class="supplier-area">
					<?php /*
					<div class="filter-supplier col-sm-3 col-xs-12 bgGrayLight">
						require("template/modules/supplier/column.left.php"); 
					</div>
					<div class="list-supplier col-sm-9 col-xs-12">
					*/	
					?>
					<div class="list-supplier col-sm-12 col-xs-12">
				<?php 
				if(count($supplierList) >0) {
					foreach($supplierList as $sup){ 
						if($sup->ID == 3 && isset($_SESSION[PDCLOG]) || $sup->ID != 3) {
							$timeSup = $supObj->checkingOpen($sup->ID,$idZone);
							$catSup = $supObj->infoCategories($sup->ID);
							if($sup->STATUS == 1) {
								if($timeSup["status"] == 1) {
									$textTime = "Pide ahora";
									$class="bgGreen";
									$border = "green";
									$icon = "fa-opencart";
								} else {
									if($timeSup["time"] == null) {
										$textTime = "No disponible";
										$class="bgDanger";
										$border = "danger";
										$icon = "fa-lock";
									}else {
										$textTime = "Pide a las " . $timeSup["time"]->START_H .":";
										if(strlen($timeSup["time"]->START_M) == 1) {
											$textTime .= "0";
										}
										$textTime .= $timeSup["time"]->START_M;
										$class="bgYellow";
										$border = "yellow";
										$icon = "fa-lock";
									}
								}
							}else{
								$textTime = "No disponible";
								$class="bgDanger";
								$border = "danger";
								$icon = "fa-lock";
							}
					?>
						<div class="col-md-4 col-sm-6 col-xs-12 item-supplier wow fadeInUp  animated"  data-wow-duration="2s" style="visibility: visible; animation-duration: 1s; animation-name: fadeInUp;">
						<a href="<?php echo DOMAIN.$slug."/".$sup->SLUG; ?>">
							<article class="single-from-supplier-new transition" style="border:1px solid var(--<?php echo $border; ?>);">

								<div class="supplier-content-new transition">
									<img class="img-responsive img-dest-supplier transitionVerySlow" src="<?php echo DOMAIN; ?>files/supplier/thumb/1-<?php echo $sup->IMAGE; ?>" />
									
									<div class="time-control-supplier-new white transition <?php echo $class; ?>">
										<i class="fa <?php echo $icon; ?>" aria-hidden="true"></i>
										<span class="textBoxBold"><?php echo $textTime; ?></span>
									</div>
									<img class="img-responsive img-logo-supplier shadow transition" src="<?php echo DOMAIN; ?>files/supplier/thumb/1-<?php echo $sup->LOGO; ?>" />
									<div class="supplier-title-new transition">
										<h3 class="arial green"><?php echo $sup->TITLE; ?></h3>
									</div>
								</div>

							</article>
						</a>
						<div class="separator50">&nbsp;</div>
						</div>
					<?php 
							}
						} 
					}else{ 
						?>
							<div class="page-not-found">
								<h3 class="danger arial" ><i class="fa fa-exclamation-triangle"></i> No hay restaurantes disponibles ahora mismo para <?php echo $zoneAct->CITY."(".$zoneAct->CP.")"; ?>.</h3>
								<div class="separator10">&nbsp;</div>
								<?php if($zoneAct->ID == 10){ ?>
									<h3 class="white bgGrayStrong" style="padding:10px 15px;"><i class="fa fa-info" style="margin-right:20px;"></i>DISPONIBLE O ACTIVOS A PARTIR DEL 12 DE OCTUBRE.</h3>
								<?php } ?>
								<div class="separator50">&nbsp;</div>
								<div class="separator50">&nbsp;</div>
							</div>
			<?php 	} ?>
					</div>  
				</div>      
			</div>
		</div>
	</section>
</div>

