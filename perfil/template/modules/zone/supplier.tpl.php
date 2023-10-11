
          <!-- Page Heading -->
<div class="row">	
	<div class="col-sm-6 col-xs-12">
		<h1 class="h3 mb-2 text-gray-800">Restaurantes - <?php echo $zone->CP ; ?> - <em><?php echo $zone->CITY; ?></em></h1>
	</div>
	<div class="col-sm-6 col-xs-12">
		<div class="separator20"></div>	
		<a href="<?php echo DOMAINZP; ?>?view=supplier&mod=supplier&tpl=create&z=<?php echo $idZone; ?>" title="Nuevo proveedor">
			<button type="button" class="btn btn-primary floatRight transition btn-new-product"><i class="fa fa-plus"></i></button>
		</a>
	</div>
</div>
<p class="mb-4"></p>
<div class="separator"></div>

<!-- DataTales Example -->
<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary">Todos los productos</h6>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>#</th>
						<th>NOMBRE</th>
						<th>TELÉFONO</th>
						<th>PRODUCTOS</th>
						<th>ESTADO</th>
						<th>DETALLES</th>
						<th></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th class="sorting_desc">#</th>
						<th>NOMBRE</th>
						<th>TELÉFONO</th>
						<th>PRODUCTOS</th>
						<th>ESTADO</th>
						<th>DETALLES</th>
						<th></th>
					</tr>
				</tfoot>
				<tbody> 
				<?php if(is_array($list)) {
						foreach($list as $item) { 
							$imgObj = new Image();
							$imgObj->path = "supplier";
							$imgObj->pathoriginal = "original";
							$imgObj->paththumb = "logo";
							$thumb = trim($item->LOGO);
							
							if($thumb != "") {
								$urlThumb = DOMAIN.$imgObj->dirbasename.$imgObj->path."/".$imgObj->paththumb."/".$thumb;
							}else {
								$urlThumb = DOMAINZP."/template/img/sin_imagen.jpg";
							}
				?>
							<tr>
								<td><img src='<?php echo $urlThumb; ?>' style='border:none;max-height:50px;'/></td>
								<td><?php echo $item->TITLE; ?></td>
								<td><?php if($item->MOVIL != ""){echo $item->MOVIL.'<br/>';} ?><?php echo $item->PHONE; ?></td>
								<td>								
									<?php 
										$products = $proObj->listProductBySupplier($item->ID); 
										$totalProduct = count($products);
									?>
									<a href="<?php echo DOMAINZP; ?>?view=product&mod=product&tpl=list&sup=<?php echo $item->ID; ?>" >
										<i class="fa fa-clipboard-list"></i> Listado de productos (<?php echo $totalProduct; ?>)
									</a>
								</td>
								<td class="textCenter">
									<?php 	if($item->STATUS == 1){ ?>
												<i class="fa fa-check-circle green" title="Publicado"></i>
									<?php 	}else if($item->STATUS == 2){ ?>
												<i class="fa fa-minus-circle orange" title="No disponible"></i>
									<?php 	} else { ?>
												<i class="fa fa-eye-slash grayNormal" title="Despublicado"></i>
									<?php 	}	?>
								</td>
								<td class="textCenter">
									<a href="<?php echo DOMAINZP; ?>?view=supplier&mod=supplier&tpl=profile&sup=<?php echo $item->ID; ?>" >
										<i class="fa fa-edit"></i>
									</a>
								</td>
								<td class="textRight">
									<i class="fa fa-trash grayLight" title="No tiene permisos para realizar esta opciónn"></i>
								</td>
							</tr>
				<?php 	}
					}
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>

       

