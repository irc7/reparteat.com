
<?php if(count($orders) > 0) { ?>
         <!-- Page Heading -->
	<h1 class="h3 mb-2 green arial">Listado de pedidos</h1>
	<p class="mb-4"></p>
<?php }else{ ?>
	<h1 class="h3 mb-2 orange textCenter arial"><i class="fa fa-info-circle orange"></i>  No tienes ningún pedido pendiente</h1>
	<p class="mb-4"></p>
<?php } ?>
	<div class="follow-order">
	<?php 
		foreach($orders as $item) { 
			$supplier = $supObj->infoSupplierById($item->IDSUPPLIER);
	?>
		<div class="">
			<div class="col-sm-6 col-xs-12">
				<h4 class="h5 mb-2 text-gray-800">
					Restaurante: <?php echo $supplier->TITLE; ?>
				</h4>
				<h4 class="h5 mb-2 text-gray-800">
					Referencia: <?php echo $item->REF; ?>
				</h4>
			</div>
			<div class="col-sm-6 col-xs-12">
				<h5 class="green arial textRight">
				<?php
					if($order->STATUS < 6) {
						echo $orderObj->franjaSend($item);
					}
				?>
				</h5>
			</div>
			<div class="separator15"></div>
			<div class="row">
				<div class="col-sm-1 col-xs-1"></div>
				<?php for($i=2;$i<6;$i++) { 
						$class = "";
						if($item->STATUS > $statusList[$i]->ID) {
							$class = "completed";
						}else if($item->STATUS == $statusList[$i]->ID) {
							$class = $statusList[$i]->COLOR;
						} else {
							$class = "disabled grayNormal";
						}
				?>
					<div class="col-sm-2 col-xs-2 follow-box no-padding<?php if($i==2){echo " textLeft";}else{echo " textCenter";} ?>">
						<i class="fa fa-<?php echo $statusList[$i]->ICON; ?> icon-follow <?php echo $class; ?>"></i>
						<br/>
						<?php if($i==2){ ?>
						<div class="separator10 bgLeft"></div>
						<?php }else{ ?>
 						<div class="separator10 bgCenter"></div>
						<?php } ?>
						<div class="separator5 bgGreen"></div>
						<span class="arial <?php echo $class; ?>"><?php echo $statusList[$i]->TITLE; ?></span>
					</div>
				<?php } ?>
					<div class="col-sm-2 col-xs-2 follow-box no-padding textRight">
					<?php if($item->STATUS > 6) { ?>
						<i class="fa fa-<?php echo $statusList[$item->ID]->ICON; ?> icon-follow <?php echo $statusList[$item->ID]->COLOR; ?>"></i>
						<br/>
						<div class="separator10 bgRight"></div>
						<div class="separator5 bgGreen"></div>
						<span class="arial <?php echo $statusList[$item->ID]->COLOR; ?>"><?php echo $statusList[6]->TITLE; ?></span>
					<?php }else { ?>
						<i class="fa fa-<?php echo $statusList[6]->ICON; ?> icon-follow disabled grayNormal"></i>
						<br/>
						
						<div class="separator10 bgRight"></div>
						<div class="separator5 bgGreen"></div>
						<span class="arial grayNormal"><?php echo $statusList[6]->TITLE; ?></span>
					<?php } ?>
					</div>
				<div class="col-sm-1 col-xs-1"></div>
			</div>
		</div>
		<div class="separator20"></div>
		<div class="separator1 bgYellow"></div>
		<div class="separator15"></div>
		
	<?php 
		} 
	?>	
	</div>

       

