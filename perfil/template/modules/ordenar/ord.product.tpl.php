<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary">
			Listado de productos
			<a href="<?php echo DOMAINZP; ?>?view=product&mod=product&tpl=list&sup=<?php echo $idSup; ?>" title="Listado de productos">
				<button type="button" class="btn btn-primary floatRight transition">Volver</button>
			</a>
		</h6>
	</div>
	<div class="col-xs-12 header-ordernar-product bgGrayStrong">
		<div class="drop_icon col-xs-1"></div>
		<div class="drop_name col-sm-9 col-xs-8">
			<h5 class="arial white">Título</h5>
		</div>
		<div class="drop_position col-sm-2 col-xs-3">
			<h5 class="white">Posición</h5>
		</div>
	</div>
	<div class="sortable"  id="drop-items">
	<?php 
		$cont = 1;
		foreach($list as $row) {

			if($row->POSITION == 0) {
				$row->POSITION = $cont;
			}
	?>
		<div class="col-xs-12 ordernar-product updated" data-index="<?php echo $row->ID; ?>" data-position="<?php echo $row->POSITION; ?>">
			<div class="drop_icon col-xs-1">
				<i class="fas fa-fw fa-arrow-alt-circle-up"></i><i class="fas fa-fw fa-arrow-alt-circle-down"></i>
			</div>
			<div class="drop_name col-sm-9 col-xs-8">
				<p class="arial grayStrong"><?php echo $row->TITLE; ?></p>
			</div>
			<div class="drop_position col-sm-2 col-xs-3">
				<p id="position-<?php echo $row->ID; ?>" class="position-product"><span><?php echo $row->POSITION; ?></span></p>
			</div>
		</div>
		<?php 
			$cont++;
		} ?>

	</div>
</div>
<script type="text/javascript">
	 function guardandoPosiciones() {
        var positions = [];
        $('.updated').each(function () {
			positions.push([$(this).attr('data-index'), $(this).attr('data-position')]);
			$(this).removeClass('updated');
        });

        $.ajax({
			url: 'template/modules/ordenar/ajax.php',
			method: 'POST',
			dataType: 'text',
			data: {
				update: 1,
				positions: positions
			}, success: function (response) {
				//console.log(response);
				$('.ordernar-product').each(function () {
					var idProduct = $(this).attr('data-index');
					var pos = $(this).attr('data-position');
					$('#position-'+idProduct).html("<span>"+pos+"</span>");
				});
			}
        });
    }
</script>  