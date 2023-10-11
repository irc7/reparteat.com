
          <!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Resumen de pedidos</h1>
<p class="mb-4"></p>

<!-- DataTales Example -->
<div class="card shadow mb-4">
	<div class="card-body">
		<div class="card-body-sumary-delivery">
			
		<?php if(is_array($orders)) {
				foreach($orders as $ordersZone) {  
					if($ordersZone["view"]) {
						
					
		?>
					
						<h3><?php echo $ordersZone["info"]->CP . " - " . $ordersZone["info"]->CITY; ?></h3>
						
						
						
			<?php			
						foreach($ordersZone["items"] as $item) {  
							if(count($item["orders"])) {
				?>
								<div class="mb-4 wrap-list-delivery-1">
									<div class="wrap-sumary-delivery-title textBoxBold white bgGreen">
										Franja horaria: <?php echo $item["start"]->format("H:i"); ?> - <?php echo $item["finish"]->format("H:i"); ?>
									</div>
									
									<?php foreach($item["orders"] as $ord) {  
										$supplier = $supObj->infoSupplierById($ord->IDSUPPLIER);
										$status = $orderObj->infoStatusOrder($ord->STATUS);
									?>
										<a class="transition" href="<?php echo DOMAINZP; ?>?view=order&mod=order&ref=<?php echo $ord->REF; ?>" title="Ir al pedido <?php echo $ord->REF; ?>">
											<div class="wrap-sumary-delivery transition">
												<div class="wrap-sumary-delivery-ref textBoxBold grayStrong">
													<?php echo $ord->REF; ?>
												</div>
												<div class="wrap-sumary-delivery-supplier textBoxBold grayStrong">
													<?php echo $supplier->TITLE; ?>
												</div>
												<div class="wrap-sumary-delivery-status textBoxBold <?php echo $status->COLOR; ?>">
													<i class="fa fa-<?php echo $status->ICON . " " . $status->COLOR; ?>" title="<?php echo $status->TITLE; ?>"></i> <?php echo $status->TITLE; ?>
												</div>
											</div>
										</a>
										<div class="separator1 bgGrayLight"></div>		
										<div class="separator10"></div>		
									<?php } ?>
								</div>
								<div class="separator1"></div>
				<?php 		}
						}
					}
				}
			}
		?>				
		</div>
	</div>
</div>

       

