
          <!-- Page Heading -->
<div class="row">	
	<div class="col-sm-6 col-xs-12">
		<h1 class="h3 mb-2 text-gray-800"><?php echo $supBD->TITLE; ?></h1>
	</div>
	<div class="col-sm-6 col-xs-12">
		<div class="separator20"></div>	
		<a href="<?php echo DOMAINZP; ?>?view=product&mod=product&tpl=create&sup=<?php echo $idSup; ?>" title="Nuevo producto">
			<button type="button" class="btn btn-primary floatRight transition btn-new-product"><i class="fa fa-plus"></i></button>
		</a>
	</div>
</div>
<p class="mb-4"></p>
<div class="separator"></div>

<!-- DataTales Example -->
<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary">
			Todos los productos
			<a href="<?php echo DOMAINZP; ?>?view=ordenar&mod=ordenar&tpl=product&sup=<?php echo $idSup; ?>" title="Ordenar productos">
				<button type="button" class="btn btn-primary floatRight transition">Ordenar</button>
			</a>
		</h6>
	</div>
	
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>IMAGEN</th>
						<th>NOMBRE</th>
						<th>CATEGORIAS</th>
						<th>COSTE</th>
						<th>ESTADO</th>
						<th>DETALLES</th>
						<th></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th class="sorting_desc">IMAGEN</th>
						<th>NOMBRE</th>
						<th>CATEGORIAS</th>
						<th>COSTE</th>
						<th>ESTADO</th>
						<th>DETALLES</th>
						<th></th>
					</tr>
				</tfoot>
				<tbody> 
				<?php if(is_array($list)) {
						foreach($list as $item) { 
							$imgObj = new Image();
							$imgObj->path = "product";
							$imgObj->pathoriginal = "original";
							$imgObj->paththumb = "thumb";
							
							$thumb = $proObj->productImageFav($item->ID);
							if($thumb != NULL) {
								$urlThumb = DOMAIN.$imgObj->dirbasename.$imgObj->path."/".$imgObj->paththumb."/1-".$thumb->URL;
							}else {
								$urlThumb = DOMAINZP."/template/img/sin_imagen.jpg";
							}
				?>
							<tr>
								<td width="120"><img src='<?php echo $urlThumb; ?>' style='border:none;max-width:120px;'/></td>
								<td><?php echo $item->TITLE; ?></td>
								<td>
								<?php 
									$cats = $proObj->totalInfoCategories($item->ID);
									for($c=0;$c<count($cats);$c++) {
										echo "<em>".$cats[$c]->TITLE. "</em>"; 
										if($c<count($cats)-1) {
											echo ", ";
										}
									}
								?>
								</td>
								<td><?php echo $item->COST; ?> €</td>
								<td class="textCenter">
								<?php 	if($item->STATUS == 1){ ?>
											<i class="fa fa-check-circle" title="Publicado"></i>
								<?php 	} else { ?>
									<i class="fa fa-minus-circle" title="Despublicado"></i>
								<?php 	}	?>
								</td>
								<td class="textCenter">
									<a href="<?php echo DOMAINZP; ?>?view=product&mod=product&sup=<?php echo $idSup; ?>&id=<?php echo $item->ID; ?>" >
										<i class="fa fa-edit"></i>
									</a>
								</td>
								<td class="textRight">
									<a href="<?php echo DOMAINZP; ?>?view=product&mod=product&sup=<?php echo $idSup; ?>&id=<?php echo $item->ID; ?>" >
										<i class="fa fa-trash"></i>
									</a>
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

       

